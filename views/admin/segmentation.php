        <div id="post-body">
            <div id="post-body-content">
                <form action="" method="POST" id="siteapps-conf">
                    <?php wp_nonce_field(SITEAPPS_PLUGIN_NAME) ?>
                    <div id="insertTag" class="stuffbox">
                        <h3 class="hndle"><span>General Settings</span></h3>
                        <div class="inside">
                            <table>
                                    <tr>
                                        <th valign="top" scrope="row" align="left">
                                            <label for="sa_id">SiteApps ID:</label><br>
                                        </th>
                                        <td>
                                            <input type="text" value="<?php print  $saId;?>" size="10" name="sa_id" id="sa_id"> <i>(You can see your SiteApps ID in your <a href="https://siteapps.com/apps/purchased" target="_blank">dashboard</a>)</i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th valign="top" scrope="row" align="left">
                                            <label for="sa_id">User Key:</label><br>
                                        </th>
                                        <td>
                                            <input type="text" value="<?php print  $userKey;?>" size="10" name="sa_user_key" id="sa_user_key"> <i>(You can see your User Key in your <a href="https://siteapps.com/apps/purchased" target="_blank">dashboard</a>)</i>
                                        </td>
                                    </tr>
                                    <?php 
                                    if (count($segments) > 0): 
                                    ?>
                                    <tr>
                                        <th valign="top" align="left" scrope="row">
                                            <label for="segments"><?php _e('Available Segments:', SITEAPPS_PLUGIN_NAME); ?></label><br>
                                        </th>
                                        <td valign="top">
                                            <ol>
                                                <?php foreach ($segments as $segmentFriendlyName => $config): ?>
                                                    <li><?php print $config['name'] ?></li>
                                                <?php endforeach; ?>
                                            </ol>
                                            <i><?php _e('Don\'t see your segment on this list? Try checking your SiteApps ID and then click the \'Refresh Data\' button in advanced options below.') ?></i><br />
                                            <i><a href='https://www.siteapps.com/general/login'><?php _e('Create New SiteApps Segments') ?></a></i>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                            </table>
                            <div id="advancedOptions" class="advanced-siteapps">
                                <table>
                                    <tr>
                                        <th valign="top" scrope="row" align="left">
                                            <label for="sa_id">Tag Type:</label><br>
                                        </th>
                                        <td valign="top">
                                            <input type="radio" value="2" name="sa_tag_type" <?php print  $asyncCheck;?>>Asynchronous (Default)
                                            <input type="radio" value="1" name="sa_tag_type" <?php print  $syncCheck;?>>Synchronous
                                        </td>
                                    </tr>
                                    <tr>
                                        <th valign="top" scrope="row" align="left">
                                            <label for="enable_segments">Enable Segments on Widgets:</label><br>
                                        </th>
                                        <td valign="top">
                                            <input type="checkbox" name="sa_enable_smart_widgets" <?php print $smartWidgetCheck;?>> <i>(You can show or hide your widgets  in <a href="widgets.php">wordpress widget page</a> for your SiteApps User Segments)</i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" scrope="row">
                                            <label for="debug_mode">Debug Mode:</label><br>
                                        </th>
                                        <td >
                                            <input type="checkbox" name="sa_debug" <?php print $debugCheck;?>> <i>(If you enable this option, you will see some important data in your Web Console. You can use this to test your widget settings)</i>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="submit">
                                <input type="submit" class="button-primary" name="siteapps_save" value="Save Changes" style="width:100px;" />
                                <input type="submit" class="button-primary" name="siteapps_refresh_data" value="Refresh Data" />
                                <input type="button" class="button-primary" id="siteapps_show_advanced" name="siteapps_show_advanced" value="Show Advanced Options" />
                                <input type="submit" class="button-primary advanced-siteapps" name="siteapps_reset" value="Reset Options" style="display: none" />
                            </div>
                        </div>
                    </div>
                </form>
                
           <div class="stuffbox">
                <h3 class="hndle"><span>SiteApps Tips</span></h3>
                <div class="inside">
                    <h4>Improve your segmentation!</h4>
                    <p>Are you from Brazil ? You can use SiteApps + Navegg Analytics and improve your segmentation with demographic data. See more information <a href='http://blog.siteapps.com/navegg-siteapps-inovacao-brasileira/'>here</a>.</p>
                </div>
            </div>
            </div>
            

            
        </div>