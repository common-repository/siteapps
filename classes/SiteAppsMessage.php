<?php

class SiteAppsMessage
{
    const CURL_NOT_LOADED = 'curlNotLoaded';
    const CANNOT_EDIT_OPTIONS = 'cannotEdit';
    const INVALID_SITEAPPS_ID = 'invalidSiteAppsId';
    const TAG_OFF = 'tagOFF';
    const TAG_ON = 'tagON';
    const CONFIGURE_SITEAPPS = 'configureSiteApps';
    const SITEAPPS_ID_NOT_FOUND = 'siteAppsIdNotFound';
    const USER_NOT_FOUND = 'userNotFound';
    const INVALID_SIGNUP_PARAMS = 'invalidSignupParams';
    const OLD_CONFIGURATION = 'oldConfiguration';
    
    private $msg;
    private $error;
    
    public function __construct()
    {
        $this->msg = null;
        $this->error = null;
    }
    
    public function addHeadWarning($msg)
    {
        add_action('admin_head', array($this, $msg));  
    }
    
    public function addCustomMessage($msg, $error = false)
    {
        $this->msg = $msg;
        $this->error = $error;
        add_action('admin_head', array($this, 'showCustomMessage'));  
    }
    
    public function showCustomMessage()
    {
        if ($this->msg && $this->error) {
            self::notify(sprintf(__(str_replace("\n", "<br>", $this->msg))), $this->error);
        }
    }
    
    public static function notify($message, $error=false, $style = "")
    {
        if (!$error) {
            print '<div class="updated fade"><p>'.$message.'</p></div>';
        } else {
            print '<div class="error" style="'. $style . '"><p>'.$message.'</p></div>';
        }
    }
    
    public static function specialNotify()
    {
        print '
            <style type="text/css">
            .wpbar{ padding: 8px; font-family: Arial; font-size: 14px; color: #fff; font-weight: bold; position: relative; overflow-y: hidden }
            .wpbar span{ display: inline-block; padding-left: 20px; }
            .wpbar a.logo{ display: block; float: right; width: 126px; height: 23px; background: url(/wp-content/plugins/siteapps/images/salogo.png) no-repeat; background-size: contain; position: relative; top: 3px; }
            .btn_action1{ display: inline-block; font-family: \'Open Sans\', \'Arial\';color: #fff;font-weight: bold;font-size: 14px;text-align: center;margin: auto;border-radius: 5px;border: 1px solid #f47318;border-bottom: 2px solid #c85011;padding: 4px 10px;text-shadow: 1px 1px 1px rgb(173, 86, 27);text-decoration: none;background: #f7941f;background: -moz-linear-gradient(top, #f7941f 0%, #f16014 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7941f), color-stop(100%,#f16014));background: -webkit-linear-gradient(top, #f7941f 0%,#f16014 100%);background: -o-linear-gradient(top, #f7941f 0%,#f16014 100%);background: -ms-linear-gradient(top, #f7941f 0%,#f16014 100%);background: linear-gradient(to bottom, #f7941f 0%,#f16014 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#f7941f\', endColorstr=\'#f16014\',GradientType=0 ); }
            .btn_action1:hover{ color: #ffffcc;}
            .rocket{ width: 200px; height: 240px; background: url(/wp-content/plugins/siteapps/images/rocket.png) no-repeat; position: absolute; right: 129px; top: -100px;  }
            </style>
            <div class="updated" style="padding:0px !important; background-color: #000;">
                <div class="wpbar wpbar_grad1">
                        <a href="admin.php?page=' . SiteAppsPages::SETTINGS . '" class="btn_action1">Activate your SiteApps account</a>
                        <span>Get started optimizing your WordPress website immediately</span>
                        <a href="#" class="logo" style="cursor: default"></a>
                        <div class="rocket"></div>
                    </div>
            </div>';
    }

    public function invalidSignupParams()
    {
        self::notify('Your Name, User E-mail or Site Url is invalid.', true);
    }
    
    public function userNotFound()
    {
        self::notify('User not found.', true);
    }
    
    public function curlNotLoaded()
    {
        self::notify('You don\'t have curl extension installed. Please, install it for use segments', true);
    }
    
    public function cannotEdit()
    {
        self::notify('You cannot edit the SiteApps options.', true);
    }
    
    public function invalidSiteAppsId()
    {
        self::notify( 'Invalid SiteApps ID!', true);
    }

    public function tagOFF()
    {
       self::notify('SiteApps Tag is <strong>OFF</strong>. You must enter your user API key to activate it.');      
    }
    
    public function tagON()
    {
        self::notify('SiteApps settings updated, SiteApps Tag is <strong>ON</strong>.');
    }
    
    public function configureSiteApps()
    {
        self::specialNotify();
    }
    
    public function siteAppsIdNotFound()
    {
        self::notify(sprintf(__('Unable to update SiteApps segment information.  Please update your user API key information <a href="admin.php?page=' . SiteAppsPages::SETTINGS . '">here</a>.')), true);
    }
    
    public function oldConfiguration()
    {
        self::notify(sprintf(__('Unable to update SiteApps segment information. Please update your Account Email and User API key information below to personalize your website with SiteApps segments.')), true, "border-left:0px;border: 1px solid #DD3D36;");
    }
    
    public static function changeMsg($msg) 
    {
        if ($msg == "Your email has already been registered.") {
            return "A SiteApps account already exists for this email account.  <a href='https://siteapps.com/general/login' target='_blank'>Login here</a> or <a href='https://siteapps.com/users/forgot_pass' target='_blank'>reset your password</a>";
        }
    }

}