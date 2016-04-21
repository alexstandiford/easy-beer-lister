<?php
if ( ! defined( 'ABSPATH' ) ) exit;
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
function ebl_options_tabs(){
  if(isset($_GET['tab'])){
       $active_tab = $_GET['tab'];
  }?>
  <h2 class="nav-tab-wrapper">
      <a href="?page=ebl-settings&tab=ebl_options" class="nav-tab <?php echo $active_tab == 'ebl_options' || $active_tab == null ? 'nav-tab-active' : ''; ?>">Beer Page Options</a>
      <a href="?page=ebl-settings&tab=ebl_shortcode_options" class="nav-tab <?php echo $active_tab == 'ebl_shortcode_options' ? 'nav-tab-active' : ''; ?>">Shortcode Options</a>
      <a href="?page=ebl-settings&tab=ebl_menu_options" class="nav-tab <?php echo $active_tab == 'ebl_menu_options' ? 'nav-tab-active' : '';?>">Beer Menu Options</a>
      <?php do_action('ebl_add_options_tab',$active_tab); ?>
  </h2>
<?php return $active_tab;
 }?>
<div class="wrap">
  <?php $active_tab = ebl_options_tabs(); ?>
  <div id="icon-options-general" class="icon32"></div>
  <h1>Easy Beer Lister Options</h1>
    <div class="ebl-wrapper">
      <form method="post" action="options.php">
      <?php
      if($active_tab == 'ebl_options' || $active_tab == null){
        settings_fields("ebl_beer_page_options");
        do_settings_sections("ebl-beer-page-options");
      }
      elseif( $active_tab == 'ebl_shortcode_options') {
        settings_fields("ebl_settings");
        do_settings_sections("ebl-settings");
      }
      elseif($active_tab == 'ebl_menu_options'){
        settings_fields('ebl_menu_options');
        do_settings_sections('ebl-menu-options');
      }
      else{
        do_action('ebl_add_settings_register',$active_tab);
      }
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

function ebl_menu_settings_register(){
  add_settings_section("ebl_menu_options","Beer Menu Options","ebl_menu_settings_header","ebl-menu-options");
  add_settings_field("ebl_default_menu_image","Default image for menus (usually your logo)","ebl_default_menu_image","ebl-menu-options","ebl_menu_options");
  register_setting("ebl_menu_options", "ebl_default_menu_image");
}
add_action("admin_init","ebl_menu_settings_register");

function ebl_beer_page_settings_register(){
  add_settings_section("ebl_beer_page_options","Default Beer Page Options","ebl_beer_page_settings_header","ebl-beer-page-options");
  add_settings_field("ebl_beer_page_wrapper_class","Beer heading wrapper class","ebl_beer_page_wrapper_class","ebl-beer-page-options","ebl_beer_page_options");
  add_settings_field("ebl_beer_page_heading_wrapper_class","Beer heading wrapper class","ebl_beer_page_heading_wrapper_class","ebl-beer-page-options","ebl_beer_page_options");
  add_settings_field("ebl_beer_page_content_wrapper","Beer content wrapper class","ebl_beer_page_content_wrapper","ebl-beer-page-options","ebl_beer_page_options");
  add_settings_field("ebl_beer_page_sidebar_wrapper","Beer sidebar wrapper class","ebl_beer_page_sidebar_wrapper","ebl-beer-page-options","ebl_beer_page_options");
  add_settings_field("ebl_beer_page_sidebar","Sidebar to use on beer page","ebl_beer_page_sidebar","ebl-beer-page-options","ebl_beer_page_options");
  
  register_setting("ebl_beer_page_options","ebl_beer_page_wrapper_class");
  register_setting("ebl_beer_page_options","ebl_beer_page_heading_wrapper_class");
  register_setting("ebl_beer_page_options","ebl_beer_page_content_wrapper");
  register_setting("ebl_beer_page_options","ebl_beer_page_sidebar_wrapper");
  register_setting("ebl_beer_page_options","ebl_beer_page_sidebar");
}
add_action("admin_init","ebl_beer_page_settings_register");

function ebl_beer_page_heading_wrapper_class(){?>
  <label for="ebl_beer_page_heading_wrapper_class">Leave blank to use default class</label><br>
  <input type="text" id="ebl_beer_page_heading_wrapper_class" name="ebl_beer_page_heading_wrapper_class" value="<?php echo get_option('ebl_beer_page_heading_wrapper_class'); ?>">
<?php }

function ebl_beer_page_wrapper_class(){?>
  <label for="ebl_beer_page_heading_wrapper_class">Leave blank to use default class</label><br>
  <input type="text" id="ebl_beer_page_wrapper_class" name="ebl_beer_page_wrapper_class" value="<?php echo get_option('ebl_beer_page_wrapper_class'); ?>">
<?php }

function ebl_beer_page_content_wrapper(){?>
  <label for="ebl_beer_page_content_wrapper">Leave blank to use default class</label><br>
  <input type="text" id="ebl_beer_page_content_wrapper" name="ebl_beer_page_content_wrapper" value="<?php echo get_option('ebl_beer_page_content_wrapper'); ?>">
<?php }

function ebl_beer_page_sidebar_wrapper(){?>
  <label for="ebl_beer_page_sidebar_wrapper">Leave blank to use default class</label><br>
  <input type="text" id="ebl_beer_page_sidebar_wrapper" name="ebl_beer_page_sidebar_wrapper" value="<?php echo get_option('ebl_beer_page_sidebar_wrapper'); ?>">
<?php }

function ebl_beer_page_sidebar(){?>
  <label for="ebl_beer_page_sidebar">Specify the sidebar to use in your template if you don't want to use the default</label><br>
  <select id="ebl_beer_page_sidebar" name="ebl_beer_page_sidebar">
     <option value="ebl-no-widget">No Widget</option>
     <option value="default">Use Default Widget</option>
  <?php foreach($GLOBALS['wp_registered_sidebars'] as $registered_sidebar){?>
     <option value="<?php echo $registered_sidebar['id']; ?>" <?php selected(get_option('ebl_beer_page_sidebar'), $registered_sidebar['id']);?>><?php echo $registered_sidebar['name']; ?></option>
 <?php }?>
  </select>
<?php }

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
add_action("admin_init", "ebl_settings_register");

function ebl_beer_page_settings_header(){
  echo "Adjust the settings for default beer page template";
}

function ebl_default_menu_image(){
  wp_enqueue_script('settings-media-uploader.js', plugin_dir_url(__FILE__).'js/settings-media-uploader.js');
  wp_enqueue_media();
  ?>
  <img class="ebl_default_menu_image" src="<?php echo get_option('ebl_default_menu_image');?>">
  <input class="hidden" type="text" name="ebl_default_menu_image" id="image_attachment_id" value="<?php echo get_option('ebl_default_menu_image'); ?>" />
  <input type="button" class="button" name="ebl_default_menu_image" id="ebl_default_menu_image" value="<?php _e( 'Upload/Select images' ); ?>" />
  <?php
}

function ebl_menu_settings_header(){
echo "Update the menu setting defaults";
}

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
