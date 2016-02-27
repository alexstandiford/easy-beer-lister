<?php
function my_plugin_menu() {
	add_options_page( 'BrewBuddy Settings', 'Settings', 'manage_options', 'tasbb-settings', 'tasbb_options' );
}
add_action( 'admin_menu', 'my_plugin_menu' );



function tasbb_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
            <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>BrewBuddy Options</h1>
            <form method="post" action="options.php">
                <?php
                
                    //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
                    settings_fields("header_section");
                    
                    // all the add_settings_field callbacks is displayed here
                    do_settings_sections("theme-options");
                
                    // Add the submit button to serialize the options
                    submit_button(); 
                    
                ?>          
            </form>
        </div>
        <?php
    }

function tasbb_display_options(){
    //section name, display name, callback to print description of section, page to which section is attached.
    add_settings_section("header_section", "Header Options", "tasbb_display_header_options_content", "theme-options");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("header_logo", "Logo Url", "tasbb_display_logo_form_element", "theme-options", "header_section");
    add_settings_field("advertising_code", "Ads Code", "tasbb_display_ads_form_element", "theme-options", "header_section");

    //section name, form element name, callback for sanitization
    register_setting("header_section", "header_logo");
    register_setting("header_section", "advertising_code");
}

function tasbb_display_header_options_content(){
echo "The header of the theme";}
function tasbb_display_logo_form_element(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" name="header_logo" id="header_logo" value="<?php echo get_option('header_logo'); ?>" />
    <?php
}
function tasbb_display_ads_form_element()
{
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" name="advertising_code" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
    <?php
}

//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "tasbb_display_options");
?>