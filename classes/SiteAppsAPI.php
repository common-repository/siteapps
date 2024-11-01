<?php

include_once SITEAPPS_CLASS_DIR . "SiteAppsMessage.php";

class SiteAppsAPI 
{
    
    private $messages;
    
    public function __construct()
    {
        if (!self::checkCurlIsLoaded()) {
            throw new Exception('curl not loaded');
        }
        $this->messages = new SiteAppsMessage();
    }
    
    public static function checkCurlIsLoaded()
    {
        if  (in_array('curl', get_loaded_extensions())) {
            return true;
        } else {
            return false;
        }
    }
    
    private function createUser($name, $email, $privateKey, $publicKey)
    {
        $userParam  = json_encode(array('user_name' => $name, 'user_email' => $email));
        $hash       = hash_hmac('sha256', $userParam, $privateKey);
        $user       = $this->getResponse('Account/add', $hash, $userParam, $publicKey);
        if ( !array_key_exists('user_id', $user) || !array_key_exists('user_key', $user)) {
            throw new Exception('No user data.');
        }
        return $user;
    }
    
    private function createSite($email, $url, $userKey , $privateKey, $publicKey)
    {
        $siteParam  = json_encode(array('user_email' => $email, 'site_url' => $url));
        $hash       = hash_hmac('sha256', $siteParam, $privateKey . $userKey);
        $site       = $this->getResponse('Site/add', $hash, $siteParam, $publicKey);
        if ( !array_key_exists('site_id', $site) || !array_key_exists('site_key', $site)) {
            throw new Exception('No site data.');
        }
        return $site;
    }
    
    private function getSegments($siteAppsId, $email, $userKey, $privateKey, $publicKey)
    {
        $segmentsParam  = json_encode(array('site_id' => $siteAppsId, 'user_email' => $email));
        
        $hash           = hash_hmac('sha256', $segmentsParam, $privateKey . $userKey);
        $segments       = $this->getResponse('Segment/getSegments', $hash, $segmentsParam, $publicKey);
        return $segments;
    }
    
    private function addFlags($flags, $siteId, $email, $userKey , $privateKey, $publicKey)
    {
        try {
            $flagsParam  = json_encode(array('site_id' => $siteId, 'user_email' => $email, 'flags' => $flags));
            $hash       = hash_hmac('sha256', $flagsParam, $privateKey . $userKey);
            $this->getResponse('Site/addFlags', $hash, $flagsParam, $publicKey);
        } catch (Exception $e) {
            $this->messages->addCustomMessage($e->getMessage(), true);
        }
    }
    
    public function createAccount($name, $email, $url, $privateKey, $publicKey)
    {
        try {
            $user = $this->createUser($name, $email, $privateKey, $publicKey);
            $site = $this->createSite($email, $url, $user['user_key'], $privateKey, $publicKey);
            $flags = array('platform' => array('wordpress', 'plugin-wordpress', 'signup-wordpress'));
            $this->addFlags($flags, $site['site_id'], $email, $user['user_key'], $privateKey, $publicKey);
            return array_merge($user, $site, $flags);
        } catch (Exception $e) {
            $this->messages->addCustomMessage($e->getMessage(), true);
            return null;
        }
    }
    
    public function getSegmentsByClient($siteAppsId, $email, $privateKey, $publicKey, $userKey)
    {
        try {
            return $this->getSegments($siteAppsId, $email, $userKey, $privateKey, $publicKey);
        } catch (Exception $e) {
            //$this->messages->addCustomMessage($e->getMessage() . $publicKey, true);
            return array();
        }
    }
    
    public function getLoginToken($siteId, $email, $privateKey, $publicKey, $userKey)
    {
        try {
            $params  = json_encode(array('site_id' => $siteId, 'user_email' => $email, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip' => $_SERVER['REMOTE_ADDR']));
            
            $hash       = hash_hmac('sha256', $params, $privateKey . $userKey);
            $result = $this->getResponse('Auth/createLoginToken', $hash, $params, $publicKey);
            
            if ( !array_key_exists('token', $result) || !array_key_exists('url_to_login', $result)) {
                throw new Exception('Can\'t login. Wrong data.');
            }

            return $result;
        } catch (Exception $e) {
            $this->messages->addCustomMessage($e->getMessage(), true);
        }
    }
    
    private function getResponse($endpoint, $hash, $params, $publicKey)
    {
        $response = json_decode($this->submit($endpoint, $hash, $params, $publicKey), 1);
        
        if ($response['status'] == 100 && is_array($response['content'])) {
            return $response['content'];
        } else {
            throw new Exception(SiteAppsMessage::changeMsg($response['msg']));
        }
    }
    
    private function submit($endpoint, $hash, $params, $publicKey)
    {
        $postData = array(
            "hash"          => $hash,
            "public-key"    => $publicKey,
            "json-data"     => $params
        );

        $url = 'https://api.siteapps.com/' . $endpoint;
        
        $result = $this->postData($url, $postData);
        return $result;
    }
    
    public function postData($url, $postData) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        return $result;
    }
    
}

?>
