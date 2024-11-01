<?php

class SiteAppsDivManipulator extends WP_Widget 
{
    function __construct() 
    {
        $widget_ops = array('description' => 'Creates a div for SiteApps manipulation' );
        parent::__construct('sa_divs', 'SiteApps Div Marker', $widget_ops);
    }

    function widget($args, $instance) 
    {
        extract($args);
        echo $before_widget;
        echo $before_title . $after_title;
        $div_name = apply_filters('widget_div_name', empty($instance['div_name']) ? '&nbsp;' : $instance['div_name'], $instance, $this->id_base);
        echo '<div id="'.$div_name.'"></div>';
        echo $after_widget;
    }

    function update($new_instance, $old_instance) 
    {
        $instance               = $old_instance;
        $instance['div_name']   = strip_tags($new_instance['div_name']);
        return $instance;
    }

    function form($instance) 
    {
        $instance = wp_parse_args( (array) $instance, array( 'div_name' => '' ) );
        $div_name = strip_tags($instance['div_name']);
        include SITEAPPS_VIEW_DIR . "widgets/divManipulator.php";
    }
}

?>