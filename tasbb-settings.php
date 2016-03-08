<?php
function tasbb_plugin_menu() {
	add_options_page( 'BrewBuddy Settings', 'BrewBuddy Settings', 'manage_options', 'tasbb-settings', 'tasbb_options' );
}
add_action( 'admin_menu', 'tasbb_plugin_menu' );



function tasbb_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
            <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>BrewBuddy Options</h1>
						<div class="tasbb-wrapper">
							<form method="post" action="options.php">
									<?php

											//add_settings_section callback is displayed here. For every new section we need to call settings_fields.
											settings_fields("js_hover_settings");

											// all the add_settings_field callbacks is displayed here
											do_settings_sections("tasbb-settings");

											// Add the submit button to serialize the options
											submit_button(); 

									?>          
							</form>
	<div class="tasbb-admin-sidebar">
		<div class="tasbb-sidebar-item">
			<h2>BrewBuddy was proudly made by Alex Standiford</h2>
			<p>I am here to help breweries manage their online presence faster. I do that by providing breweries with tools, tips, and tricks that make their lives easier.</p>
			<p>If you ever have <em>any</em> questions about WordPress, or need customizations to your website don't hesitate to send me a message.  I'll be happy to help you out in any way I can.</p>
			<ul>
				<li>Email: <a href="mailto:a@alexstandiford.com">a@alexstandiford.com</a></li>
				<li><a href="http://www.twitter.com/alexstandiford" target="blank">Follow me on Twitter</a></li>
				<li><a href="http://www.alexstandiford.com" target="blank">Visit my website</a></li>
			</ul>
		</div>
		<div class="signup-form">	
		<div id="mc_embed_signup">
		<form action="//alexstandiford.us2.list-manage.com/subscribe/post?u=f39d9629a4dd9dd976f09f6e5&amp;id=b6a3d349e7" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
			<h2>Spend Less Time Updating Your Website</h2>
			<h3>Fill out the form below, and I'll send you</h3>
					<ul>
						<li>A list of my 3 must-have free plugins for brewers and bars</li>
						<li>Learn about the free tool that I use to spend less time managing social media</li>
						<li>A complete workflow of how to quickly promote events on Facebook, Instagram, and Twitter</li>
						<li>PDF to-do checklists that walk you through the process quickly</li>
						<li>Ongoing WordPress tips and tricks for breweries</li>
					</ul>
		<div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
		<div class="mc-field-group">
			<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
		</label>
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>
		<div class="mc-field-group">
			<label for="mce-FNAME">First Name </label>
			<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
		</div>
		<div class="mc-field-group">
			<label for="mce-LNAME">Last Name </label>
			<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
		</div>
			<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none"></div>
				<div class="response" id="mce-success-response" style="display:none"></div>
			</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f39d9629a4dd9dd976f09f6e5_b6a3d349e7" tabindex="-1" value=""></div>
				<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
		</form>
		</div>
		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
		<!--End mc_embed_signup-->
		</div>
							</div>
					</div>
        </div>
        <?php
    }

function tasbb_display_options(){
    //section name, display name, callback to print description of section, page to which section is attached.
    add_settings_section("js_hover_settings", "<code>[beer]</code> Behavior Options", "tasbb_display_header_options_content", "tasbb-settings");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("tasbb_js_hover", "Disable Beer information on hover for the <code>[beer]</code> shortcode.", "tasbb_disable_js_hover", "tasbb-settings", "js_hover_settings");
    add_settings_field("tasbb_js_hover_x", "Hover X offset", "tasbb_js_hover_x", "tasbb-settings", "js_hover_settings");
    add_settings_field("tasbb_js_hover_y", "Hover Y offset", "tasbb_js_hover_y", "tasbb-settings", "js_hover_settings");

    //section name, form element name, callback for sanitization
    register_setting("js_hover_settings", "tasbb_js_hover");
    register_setting("js_hover_settings", "tasbb_js_hover_x");
    register_setting("js_hover_settings", "tasbb_js_hover_y");
}

//------ JS SETTINGS ------//
function tasbb_display_header_options_content(){
echo "Adjust how the <code>[beer]</code> shortcode behaves, and displays.  Some themes don't display the hover option too well, but these settings allow you to fix that.";
}
function tasbb_disable_js_hover(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_js_hover" name="tasbb_js_hover" value="1" <?php echo checked(1, get_option('tasbb_js_hover'), false);  ?>/>
    <?php
}
function tasbb_js_hover_x(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="number" id="tasbb_js_hover_x" name="tasbb_js_hover_x" value="<?php echo get_option('tasbb_js_hover_x');?>" />
    <?php
}
function tasbb_js_hover_y(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="number" id="tasbb_js_hover_y" name="tasbb_js_hover_y" value="<?php echo get_option('tasbb_js_hover_y');?>" />
    <?php
}

//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "tasbb_display_options");
?>