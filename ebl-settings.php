<?php
function ebl_plugin_menu() {
	add_options_page( 'Easy Beer Lister Settings', 'Easy Beer Lister Settings', 'manage_options', 'ebl-settings', 'ebl_options' );
}
add_action( 'admin_menu', 'ebl_plugin_menu' );

function ebl_before_options(){
  do_action('ebl_before_options');
}


function ebl_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
            <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>Easy Beer Lister Options</h1>
						<div class="ebl-wrapper">
							<form method="post" action="options.php">
									<?php

											//add_settings_section callback is displayed here. For every new section we need to call settings_fields.
											settings_fields("ebl_settings");
											// all the add_settings_field callbacks is displayed here
											do_settings_sections("ebl-settings");
											// Add the submit button to serialize the options
											submit_button(); 

									?>          
							</form>
                    	<div class="ebl-admin-sidebar">
							<?php do_action('ebl_settings_sidebar');?>
                    </div>
					</div>
        </div>
        <?php
    }

function ebl_settings_register(){
    //section name, display name, callback to print description of section, page to which section is attached.
    add_settings_section("ebl_settings", "<code>[beer]</code> Behavior Options", "ebl_display_header_options_content", "ebl-settings");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("ebl_js_hover", "Disable Beer information on hover for the <code>[beer]</code> shortcode.", "ebl_disable_js_hover", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_hide_ibu", "Hide IBU from <code>[beer]</code> shortcode", "ebl_hide_ibu", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_hide_abv", "Hide ABV from <code>[beer]</code> shortcode", "ebl_hide_abv", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_hide_og", "Hide OG from <code>[beer]</code> shortcode", "ebl_hide_og", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_hide_ontap_msg", "Hide On Tap message from <code>[beer]</code> shortcode", "ebl_hide_ontap_msg", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_js_hover_x", "Hover X offset", "ebl_js_hover_x", "ebl-settings", "ebl_settings");
    add_settings_field("ebl_js_hover_y", "Hover Y offset", "ebl_js_hover_y", "ebl-settings", "ebl_settings");

    //section name, form element name, callback for sanitization
    register_setting("ebl_settings", "ebl_js_hover");
    register_setting("ebl_settings", "ebl_hide_ibu");
    register_setting("ebl_settings", "ebl_hide_abv");
    register_setting("ebl_settings", "ebl_hide_og");
    register_setting("ebl_settings", "ebl_hide_ontap_msg");
    register_setting("ebl_settings", "ebl_js_hover_x");
    register_setting("ebl_settings", "ebl_js_hover_y");

    do_action('ebl_addon_settings_fields');
}
//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "ebl_settings_register");

//------ JS SETTINGS ------//
function ebl_display_header_options_content(){
echo "Adjust how the <code>[beer]</code> shortcode behaves, and displays.  Some themes don't display the hover option too well, but these settings allow you to fix that.";
}
function ebl_disable_js_hover(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="ebl_js_hover" name="ebl_js_hover" value="1" <?php echo checked(1, get_option('ebl_js_hover'), false);  ?>/>
    <?php
}
function ebl_js_hover_x(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="number" id="ebl_js_hover_x" name="ebl_js_hover_x" value="<?php echo get_option('ebl_js_hover_x');?>" />
    <?php
}
function ebl_js_hover_y(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="number" id="ebl_js_hover_y" name="ebl_js_hover_y" value="<?php echo get_option('ebl_js_hover_y');?>" />
    <?php
}

//------ Show/Hide Beer Shortcode Details on Hover ------//
function ebl_hide_ibu(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="ebl_hide_ibu" name="ebl_hide_ibu" value="1" <?php echo checked(1, get_option('ebl_hide_ibu'), false);  ?>/>
    <?php
}
//------ Show/Hide Beer Shortcode Details on Hover ------//
function ebl_hide_abv(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="ebl_hide_abv" name="ebl_hide_abv" value="1" <?php echo checked(1, get_option('ebl_hide_abv'), false);  ?>/>
    <?php
}
//------ Show/Hide Beer Shortcode Details on Hover ------//
function ebl_hide_og(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="ebl_hide_og" name="ebl_hide_og" value="1" <?php echo checked(1, get_option('ebl_hide_og'), false);  ?>/>
    <?php
}
//------ Show/Hide Beer Shortcode Details on Hover ------//
function ebl_hide_ontap_msg(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="ebl_hide_ontap_msg" name="ebl_hide_ontap_msg" value="1" <?php echo checked(1, get_option('ebl_hide_ontap_msg'), false);  ?>/>
    <?php
}
