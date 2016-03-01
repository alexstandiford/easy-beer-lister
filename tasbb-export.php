<?php
function tasbb_export_menu() {
	add_submenu_page( 'edit.php?post_type=beers', 'Export', 'Export Menu', 'manage_options', 'tasbb-export', 'tasbb_export' );
}
add_action( 'admin_menu', 'tasbb_export_menu' );

function tasbb_export() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
            <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>Export/Print Beer Menu</h1>
            <form method="post" action="options.php">
							<a class="button button-primary" href="<?php echo plugin_dir_url(__FILE__);?>menu-export.php">View/Print Menu</a>
                <?php
                
                    //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
                    settings_fields("tasbb_export_settings");
                    
                    // all the add_settings_field callbacks is displayed here
                    do_settings_sections("tasbb-export");
                
                    // Add the submit button to serialize the options
                    submit_button();
                    
                ?>
            </form>
        </div>
        <?php
    }

function tasbb_export_options(){
    //section name, display name, callback to print description of section, page to which section is attached.
    add_settings_section("tasbb_export_settings", "Export Options", "tasbb_export_options_content", "tasbb-export");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("tasbb_export_show_img", "Show Images on Menu", "tasbb_export_show_img", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_description", "Show Description on Menu", "tasbb_export_show_description", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_style", "Show Style on Menu", "tasbb_export_show_style", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_ibu", "Show IBU on Menu", "tasbb_export_show_ibu", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_abv", "Show ABV on Menu", "tasbb_export_show_abv", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_og", "Show OG on Menu", "tasbb_export_show_og", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_show_price", "Show Price on Menu", "tasbb_export_show_price", "tasbb-export", "tasbb_export_settings");
    add_settings_field("export_ontap", "Only Show What's On Tap", "tasbb_export_ontap", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_menu_css", "Custom CSS Overrides", "tasbb_export_menu_css", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_pairings", "Pairings", "tasbb_export_pairings", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_styles", "Styles", "tasbb_export_styles", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_tags", "Tags", "tasbb_export_tags", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_availability", "Availability", "tasbb_export_availability", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_menu_heading", "Menu Heading", "tasbb_export_menu_heading", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_menu_subheading", "Menu Subheading", "tasbb_export_menu_subheading", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_menu_before_menu", "Before Menu Text", "tasbb_export_menu_before_menu", "tasbb-export", "tasbb_export_settings");
    add_settings_field("tasbb_export_menu_after_menu", "After Menu Text", "tasbb_export_menu_after_menu", "tasbb-export", "tasbb_export_settings");

    //section name, form element name, callback for sanitization
    register_setting("tasbb_export_settings", "tasbb_export_ontap");
    register_setting("tasbb_export_settings", "tasbb_export_show_img");
    register_setting("tasbb_export_settings", "tasbb_export_show_description");
    register_setting("tasbb_export_settings", "tasbb_export_show_style");
    register_setting("tasbb_export_settings", "tasbb_export_show_ibu");
    register_setting("tasbb_export_settings", "tasbb_export_show_abv");
    register_setting("tasbb_export_settings", "tasbb_export_show_og");
    register_setting("tasbb_export_settings", "tasbb_export_show_price");
    register_setting("tasbb_export_settings", "tasbb_export_menu_css");
    register_setting("tasbb_export_settings", "tasbb_export_pairings");
    register_setting("tasbb_export_settings", "tasbb_export_styles");
    register_setting("tasbb_export_settings", "tasbb_export_tags");
    register_setting("tasbb_export_settings", "tasbb_export_availability");
    register_setting("tasbb_export_settings", "tasbb_export_menu_before_menu");
    register_setting("tasbb_export_settings", "tasbb_export_menu_heading");
    register_setting("tasbb_export_settings", "tasbb_export_menu_subheading");
    register_setting("tasbb_export_settings", "tasbb_export_menu_after_menu");

}

function tasbb_export_ontap(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_ontap" name="tasbb_export_ontap" value="1" <?php echo checked(1, get_option('tasbb_export_ontap'), false);  ?>/>
    <?php
}

function tasbb_export_show_img(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_img" name="tasbb_export_show_img" value="1" <?php echo checked(1, get_option('tasbb_export_show_img'), false);  ?>/>
    <?php
}

function tasbb_export_show_description(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_description" name="tasbb_export_show_description" value="1" <?php echo checked(1, get_option('tasbb_export_show_description'), false);  ?>/>
    <?php
}

function tasbb_export_show_style(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_style" name="tasbb_export_show_style" value="1" <?php echo checked(1, get_option('tasbb_export_show_style'), false);  ?>/>
    <?php
}

function tasbb_export_show_og(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_og" name="tasbb_export_show_og" value="1" <?php echo checked(1, get_option('tasbb_export_show_og'), false);  ?>/>
    <?php
}

function tasbb_export_show_ibu(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_ibu" name="tasbb_export_show_ibu" value="1" <?php echo checked(1, get_option('tasbb_export_show_ibu'), false);  ?>/>
    <?php
}

function tasbb_export_show_abv(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_abv" name="tasbb_export_show_abv" value="1" <?php echo checked(1, get_option('tasbb_export_show_abv'), false);  ?>/>
    <?php
}

function tasbb_export_show_price(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="checkbox" id="tasbb_export_show_price" name="tasbb_export_show_price" value="1" <?php echo checked(1, get_option('tasbb_export_show_price'), false);  ?>/>
    <?php
}

function tasbb_export_menu_css(){
    //id and name of form element should be same as the setting name.
    ?>
<textarea id="tasbb_export_menu_css" name="tasbb_export_menu_css"><?php echo get_option('tasbb_export_menu_css');?></textarea>
    <?php
}

function tasbb_export_menu_before_menu(){
    //id and name of form element should be same as the setting name.
    ?>
<textarea id="tasbb_export_menu_before_menu" name="tasbb_export_menu_before_menu"><?php echo get_option('tasbb_export_menu_before_menu');?></textarea>
    <?php
}

function tasbb_export_menu_after_menu(){
    //id and name of form element should be same as the setting name.
    ?>
<textarea id="tasbb_export_menu_after_menu" name="tasbb_export_menu_after_menu"><?php echo get_option('tasbb_export_menu_after_menu');?></textarea>
    <?php
}

function tasbb_export_menu_heading(){
    //id and name of form element should be same as the setting name.
    ?>
<textarea id="tasbb_export_menu_heading" name="tasbb_export_menu_heading"><?php echo get_option('tasbb_export_menu_heading');?></textarea>
    <?php
}

function tasbb_export_menu_subheading(){
    //id and name of form element should be same as the setting name.
    ?>
<textarea id="tasbb_export_menu_subheading" name="tasbb_export_menu_subheading"><?php echo get_option('tasbb_export_menu_subheading');?></textarea>
    <?php
}

function tasbb_export_pairings(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" id="tasbb_export_pairings" name="tasbb_export_pairings" value="<?php echo get_option('tasbb_export_pairings');?>" />
    <?php
}

function tasbb_export_styles(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" id="tasbb_export_styles" name="tasbb_export_styles" value="<?php echo get_option('tasbb_export_styles');?>" />
    <?php
}

function tasbb_export_tags(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" id="tasbb_export_tags" name="tasbb_export_tags" value="<?php echo get_option('tasbb_export_tags');?>" />
    <?php
}

function tasbb_export_availability(){
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" id="tasbb_export_availability" name="tasbb_export_availability" value="<?php echo get_option('tasbb_export_availability');?>" />
    <?php
}



function tasbb_export_options_content(){
	echo "Adjust the settings for the print-friendly beer menu here.";
}

//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "tasbb_export_options");
?>