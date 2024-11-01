<?php

class SiteAppsPluginInstall extends PluginWPBase
{
    private $requiredWPVersion;
    private $options;
    
    public function __construct($requiredWPVersion, $options)
    {
        $this->requiredWPVersion = $requiredWPVersion;
        $this->options = $options;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function activate() 
    {
        $this->checkVersion();
        $this->checkOptions();
    }
    
    public function checkVersion()
    {
        global $wp_version;
        if (!empty($wp_version) && version_compare($wp_version, $this->requiredWPVersion, "<")) {
            add_action('admin_notices', array($this, 'notifyVersion'));
        }
    }
    
    static function getDefaultOptions() 
    {
        return PluginConfig::getDefaultOptions();
    }
    
    private function checkOptions()
    {
        if (!$this->options) {
            $this->options = self::getDefaultOptions();
            //Remove this in the next version
            $this->options = $this->setOldId($this->options);
            update_option(SITEAPPS_PLUGIN_NAME, $this->options);
            
        } elseif($this->options && array_key_exists('version', $this->options) && version_compare($this->options['version'], SITEAPPS_VERSION, "<")) {
            $this->upgradeOptions();
        } else {
            add_action('admin_notices', array($this , 'notifyOrphanOptions'));
            //I dont know why this, It is from BT Buckets Plugin
            //$this->a_check_orphan_options();
        }
    }
    
    function setOldId($options)
    {
        $oldOption = get_option("SiteAppsId");
        if (isset($oldOption) && is_array($oldOption) && array_key_exists('id', $oldOption)) {
           $options['id'] = $oldOption['id'];
        }
        return $options;
    }
    
    function upgradeOptions() 
    {
        $defaultOptions = self::getDefaultOptions();
        
        $this->options['version'] = SITEAPPS_VERSION;
        
        foreach($defaultOptions as $optionName => $optionValue) {
            if(!isset($this->options[$optionName])) {
                $this->options[$optionName] = $optionValue;
            }
        }
        
        $this->options = $this->setOldId($this->options);
        
        foreach($defaultOptions['deprecated'] as $optionName) {
            if(isset($this->options[$optionName])) {
                unset($this->options[$optionName]);
            }
        }
        
        update_option(SITEAPPS_PLUGIN_NAME, $this->options);
        
        add_action('admin_notices', array($this, 'notifyUpgrade'));
    }
    
    public function notifyUpgrade() 
    {
        SiteAppsUtil::notify(sprintf(__('%s options has been upgraded.', SITEAPPS_PLUGIN_NAME), "SiteApps Plugin"));
    }

    public function notifyOrphanOptions() 
    {
        SiteAppsUtil::notify(sprintf('%s', __('Some option settings were missing (possibly from plugin upgrade).', SITEAPPS_PLUGIN_NAME)));
    }
    
    
    public function notifyVersion() 
    {
        global $wp_version;
        SiteAppsUtil::notify(
          sprintf(__('You are using WordPress version %s.', SITEAPPS_PLUGIN_NAME), $wp_version).' '.
          sprintf(__('%s recommends that you use WordPress %s or newer.', SITEAPPS_PLUGIN_NAME), "SiteApps Plugin", $this->requiredWPVersion).' '.
          sprintf(__('%sPlease update!%s', SITEAPPS_PLUGIN_NAME), '<a href="http://codex.wordpress.org/Upgrading_WordPress">', '</a>'),
          true);
    }
    
    
//    function a_check_orphan_options() 
//    {
//        $options = get_option(SITEAPPS_PLUGIN_NAME);
//        if (!$options) {
//            add_action('admin_notices', array($this , 'notifyOrphanOptions'));
//            $this->upgradeOptions();
//            return true;
//        } else {
//            $defaultOptions = $this->p->a->default_options();
//            foreach( $default_options as $key => $value ) {
//                if ( !array_key_exists($key, $options) ) {
//                    add_action('admin_notices', array($this , 'a_notify_orphan_options'));
//                    $this->a_upgrade_options();
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
}

?>
