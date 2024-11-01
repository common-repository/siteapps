<?php

include_once SITEAPPS_CLASS_DIR . "PluginWPBase.php";

class SiteAppsPages extends PluginWPBase
{
    
    private $isConfigured;
    
    const DASHBOARD = 'siteapps_dashboard';
    const SETTINGS = 'siteapps_settings';
    const SEGMENTATION = 'siteapps_segmentation';


    public function __construct() 
    {
        $options = PluginConfig::getOptions();
        $this->isConfigured = ($options['id'] && $options['user_key'] && $options['user_email']);
        $this->oldConfigured = ($options['id']);
    }
    
    public function buildMenu() 
    {
//        $homePage = SiteAppsPages::DASHBOARD;
//        if (!$this->isConfigured) {
//            $homePage = SiteAppsPages::SETTINGS;
//        }
        $homePage = SiteAppsPages::SETTINGS;
        
        $optionsPage = add_menu_page(  __( 'SiteApps Configuration', SITEAPPS_PLUGIN_NAME),
                        __( 'SiteApps', SITEAPPS_PLUGIN_NAME), 
                            'manage_options', 
                            $homePage, 
                            array( $this, $homePage ), 
                            plugins_url(SITEAPPS_PLUGIN_NAME) . '/images/siteapps-icon.png', 
                            '155.1010' );
        add_action('admin_print_scripts-'.$optionsPage, array($this, 'loadSettingsScripts'));
        
//        if ($this->isConfigured) {
//            add_submenu_page( SiteAppsPages::DASHBOARD,
//                    __( 'Dashboard', SITEAPPS_PLUGIN_NAME ), 
//                    __( 'Dashboard', SITEAPPS_PLUGIN_NAME ), 
//                    'manage_options', SiteAppsPages::DASHBOARD, 
//                    array( $this, SiteAppsPages::DASHBOARD ));
//        }
        
//        $optionsPage2 = add_submenu_page( SiteAppsPages::SETTINGS,
//                __( 'Settings', SITEAPPS_PLUGIN_NAME ), 
//                __( 'Settings', SITEAPPS_PLUGIN_NAME ), 
//                'manage_options', SiteAppsPages::DASHBOARD, 
//                array( $this, SiteAppsPages::SETTINGS ));
//        add_action('admin_print_scripts-'.$optionsPage2, array($this, 'loadSettingsScripts'));
        
//        if ($this->isConfigured) {
//            add_submenu_page( SiteAppsPages::DASHBOARD,
//                    __( 'Segmentation', SITEAPPS_PLUGIN_NAME ), 
//                    __( 'Segmentation', SITEAPPS_PLUGIN_NAME ), 
//                    'manage_options', SiteAppsPages::SEGMENTATION, 
//                    array( $this, SiteAppsPages::SEGMENTATION ));
//        }
        
//        global $submenu;
//        if ( isset( $submenu['siteapps-dashboard'] ) )
//                $submenu['siteapps-dashboard'][0][0] = __( 'Dashboard', SITEAPPS_PLUGIN_NAME );
        
//        //keeping old page
//        $optionsPage2 = add_options_page('SiteApps Plugin', 'SiteApps', 'manage_options', 'siteapps-dashboard', array($this, "settings"));
//        add_action('admin_print_scripts-'.$optionsPage2, array($this, 'loadSettingsScripts'));   
    }
    
    
// Pages
    
    public function siteapps_settings()
    {
        
        //die("suiahsuis");
        
        $options = get_option(SITEAPPS_PLUGIN_NAME);
        global $current_user;
        get_currentuserinfo();
        
        $saId               = $options['id'];
        $userKey            = $options['user_key'];
        $emailConfig        = $options['user_email'];
        $syncCheck          = ($options['type'] == 1)?'checked="true"':'';
        $asyncCheck         = ($options['type'] == 1)?'':'checked="true"';
        $smartWidgetCheck   = ($options['enable_smart_widgets'])?'checked="true"':'';
        $debugCheck         = ($options['debug'])?'checked="true"':'';
        $cloudFlareCheck    = ($options['cloud_flare'] == 1)?'checked="true"':'';
        
        $siteUrl            = ($options['site_url'])? $options['site_url']:(($_POST['siteapps_signup_site_url'])? $_POST['siteapps_signup_site_url']: get_site_url());
        $name               = ($options['user_name'])? $options['user_name']:(($_POST['siteapps_signup_name'])? $_POST['siteapps_signup_name']: $current_user->user_firstname . $current_user->user_lastname);
        $email              = ($options['user_email'])? $options['user_email']: (($_POST['siteapps_signup_email']) ? $_POST['siteapps_signup_email']: $current_user->user_email);
        $segments           = $options['segments'];
        $isConfigured       = $this->isConfigured;
        $oldConfigured       = $this->oldConfigured;
        
        if (!$isConfigured && !$oldConfigured) {
            require_once(SITEAPPS_VIEW_DIR.'admin/home.php');  
        } else {
            require_once(SITEAPPS_VIEW_DIR.'admin/settings.php');  
        }
    }
    
    //scripts
    public function loadSettingsScripts() 
    {
        if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?page=' . SiteAppsPages::SETTINGS)
                ||
            strstr($_SERVER['REQUEST_URI'], 'admin.php?page=' . SiteAppsPages::SETTINGS)) {
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_jquery_ui');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_jquery_livequery');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_plugin_siteapps_config');
        }
    }
}