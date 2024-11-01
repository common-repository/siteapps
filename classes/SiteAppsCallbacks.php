<?php

include_once SITEAPPS_CLASS_DIR . "PluginWPBase.php";
include_once SITEAPPS_CLASS_DIR . "SiteAppsAPI.php";

class SiteAppsCallbacks extends PluginWPBase
{

    private function checkReferer()
    {
        check_admin_referer(SITEAPPS_PLUGIN_NAME);
    }
    
    private function canEditOptions()
    {
        $this->checkReferer();
        if (!current_user_can('manage_options')) {
            $this->addHeadWarning(SiteAppsMessage::CANNOT_EDIT_OPTIONS);
            return;
        }
    }
    
    public function requestCallbacks()
    {
        if (isset($_POST['siteapps_save']) && $_POST['siteapps_save']) {
            $this->saveOptions();
        } elseif (isset($_POST['siteapps_reset']) && $_POST['siteapps_reset']) {
            $this->reset();
        } elseif (isset($_POST['siteapps_create_account']) && $_POST['siteapps_create_account']) {
            $this->signup();
        } elseif (isset($_POST['siteapps_login']) && $_POST['siteapps_login']) {
            $this->login();
        }
    }
    
    private function reset()
    {
        $this->canEditOptions();
        
        $pluginConfig = new PluginConfig();
        $pluginConfig->resetOptions();
        
        $this->addHeadWarning(SiteAppsMessage::TAG_OFF);
        $this->redirect(SiteAppsPages::SETTINGS);
    }
    
    private function isValidSiteAppsId($siteAppsId)
    {
        if (isset($siteAppsId) && $siteAppsId > 0) {
            return true;
        }
        return false;
    }
    
    private function saveOptions() 
    {
        $this->canEditOptions();
        $pluginConfig = new PluginConfig();
                
        if ($this->isValidSiteAppsId($_POST['sa_id'])) {
            $options = PluginConfig::getOptions();
            $options['id']                  = (int) $_POST['sa_id'];
            $options['user_key']            = trim($_POST['sa_user_key']);
            $options['user_email']          = trim($_POST['sa_email']);
            $options['type']                = (int) $_POST['sa_tag_type'];
            $options['debug']               = (bool) $_POST['sa_debug'];
            $options['enable_smart_widgets'] = (int) $_POST['sa_enable_smart_widgets'];
            $options['cloud_flare']         = (bool) $_POST['sa_cloud_flare'];
            $options['tag'] = $pluginConfig->getTag($options['id'],$options['type'], $options['cloud_flare']);
            
            
//            print_r($options);
//            die;
            
            $pluginConfig->saveOptions($options);
            
            if ($options['id'] && $options['user_email'] && $options['user_key']) {
                $this->updateSegments($options['id'], $options['user_email'], $options['user_key']);
            }
            $this->addHeadWarning(SiteAppsMessage::TAG_ON);
            
            $this->redirect(SiteAppsPages::SETTINGS);
        } elseif (isset($_POST['sa_id']) && $_POST['sa_id'] < 1) {
            $this->addHeadWarning(SiteAppsMessage::INVALID_SITEAPPS_ID);
        }
    }
    
    private function signup()
    {
        $this->canEditOptions();
        $pluginConfig = new PluginConfig();
        $options = PluginConfig::getOptions();
        
        if ($_POST['siteapps_signup_site_url'] && $_POST['siteapps_signup_email'] && $_POST['siteapps_signup_name']) {
            $options['site_url']    = $_POST['siteapps_signup_site_url'];
            $options['user_email']  = $_POST['siteapps_signup_email'];
            $options['user_name']   = $_POST['siteapps_signup_name'];
            
            $siteAppsAPI    = new SiteAppsAPI();
            $account       = $siteAppsAPI->createAccount($options['user_name'], $options['user_email'], $options['site_url'], $options['private_key'], $options['public_key']);

            if ($account == null) {
               return false; 
            }
            
            $options['id']                  = $account['site_id'];
            $options['user_id']             = $account['user_id'];
            $options['user_key']            = $account['user_key'];
            $options['site_key']            = $account['site_key'];
            $options['flags']               = json_encode($account['platform']);
            
            $options['tag'] = $pluginConfig->getTag($options['id'], $options['type']);
            $pluginConfig->saveOptions($options);
            
            if ($options['id'] && $options['user_email'] && $options['user_key']) {
                $this->updateSegments($options['id'], $options['user_email'], $options['user_key']);
            }
            
            
            $this->addHeadWarning(SiteAppsMessage::TAG_ON);
            
            $this->redirect(SiteAppsPages::SETTINGS);
        } else {
            $this->addHeadWarning(SiteAppsMessage::INVALID_SIGNUP_PARAMS);
        }
    }
    
    private function login()
    {
        try {
            $pluginConfig = new PluginConfig();
            $options = PluginConfig::getOptions();
            $siteAppsAPI = new SiteAppsAPI();
            $token       = $siteAppsAPI->getLoginToken($options['id'], $options['user_email'], $options['private_key'], $options['public_key'], $options['user_key']);
            $options['site_token']            = $token;
            $pluginConfig->saveOptions($options);
            
            if ($token['token']) {
                header("Location: " . $token['url_to_login'] . $token['token']);
            } else {
                header('location: https://siteapps.com/Dashboard?utm_source=wordpress&utm_medium=plugin&utm_campaign=settings_info&utm_content=');
            }
        } catch (Exception $e) {
            $this->messages->addCustomMessage($e->getMessage() , true);
        }
        
    }
    
    public function updateSegments($siteId, $email, $userKey)
    {
        try {
            $pluginConfig = new PluginConfig();
            $options = PluginConfig::getOptions();
            
            $siteAppsAPI    = new SiteAppsAPI();
            $segments       = $siteAppsAPI->getSegmentsByClient($siteId, $email, $options['private_key'], $options['public_key'], $userKey);
            
            if (count($segments) > 0) {
                $newSegments = array();
                foreach ($segments as $segment) {
                    $newSegments[$segment['friendly_name']] = $segment;
                }
                $options['segments'] = $newSegments;
                $pluginConfig->saveOptions($options);
                return true;
            } else {
                $this->addHeadWarning(SiteAppsMessage::SITEAPPS_ID_NOT_FOUND);
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}