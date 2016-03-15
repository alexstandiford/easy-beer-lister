<?php
function tasbb_plugin_menu() {
	add_options_page( 'BrewBuddy Settings', 'BrewBuddy Settings', 'manage_options', 'tasbb-settings', 'tasbb_options' );
}
add_action( 'admin_menu', 'tasbb_plugin_menu' );

function tasbb_before_options(){
  do_action('tasbb_before_options');
}


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
											settings_fields("tasbb_settings");
											// all the add_settings_field callbacks is displayed here
											do_settings_sections("tasbb-settings");
											// Add the submit button to serialize the options
											submit_button(); 

									?>          
							</form>
							<?php tasbb_email_sidebar();?>
					</div>
        </div>
        <?php
    }

function tasbb_settings_register(){
    //section name, display name, callback to print description of section, page to which section is attached.
    add_settings_section("tasbb_settings", "<code>[beer]</code> Behavior Options", "tasbb_display_header_options_content", "tasbb-settings");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("tasbb_js_hover", "Disable Beer information on hover for the <code>[beer]</code> shortcode.", "tasbb_disable_js_hover", "tasbb-settings", "tasbb_settings");
    add_settings_field("tasbb_js_hover_x", "Hover X offset", "tasbb_js_hover_x", "tasbb-settings", "tasbb_settings");
    add_settings_field("tasbb_js_hover_y", "Hover Y offset", "tasbb_js_hover_y", "tasbb-settings", "tasbb_settings");

    //section name, form element name, callback for sanitization
    register_setting("tasbb_settings", "tasbb_js_hover");
    register_setting("tasbb_settings", "tasbb_js_hover_x");
    register_setting("tasbb_settings", "tasbb_js_hover_y");

    do_action('tasbb_addon_settings_fields');
}
//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "tasbb_settings_register");

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