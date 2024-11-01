<?php

include_once SITEAPPS_CLASS_DIR . "SiteAppsMessage.php";
include_once SITEAPPS_CLASS_DIR . "PluginConfig.php";

class PluginWPBase
{
    protected $messages;
    
    public function __construct()
    {
        $this->messages = new SiteAppsMessage();
    }
    
    public function addHeadWarning($msg)
    {
        add_action('admin_head', array($this->messages, $msg));  
    }
    
    public function redirect( $page, $additional_flags = "" ){
	$page = get_bloginfo( "wpurl" ) . "/wp-admin/admin.php?page=" . $page . $additional_flags;
	wp_redirect($page);
        exit;
    }
}   