<style>
    .question{ background: #fff; border: 1px solid #999; color: #999; font-size: 10px; font-weight: bold; text-decoration: none; border-radius: 50%; padding: 2px 5px; }
</style>

<div class="wpbtb-wc">
    <hr />
    <div class="wpbtb-wc-header">
        <div id="siteapps-icon" style="background: url(/wp-content/plugins/siteapps/images/logositeappswp.gif) no-repeat; width: 180px; height: 38px;" ><br /></div>
    </div>
    <div class="wpbtb-wc-body">
        <strong><?php _e('Segmentation') ?>:</strong>
        <input type="checkbox" name="<?php print 'siteapps_widget_config[widget_config]['.$wid.'][widget_mode]';?>" class="wpbtb_widget_mode" value="start_hidden" <?php print ($isEnabled)?"checked=checked":""?>> Enable
        <?php 
        if ($isEnabled): 
            $start_hidden_class = 'wpbtb-show'; 
        else: 
            $start_hidden_class = 'wpbtb-hide'; 
        endif; 
        ?>
        <br />
        <br />
        <div class="siteapps-warning start-hidden <?php echo $start_hidden_class ?>">
            <strong><?php _e('Select which segments this widget will be shown to:', SITEAPPS_PLUGIN_NAME)?></strong></p>
            <table class="wpbtb-wc-table">
                <?php foreach ($segments as $friendlyName => $config): ?>
                    <tr class="<?php print ($widgetConfig['to_show'][$friendlyName])?'siteapps-orange':""?>">
                        <td title="<?php print $friendlyName; ?>">
                        <?php 
                        if (strlen($config['name']) > 34) {
                            echo substr($config['name'], 0, 34);
                        } else {
                            echo $config['name'];
                        }
                        ?></td>
                        <td>
                            <input type="checkbox" class="siteapps-segment" name="siteapps_widget_config[widget_config][<?php print $wid;?>][to_show][<?php print $friendlyName?>]" <?php print ($widgetConfig['to_show'][$friendlyName])?'checked="checked"':""?>>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br />
            <b>Advanced expression:</b><br />
            <input type="text" class="siteapps-segment" name="siteapps_widget_config[widget_expression][<?php print $wid;?>][to_show]" value="<?php print $widgetExpression['to_show']; ?>">
            <a href="#" class="question" title="Here you can use your segments to create logical expressions by using their friendly names, for example: (segementa OR segmentb) AND segmentc">?</a>
        </div>
        
        <br />
        <?php 
        if($isHide): ?>
            <div class="siteapps-warning">
                <div class="siteapps-warning-title">Deprecated settings for previous version</div>
                <strong><?php _e('This widget is hidden for following segments:', SITEAPPS_PLUGIN_NAME)?></strong> 
                <ul>
                    <?php foreach ($segments as $friendlyName => $config): 
                        if($widgetConfig['to_hide'][$friendlyName]) {
                            print "<li>" . $config['name'] . "</li>";
                        }

                     endforeach; ?>
                </ul>
                <p class="siteapps-footer-warning"><strong>* If you check Segmentation option above, this deprecated settings will be overwritten.</strong></p>
            </div>
        <?php endif;?>
    </div>
</div>
