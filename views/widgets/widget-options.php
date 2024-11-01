<div class="wpbtb-wc">
    <div class="wpbtb-wc-header">
        <h3><?php _e('SiteApps Options', SITEAPPS_PLUGIN_NAME) ?></h3>
    </div>
    <div class="wpbtb-wc-body">
        <strong><?php _e('Widget Mode') ?>:</strong>
        <select name="<?php print 'siteapps_widget_config[widget_config]['.$wid.'][widget_mode]';?>" class="wpbtb_widget_mode">
            <?php
            foreach ($widgetModes as $widgetsModeId => $widgetsModeLabel):
                $selected = ($valueMode == $widgetsModeId)?'selected="selected"':'';
            ?>
                <option value="<?php print $widgetsModeId ?>" <?php print $selected?>><?php print $widgetsModeLabel ?></option>
            <?php
            endforeach;
            ?>
        </select>   
        <?php 
        if ($widgetConfig['widget_mode'] == 'start_hidden'): 
            $start_hidden_class = 'wpbtb-show'; 
            $start_visible_class = 'wpbtb-hide'; 
        else: 
            $start_hidden_class = 'wpbtb-hide'; 
            $start_visible_class = 'wpbtb-show'; 
        endif; 
        ?>
        <div class="start-hidden <?php echo $start_hidden_class ?>">
            <table class="wpbtb-wc-table">
                <tr>
                    <th align="left"><?php _e('Segment', SITEAPPS_PLUGIN_NAME)?></th>
                    <th><?php _e('Show', SITEAPPS_PLUGIN_NAME)?></th>
                </tr>
                <?php foreach ($segments as $friendlyName => $config): ?>
                    <tr>
                        <td><?php echo $config['name'] ?></td>
                        <td>
                            <input type="checkbox" name="siteapps_widget_config[widget_config][<?php print $wid;?>][to_show][<?php print $friendlyName?>]" <?php print ($widgetConfig['to_show'][$friendlyName])?'checked="checked"':""?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="start-visible <?php echo $start_visible_class ?>">
            <table class="wpbtb-wc-table">
                <tr>
                    <th><?php _e('Segment', SITEAPPS_PLUGIN_NAME)?></th>
                    <th><?php _e('Hide', SITEAPPS_PLUGIN_NAME)?></th>
                </tr>
                <?php foreach ($segments as $friendlyName => $config): ?>
                    <tr>
                        <td><?php echo $config['name'] ?></td>
                        <td>
                            <input type="checkbox" name="siteapps_widget_config[widget_config][<?php print $wid;?>][to_hide][<?php print $friendlyName?>]" <?php print ($widgetConfig['to_hide'][$friendlyName])?'checked="checked"':""?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>