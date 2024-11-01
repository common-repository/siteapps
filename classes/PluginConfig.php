<?php

class PluginConfig
{
    
    static public function getOptions()
    {
        return get_option(SITEAPPS_PLUGIN_NAME);
    }
    
    static function getDefaultOptions() 
    {
        return array(
            'id'                        => '',
            'user_key'                  => '',
            'user_email'                => '',
            'site_url'                  => '',
            'user_id'                   => '',
            'user_email'                => '',
            'site_key'                  => '',
            'flags'                     => '',
            'private_key'               => '78ce388633a64f2213ff7f19e9a8ece4',
            'public_key'                => '51f22e8dc2da04f49ef1f9a992858be2',
            'type'                      => 0,
            'version'                   => SITEAPPS_VERSION,
            'tag'                       => '',
            'debug'                     => false,
            'enable_smart_widgets'      => 1,
            'last_updated'              => 0,
            'refresh_interval'          => 3600,
            'segments'                  => array(),            
            'deprecated'                => array('SiteAppsId'),
            'widget_modes'              => array(
                                                'start_visible' => 'Visible by Default',
                                                'start_hidden'  => 'Hidden by Default'
                                            ),
            'widget_config'             => array(),
            'cloud_flare'               => false
        );
    }
    
    public function saveOptions($options)
    {
        $options = array_merge(PluginConfig::getDefaultOptions(), $options);
        update_option(SITEAPPS_PLUGIN_NAME, $options);
    }
    
    public function resetOptions()
    {
        $this->saveOptions(self::getDefaultOptions());
    }
    
    public function getTag($id, $type, $isCloudFlare = false) 
    {
        $cloudFlare = '';
        if ($isCloudFlare) {
            $cloudFlare = ' data-cfasync="false"';
        }
        
        
        $tag = '<script' . $cloudFlare .'>
                //<![CDATA[
                $SA={s:'.$id.',asynch:1};
                (function(){
                    var sa = document.createElement("script"); sa.type = "text/javascript"; sa.async = true;
                    sa.src = ("https:" == document.location.protocol ? "https://" + $SA.s + ".sa" : "http://" + $SA.s + ".a") + ".siteapps.com/" + $SA.s + ".js";
                    var t = document.getElementsByTagName("script")[0]; t.parentNode.insertBefore(sa, t);
                })();
                //]]>
                </script>'; 
        if($type == 1) {
            $tag = '<script ' . $cloudFlare .'>
                    //<![CDATA[
                    $SA={s:'.$id.'};
                    document.write(unescape("%3Cscript src=\'" + ("https:" == document.location.protocol ? "https://" + $SA.s + ".sa" : "http://" + $SA.s + ".a") + ".siteapps.com/" + $SA.s + ".js\' type=\'text/javascript\'%3E%3C/script%3E"));
                    //]]>
                    </script>';
        }
        return $tag;
    }
}