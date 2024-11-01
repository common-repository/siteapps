<?php

/*
 * Base Class
 */

class SiteAppsPlugin
{

    public $path;
    public $requiredWpVersion = '2.9';
    public $options;
    private $current_widget_id;

    public function __construct($path)
    {
        $this->path = $path;
        $this->start();
    }

    public function start()
    {
        $this->options = get_option(SITEAPPS_PLUGIN_NAME);

        if (is_admin()) {
            include_once dirname(__FILE__) . '/SiteAppsAdmin.php';
            $siteAppsAdmin = new SiteAppsAdmin($this);
        } else {
            add_filter('init', array($this, 'renderPublic'));
        }
        add_filter('init', array($this, 'addWidgets'));
        add_action('widgets_init', array($this, 'addWidgets'));

    }

    public function renderPublic()
    {
        add_action('wp_head', array($this, 'insertTag'), 20);
        add_filter('the_content', array($this, 'insertDivs'), 20);

        if ($this->options['enable_smart_widgets'] && count($this->options['widget_config'])) {
            add_action('init', array($this, 'register_styles'));
            add_action('init', array($this, 'register_scripts'));
            add_action('wp_print_styles', array($this, 'enqueue_styles'));
            add_action('wp_print_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_head', array($this, 'replace_widget_output_callback'));
            add_action('wp_footer', array($this, 'show_and_hide_widgets'));
        }
    }

    public function addWidgets()
    {
        require_once(SITEAPPS_CLASS_DIR . '/widget/SiteAppsDivManipulator.php');
        register_widget('SiteAppsDivManipulator');
    }

    public function insertDivs($content)
    {
        if (is_single()) {
            $content = sprintf('<div id="sa_post"></div>%s<div id="sa_footer"></div>', $content);
        }
        return $content;
    }

    public function insertTag()
    {
        if (isset($this->options['tag'])) {
            $this->options['tag'] = ($this->options['tag'] === 0)?'':$this->options['tag'];
            printf("<!-- @SiteAppsPluginWordpress.version=%s - SiteApps Tag-->%s<!-- SiteApps tag end -->\n", SITEAPPS_VERSION, $this->options['tag']);
        }
    }

    public function register_styles()
    {
        wp_register_style(SITEAPPS_PLUGIN_NAME . '_style', plugins_url(SITEAPPS_PLUGIN_NAME) . 'css/style.css');
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(SITEAPPS_PLUGIN_NAME . '_style');
    }

    public function register_scripts()
    {
        
    }

    public function enqueue_scripts()
    {
        
    }

    public function replace_widget_output_callback()
    {
        global $wp_registered_widgets;
        foreach ($wp_registered_widgets as $widget_id => $widget_data) {
            $wp_registered_widgets[$widget_id]['params'][]['widget_id'] = $widget_id;
            $wp_registered_widgets[$widget_id]['callback_original_siteapps'] = $wp_registered_widgets[$widget_id]['callback'];
            $wp_registered_widgets[$widget_id]['callback'] = array($this, 'replace_widget_output');
        }
    }

    public function replace_widget_output()
    {
        global $wp_registered_widgets;
        $all_params = func_get_args();
        if (is_array($all_params[2])) {
            $widget_id = $all_params[2]['widget_id'];
        } else {
            $widget_id = $all_params[1]['widget_id'];
        }

        $widget_callback = $wp_registered_widgets[$widget_id]['callback_original_siteapps'];

        if (is_callable($widget_callback)) {
            $this->current_widget_id = $widget_id;
            ob_start(array($this, 'prepare_widget'));
            call_user_func_array($widget_callback, $all_params);
            ob_end_flush();
            $this->current_widget_id = null;
            return true;
        } elseif (!is_callable($widget_callback)) {
            print '<!-- widget context: could not call the original callback function -->';
            return false;
        } else {
            return false;
        }
    }

    function prepare_widget($buffer)
    {
        //hide widgets preconfigured
        $widget_id = $this->current_widget_id;

        if ($this->options['widget_config'][$widget_id]['widget_mode'] == 'start_hidden') {
            // add style="display:none" to widget block if it starts as hidden
            if (isset($this->options['widget_config'][$widget_id]['lookup'])) {
                // links widget
                $id = implode('|', $this->options['widget_config'][$widget_id]['lookup']);
            } else {
                $id = $widget_id;
            }
            $buffer = preg_replace('/( id=["\'](' . $id . ')["\'] )/', '$1style="display:none" ', $buffer, 1);
        }

        return $buffer;
    }

    function show_and_hide_widgets()
    {
        if ($this->options['tag'] == '') {
            return '';
        }

        $show_list = array();
        $hide_list = array();

        $debug = $this->options['debug'];
        foreach ($this->options['segments'] as $friendlyName => $config) {
            $show_list[$friendlyName] = array();
            $hide_list[$friendlyName] = array();
        }

        foreach ($this->options['widget_config'] as $w_id => $w_config) {
            if ($w_config) {
                if ($w_config['widget_mode'] == 'start_hidden' && isset($w_config['to_show'])) {
                    foreach ($w_config['to_show'] as $segment => $show) {
                        if ($show) {
                            $show_list[$segment][] = $w_id;
                        }
                    }
                } elseif (isset($w_config['to_hide'])) {
                    foreach ($w_config['to_hide'] as $segment => $hide) {
                        if ($hide) {
                            $hide_list[$segment][] = $w_id;
                        }
                    }
                }
            }
        }

        if (is_array($this->options['widget_expression'])) {
            $expressionList = array();
            foreach ($this->options['widget_expression'] as $widgetId => $expression) {
                if (array_key_exists('to_show', $expression)) {
                    $expressionList[$widgetId] = $expression['to_show'];
                }
            }
        }

        $widgetsToShow = json_encode($show_list);
        $widgetsToHide = json_encode($hide_list);
        $expressionList = json_encode($expressionList);

        require_once(SITEAPPS_VIEW_DIR . 'widgets/onload.php');
    }

}

?>