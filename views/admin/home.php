
<style>
.sa_box1{ width: 523px; background: #ECECEC; border-radius: 12px; margin: auto; border: 6px solid #E4E4E4; color: #666; font-size: 14px; padding-top: 16px }
.sa_box1_content{ margin: 34px 30px 0px; }

.sa_box1 .salogo{ display: block; float: left; width: 148px; height: 25px; background: url(/wp-content/plugins/siteapps/images/logo_sa_mini.png) center center no-repeat; padding: 6px 20px; margin: 10px 0px 0px 82px; border-right: 1px solid #DBDBDB; }
.sa_box1 .header{ display: block; float: left; font-size: 22px; color: #666; text-shadow: -1px -1px 0px #fff; position: relative; left: 20px; top: 22px; }

.sa_box1 .inputrow{ margin-bottom: 6px; font-weight: bold; }
.sa_box1 .inputrow span{ display: inline-block; text-align: right; width: 100px; }
.sa_box1 .inputrow .config{ display: inline-block; text-align: right; width: 170px; }
.sa_box1 .inputrow input[type=text]{ padding: 6px; color: 333; width: 320px; box-shadow: inset 1px 1px 4px #ddd }
.sa_box1 .irsettings input[type=text]{ width: 260px; }
.sa_box1 .inputrow input[type=text]:focus{ outline: 0px;  }

.sa_box1 .footer{ border: 1px solid #E4E4E4; margin: 60px 0px 0px 0px; padding: 40px 20px 20px 20px; text-align: center; font-weight: bold }
.sa_box1 .footer a{ color: #F05913; text-decoration: none; }
.sa_box1 .footer p{ font-size: 10px; }
.sa_box1 .footer_grad{ background: rgb(247,247,247); /* Old browsers */background: -moz-linear-gradient(top, rgba(247,247,247,1) 0%, rgba(221,221,221,1) 100%); /* FF3.6+ */background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(247,247,247,1)), color-stop(100%,rgba(221,221,221,1))); /* Chrome,Safari4+ */background: -webkit-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(221,221,221,1) 100%); /* Chrome10+,Safari5.1+ */background: -o-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(221,221,221,1) 100%); /* Opera 11.10+ */background: -ms-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(221,221,221,1) 100%); /* IE10+ */background: linear-gradient(to bottom, rgba(247,247,247,1) 0%,rgba(221,221,221,1) 100%); /* W3C */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f7f7', endColorstr='#dddddd',GradientType=0 ); /* IE6-9 */ }

.btn_action1{ font-family: 'Open Sans', 'Arial'; color: #fff !important; font-weight: bold; font-size: 16px; position: relative; top: -66px; text-align: center; margin: auto; border-radius: 5px; border: 1px solid #f47318; border-bottom: 2px solid #c85011; padding: 18px 49px; text-shadow: 1px 1px 1px rgb(173, 86, 27); text-decoration: none; background: #f7941f; /* Old browsers */background: -moz-linear-gradient(top, #f7941f 0%, #f16014 100%); /* FF3.6+ */background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7941f), color-stop(100%,#f16014)); /* Chrome,Safari4+ */background: -webkit-linear-gradient(top, #f7941f 0%,#f16014 100%); /* Chrome10+,Safari5.1+ */background: -o-linear-gradient(top, #f7941f 0%,#f16014 100%); /* Opera 11.10+ */background: -ms-linear-gradient(top, #f7941f 0%,#f16014 100%); /* IE10+ */background: linear-gradient(to bottom, #f7941f 0%,#f16014 100%); /* W3C */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7941f', endColorstr='#f16014',GradientType=0 ); /* IE6-9 */;  }
.btn_action1:hover{ text-decoration: none; color: #fff; background: #f7a81f; /* Old browsers */background: -moz-linear-gradient(top, #f7a81f 0%, #ef6411 100%); /* FF3.6+ */background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7a81f), color-stop(100%,#ef6411)); /* Chrome,Safari4+ */background: -webkit-linear-gradient(top, #f7a81f 0%,#ef6411 100%); /* Chrome10+,Safari5.1+ */background: -o-linear-gradient(top, #f7a81f 0%,#ef6411 100%); /* Opera 11.10+ */background: -ms-linear-gradient(top, #f7a81f 0%,#ef6411 100%); /* IE10+ */background: linear-gradient(to bottom, #f7a81f 0%,#ef6411 100%); /* W3C */filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7a81f', endColorstr='#ef6411',GradientType=0 ); /* IE6-9 */ }

.question{ background: #fff; border: 1px solid #999; color: #999; font-size: 10px; font-weight: bold; text-decoration: none; border-radius: 50%; padding: 2px 5px; }

.go {position: relative; top: 4px;}
.optional { width: auto !important; font-weight: normal; }
</style>
<br />
<form action="" method="POST" id="siteapps-conf">
    <?php wp_nonce_field(SITEAPPS_PLUGIN_NAME) ?>
    <input type="hidden" name="agree" value="yes" />
    
    <div class="sa_box1" id="siteapps_site_signup">
        <div class="salogo"></div>
        <div class="header">Create Account</div>
        <div style="clear: both"></div>
       <div class="sa_box1_content">
            <div class="inputrow">
                <span>Name: </span>
                <input type="text" value="<?php print $name; ?>" name="siteapps_signup_name" id="siteapps_signup_name" placeholder="Your Name" />
            </div>
            <div class="inputrow">
                <span>User Email: </span>
                <input type="text" value="<?php print $email; ?>" name="siteapps_signup_email" id="siteapps_signup_email" placeholder="you@server.com" />
            </div>
            <div class="inputrow">
                <span>Site URL: </span>
                <input type="text" value="<?php print $siteUrl; ?>" name="siteapps_signup_site_url" id="siteapps_signup_site_url" placeholder="www.siteapps.com" />
            </div>
       </div>

       <div class="footer footer_grad">
            <input type="submit" class="btn_action1"  name="siteapps_create_account" id="siteapps_create_account" value="CREATE MY ACCOUNT" style="cursor: pointer;"/>
            <p style="margin-top: -50px;">By clicking 'Create my account', you agree with <a href="http://siteapps.com/site/terms/1" target="_blank">Terms of Use</a> and <a href="http://siteapps.com/site/terms/2" target="_blank">End User License Agreement</a>.</p>
            <p style="padding-top: 10px; font-size: 20px;">Already have a SiteApps Account?</p>
            <p style="color: #888; font-size: 14px; position: relative; top: -10px;">Then <a href="#"  id="siteapps_configure">click here</a> to configure your site.</p>
       </div>
    </div>


    <div class="sa_box1" id="siteapps_site_config" style="display: none; width: 600px;">
        <div class="salogo"></div>
        <div class="header">Configure Your Account</div>
        <div style="clear: both"></div>
       <div class="sa_box1_content">
            <div class="inputrow irsettings">
                <span class="config">SiteApps ID: </span>
                <input type="text" value="<?php print $saId; ?>" size="10" name="sa_id" id="sa_id"> 
                <a href="https://siteapps.com/Dashboard?utm_source=wordpress&utm_medium=plugin&utm_campaign=settings_info&utm_content=" class="question" target="_blank" title="Please enter the SiteApps ID for this website.  This information can be found on your SiteApps dashboard.  Simply click your mouse now to open a new window to access this information.">?</a>
            </div>
            <div class="inputrow irsettings">
                <span class="config">SiteApps Account Email: </span>
                <input type="text" value="<?php print $emailConfig; ?>" size="30" name="sa_email" id="sa_email">
                <a href="#" class="question" title="Please input the email address used with SiteApps for this site.">?</a>
                <span class="optional">(optional)</span>
            </div>
            <div class="inputrow irsettings">
                <span class="config">User Key: </span>
                <input type="text" value="<?php print $userKey; ?>" size="30" name="sa_user_key" id="sa_user_key" >
                <a href="https://siteapps.com/users/edit?utm_source=wordpress&utm_medium=plugin&utm_campaign=settings_info&utm_content=" class="question" target="_blank" title="Please enter your API user key.  This information can be found in the Preferences link for your user account.  Simply click your mouse now to open a new window to access this information.">?</a>
                <span class="optional">(optional)</span>
            </div>
       </div>

       <div class="footer footer_grad">
           <input type="submit" class="btn_action1" name="siteapps_save" value="SAVE CHANGES" style="cursor: pointer;" /> 
           <p style="font-size: 20px;">Still don't have a SiteApps account for this website?</p>
           <p style="color: #888; font-size: 14px; position: relative; top: -10px;">Then <a href="#"  id="siteapps_signup">click here</a> to sign up.</p>
       </div>
    </div>
</form>        