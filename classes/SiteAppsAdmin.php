<?php

include_once SITEAPPS_CLASS_DIR . "SiteAppsPages.php";
include_once SITEAPPS_CLASS_DIR . "SiteAppsAPI.php";
include_once SITEAPPS_CLASS_DIR . "SiteAppsCallbacks.php";

class SiteAppsAdmin extends PluginWPBase
{
    
    public $plugin;
    
    public function __construct($plugin) 
    {
        parent::__construct();
        
        $this->plugin = $plugin;
        
        $this->installation();
        
        $this->setDefaultAttributes();
        
        //add All Scripts here
        $this->scripts();
        
        //Add All Styles here
        $this->styles();
        
        $this->registerCallBacks();
        
        //acho que isso deve rolar somente nas páginas de adm
        $this->checkSegmentsUpdate();
        
        $this->renderPage();
        
        $this->widgets();
        
        $this->checkEmptySiteAppsId();
        
        $this->checkDependencies();
    }
    
    private function installation()
    {
        include_once SITEAPPS_CLASS_DIR . "SiteAppsPluginInstall.php";
        $install = new SiteAppsPluginInstall($this->plugin->requiredWpVersion, $this->plugin->options);
        register_activation_hook($this->plugin->path . '/' . SITEAPPS_PLUGIN_NAME . '.php', array($install, 'activate'));
        
        //acho que isso nunca é executado
        $this->plugin->options = $install->getOptions();
        //$install->checkVersion();
        if($this->plugin->options['id'] > 0) {
            $this->plugin->options['tag'] = $this->getTag($this->plugin->options['id'],$this->plugin->options['type'], $this->plugin->options['cloud_flare']);
        }
    }
    
    private function setDefaultAttributes()
    {
        $pluginConfig   = new PluginConfig();
        $options_old    = PluginConfig::getDefaultOptions();
        $options        = PluginConfig::getOptions();

        $options['private_key']         = ($options['private_key'])?$options['private_key']:$options_old['private_key'];
        $options['public_key']          = ($options['public_key'])?$options['public_key']:$options_old['public_key'];
        $options['version']             = ($options['version'])?$options['version']:$options_old['version'];
        $options['type']                = (is_int($options['type']))?$options['type'] :$options_old['type'];
        $options['enable_smart_widgets'] = ($options['enable_smart_widgets'])?$options['enable_smart_widgets']:$options_old['enable_smart_widgets'];
        $options['last_updated']        = ($options['last_updated'])?$options['last_updated']:$options_old['last_updated'];
        $options['refresh_interval']    = ($options['refresh_interval'])?$options['refresh_interval']:$options_old['refresh_interval'];
        $options['deprecated']          = ($options['deprecated'])?$options['deprecated']:$options_old['deprecated'];
        $options['widget_modes']        = ($options['widget_modes'])?$options['widget_modes']:$options_old['widget_modes'];
        $options['cloud_flare']         = ($options['cloud_flare'])?$options['cloud_flare']:$options_old['cloud_flare'];
        
        //fixing bug 0 tag
        $options['tag']        = ($options['tag'] == 0)?'':$options['tag'];
        
        if (!$options['tag'] && $options['id']) {
            $options['tag'] = $pluginConfig->getTag($options['id'],$options['type'], $options['cloud_flare']);
        }

        $pluginConfig->saveOptions($options);
    }
    
    private function widgets()
    {
        include_once SITEAPPS_CLASS_DIR . "widget/SiteAppsWidget.php";

        $widgetConfig = new SiteAppsWidget($this->plugin);
        $widgetConfig->enableSmartWidgets();
    }
    
    private function scripts()
    {
        if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?page=' . SiteAppsPages::SETTINGS)
                ||
            strstr($_SERVER['REQUEST_URI'], 'admin.php?page=' . SiteAppsPages::SETTINGS)
                                    ||
            strstr($_SERVER['REQUEST_URI'], 'widgets.php')) {
                add_action('admin_init', array($this, 'loadScripts'));
            }
    }
    
    public function loadScripts() 
    {
        wp_register_script(SITEAPPS_PLUGIN_NAME . '_jquery_ui', plugins_url(SITEAPPS_PLUGIN_NAME) . '/jqueryui/jquery-ui-1.10.3.custom.min.js', array('jquery'));
        wp_register_script(SITEAPPS_PLUGIN_NAME . '_plugin_siteapps_config', plugins_url(SITEAPPS_PLUGIN_NAME) . '/js/siteapps-config.js', array('jquery'));
        wp_register_script(SITEAPPS_PLUGIN_NAME . '_jquery_livequery', plugins_url(SITEAPPS_PLUGIN_NAME) . '/js/jquery.livequery.js', array('jquery'));
        wp_register_script(SITEAPPS_PLUGIN_NAME . '_widgets_onload', plugins_url(SITEAPPS_PLUGIN_NAME) . '/js/widgets-onload.js', array('jquery'));
    }
    
    public function loadAdminScripts() 
    {
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_jquery_ui');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_jquery_livequery');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_plugin_siteapps_config');
    }
    
    private function styles()
    {
        if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?page=' . SiteAppsPages::SETTINGS)
                ||
            strstr($_SERVER['REQUEST_URI'], 'admin.php?page=' . SiteAppsPages::SETTINGS)
                                    ||
            strstr($_SERVER['REQUEST_URI'], 'widgets.php')) {
                add_action('admin_init', array($this, 'loadStyles'));    
                add_action('admin_print_styles', array($this, 'widgetStyles'));
            }
    }
    
    public function loadStyles() 
    {
        wp_register_style(SITEAPPS_PLUGIN_NAME . '_jquery_ui', plugins_url(SITEAPPS_PLUGIN_NAME)  . '/jqueryui/ui-darkness/jquery-ui-1.10.3.custom.min.css');
        wp_register_style(SITEAPPS_PLUGIN_NAME . '_style_admin', plugins_url(SITEAPPS_PLUGIN_NAME)  . '/css/style-admin.css');
        wp_register_style(SITEAPPS_PLUGIN_NAME . '_style_admin_widgets', plugins_url(SITEAPPS_PLUGIN_NAME) . '/css/style-admin-widgets.css');
    }
    
    public function widgetStyles()
    {
        wp_enqueue_style(SITEAPPS_PLUGIN_NAME . '_jquery_ui');
        wp_enqueue_style(SITEAPPS_PLUGIN_NAME . '_style_admin');
    }
    
    private function registerCallBacks()
    {
        $siteAppsCallbacks = new SiteAppsCallbacks();
        add_action('admin_init', array($siteAppsCallbacks, 'requestCallbacks'));
    }
    
    private function renderPage()
    {
        $siteAppsPages = new SiteAppsPages();
        add_action('admin_menu', array($siteAppsPages,'buildMenu'));
    }
   
    private function checkSegmentsUpdate() 
    {
        if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?page=' . SiteAppsPages::SETTINGS)
                ||
            strstr($_SERVER['REQUEST_URI'], 'admin.php?page=' . SiteAppsPages::SETTINGS)) {
            if (!isset($_POST['siteapps_save']) && !isset($_POST['siteapps_create_account']) && 
                    $this->plugin->options['id'] && $this->plugin->options['user_email'] && $this->plugin->options['user_key']) {
                if ((time() - $this->plugin->options['last_updated']) > $this->plugin->options['refresh_interval']) {
                    $siteAppsCallbacks = new SiteAppsCallbacks();
                    $siteAppsCallbacks->updateSegments($this->plugin->options['id'], $this->plugin->options['user_email'], $this->plugin->options['user_key']);
                }
            }
       }
    }

    private function checkEmptySiteAppsId() 
    {
        $this->plugin->options = get_option(SITEAPPS_PLUGIN_NAME);
        
        if (!($this->plugin->options['id']) && !isset($_POST['sa_id'])) {
            if( !strstr($_SERVER['REQUEST_URI'], 'admin.php?page=' . SiteAppsPages::SETTINGS)) {
                $this->addHeadWarning(SiteAppsMessage::CONFIGURE_SITEAPPS);
            }
        }
    }
    
    private function checkDependencies()
    {
        //refactoring this - put pages in a array SiteAppsPages
        if (strstr($_SERVER['REQUEST_URI'], 'options-general.php?page=' . SiteAppsPages::SETTINGS)
                ||
            strstr($_SERVER['REQUEST_URI'], 'admin.php?page=siteapps' . SiteAppsPages::SETTINGS)) {
            
            if (!SiteAppsAPI::checkCurlIsLoaded()) {
                $this->addHeadWarning(SiteAppsMessage::CURL_NOT_LOADED);
            }
        }
    }

    
    private function getTag($id, $type, $cloudFlare = false) 
    {
        $pluginConfig = new PluginConfig();
        return $pluginConfig->getTag($id, $type, $cloudFlare);
    }
    
}

?>
