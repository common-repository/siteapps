<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SiteAppsWidget
 *
 * @author mleal
 */
class SiteAppsWidget 
{
    private $segments;
    private $plugin;
    
    public function __construct($plugin) 
    {
        $this->plugin = $plugin;
        $this->segments = $this->plugin->options['segments'];

        $this->addScriptsToWidgets();
        $this->addStylesToWidgets();
    }
    
    public function enableSmartWidgets()
    {
        if ($this->plugin->options['enable_smart_widgets'] && count($this->plugin->options['segments']) > 0) {
            add_filter('sidebars_widgets', array($this, 'filter_widgets'), 50);
            add_action('sidebar_admin_setup', array($this, 'attachCallbackToWidget'));
        }
    }
    
    private function addScriptsToWidgets()
    {
        add_action('admin_print_scripts', array($this, 'widgetScripts'));
    }
    
    private function addStylesToWidgets()
    {
        add_action('admin_print_styles', array($this, 'widgetStyles'));
    }
    
    public function widgetScripts() 
    {
        if (strstr($_SERVER['SCRIPT_NAME'], 'widgets.php')) {
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_jquery_ui');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME.'_plugin_siteapps_config');
            wp_enqueue_script(SITEAPPS_PLUGIN_NAME . '_widgets_onload', '', array(SITEAPPS_PLUGIN_NAME . '_jquery_livequery'));
        }
    }
  
    public function widgetStyles() 
    {
        if (strstr($_SERVER['SCRIPT_NAME'], 'widgets.php')) {
            wp_enqueue_style(SITEAPPS_PLUGIN_NAME . '_style_admin_widgets');
        }
    }
    
    public function attachCallbackToWidget() 
    {
        global $wp_registered_widget_controls, $wp_registered_widgets;
        // Don't show custom config when adding (there's a bug) ?
//        if (!isset($_POST['add_new'])) {
            foreach ($wp_registered_widgets as $widget_id => $widget_data) {
                // Pass widget id as param, so that we can later call the original callback function
                $wp_registered_widget_controls[$widget_id]['params'][]['widget_id'] = $widget_id;
                // Store the original callback functions and replace them with Widget Context
                $wp_registered_widget_controls[$widget_id]['callback_original_wp_siteapps'] = $wp_registered_widget_controls[$widget_id]['callback'];
                $wp_registered_widget_controls[$widget_id]['callback'] = array($this, 'replaceWidgetCallback');
            }
//        }
    }

    public function replaceWidgetCallback() 
    {
        global $wp_registered_widget_controls;

        $all_params = func_get_args();

        if (is_array($all_params[1])) {
            $widget_id = $all_params[1]['widget_id'];
        } else {
            $widget_id = $all_params[0]['widget_id'];
        }

        $original_callback = $wp_registered_widget_controls[$widget_id]['callback_original_wp_siteapps'];

        // Display the original callback
        if (isset($original_callback) && is_callable($original_callback)) {
            call_user_func_array($original_callback, $all_params);
        } else {
            print '<!-- SiteApps Plugin [controls]: could not call the original callback function -->';
        }

        $this->displayWidgetContext($original_callback, $widget_id);
    }

    public function displayWidgetContext($args = array(), $wid = null) 
    {
        if (count($this->segments) > 0) {
            $widgetConfig   = $this->plugin->options['widget_config'][$wid];
            $widgetExpression = $this->plugin->options['widget_expression'][$wid];
            $widgetModes    = $this->plugin->options['widget_modes'];
            $segments       = $this->segments;
            
            $valueMode      = (isset($this->plugin->options['widget_config'][$wid]['widget_mode']))? $this->plugin->options['widget_config'][$wid]['widget_mode']: 'start_visible';
            
            $isEnabled = ((count($widgetExpression['to_show']) > 0) || (count($widgetConfig['to_show']) > 0))?true:false;
            
            $isHide = (count($widgetConfig['to_hide']) > 0)?true:false;
            
            require(SITEAPPS_VIEW_DIR . 'widgets/widget-options-new.php');
        }
    }

    function filter_widgets($sidebars_widgets) 
    {
        foreach ($sidebars_widgets as $sidebar_id => $widgets) {
            if ($sidebar_id != 'wp_inactive_widgets' && !empty($widgets)) {
                foreach ($widgets as $widget_no => $widget_id) {
                    if (!isset($this->plugin->options['widget_config'][$widget_id]) || !count($this->plugin->options['widget_config'][$widget_id])) {
                        $this->plugin->options['widget_config'][$widget_id] = array();
                        $this->plugin->options['widget_config'][$widget_id]['widget_mode'] = 'start_visible';
                        $this->plugin->options['widget_config'][$widget_id]['to_hide'] = array();
                        $this->plugin->options['widget_config'][$widget_id]['to_show'] = array();
                    }
                }
            }
        }

        update_option(SITEAPPS_PLUGIN_NAME, $this->plugin->options);

        if (isset($_POST['siteapps_widget_config']) && !empty($_POST['siteapps_widget_config'])) {
            $this->save_widget_context();
            unset($_POST['siteapps_widget_config']);
        }

        return $sidebars_widgets;
    }

    function save_widget_context() 
    {
        //Quando se deleta um widget
        if (isset($_POST['delete_widget']) && $_POST['delete_widget']) {
            $del_id = $_POST['widget-id'];
            unset($this->plugin->options['widget_config'][$del_id]);
            unset($this->plugin->options['widget_expression'][$del_id]);
        } else {
            //Quando salva um widget
            //siteapps_widget_config[widget_config][$wid][to_show][$friendlyName]
            //siteapps_widget_config[widget_config][$wid][to_hide][$friendlyName]
            //siteapps_widget_config[widget_expression][$wid][to_show]
            
            $new_settings = $_POST['siteapps_widget_config']['widget_config'];
            $newExpression = $_POST['siteapps_widget_config']['widget_expression'];
            
            foreach ($newExpression as $widgetId => $expression) {
                if (!isset($new_settings[$widgetId]['widget_mode'])) {
                    unset($this->plugin->options['widget_expression'][$widgetId]);
                } else {
                    $this->plugin->options['widget_expression'][$widgetId] = $expression;
                }
            }
            
            if ($new_settings) {
                foreach($new_settings as $widget_id => $widget_settings) {
                    if ($widget_settings['widget_mode'] == "") {
                        unset($this->plugin->options['widget_config'][$widget_id]);
                    } else {
                        $this->plugin->options['widget_config'][$widget_id] = $widget_settings;
                    }

                    //Nao sei pra que serve isso
                    //ver com o pessoal
                    if (isset($_POST['widget-links'])) {
                        foreach ($_POST['widget-links'] as $links_widget_num => $links_widget_data) {
                            if (strlen($links_widget_data['category'])) {
                                $this->plugin->options['widget_config']['linkcat-'.$links_widget_data['category']] = $widget_settings;
                                $this->plugin->options['widget_config'][$widget_id]['lookup'][] = 'linkcat-'.$links_widget_data['category'];
                            } else {
                                $args = array('offset' => 0, 'hide_empty' => 0);
                                $categories = get_terms('link_category', $args);
                                foreach ($categories as $category) {
                                    $this->plugin->options['widget_config']['linkcat-'.$category->term_id] = $widget_settings;
                                    $this->plugin->options['widget_config'][$widget_id]['lookup'][] = 'linkcat-'.$category->term_id;
                                }
                            }
                        }
                    }
                }
            }
        }

        update_option(SITEAPPS_PLUGIN_NAME, $this->plugin->options);
        
        return;
    }
    
}

?>
