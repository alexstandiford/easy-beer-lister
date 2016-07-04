<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ebl_register_addons_page(){
	add_submenu_page(
		'edit.php?post_type=beers',
		__( 'Easy Beer Lister Extensions' ),
		__( 'Extensions' ),
		'manage_options',
		'easybeerlister-addons',
		'ebl_addons_page'
	);

	add_submenu_page(
		'edit.php?post_type=menus',
		__( 'Menu Themes' ),
		__( 'Themes' ),
		'manage_options',
		'easybeerlister-themes',
		'ebl_themes_page'
	);

}
add_action('admin_menu','ebl_register_addons_page');

function ebl_addons_page(){
	$json = file_get_contents('http://easybeerlister.com/edd-api/products/');
	$json = json_decode($json);
	if(get_option('ebl_referral_id') != null){
		$referral = '/?ref='.get_option('ebl_referral_id');
	}
?><div class="ebl-addon-product-wrapper"><?php
	foreach($json->products as $product){
		$product = $product->info; ?>
	<div class="product">
		<img src="<?php echo $product->thumbnail; ?>">
		<h2><?php echo $product->title; ?></h2>
		<p><?php echo $product->excerpt; ?></p>
		<div class="cta-buttons">
			<a class="button" href="#">Documentation</a>
			<a class="button button-primary" href="http://www.easybeerlister.com/downloads/<?php echo $product->slug.$referral ?>">Learn More</a>
		</div>
	</div>
<?php }
?></div><?php
}

function ebl_themes_page(){
 // Themes will go here once I have a few done :)
}

//Class to build license checks
class ebl_license{
  public function __construct($plugin_name,$function_name){
    $this->friendly_name = str_replace(' ','_',strtolower($plugin_name));
    $this->function_name = $function_name;
    $this->key = get_option($this->function_name);
    $this->keyCheck = get_option($this->friendly_name.'_key_check');
    $this->plugin_name = $plugin_name;
    $this->plugin_url_name = urlencode($plugin_name);
  }
  
  private function api($action){
    $url = "http://easybeerlister.com/?edd_action=".$action."&item_name=".$this->plugin_url_name."&license=".$this->key."&url=".get_site_url();
    return $url;
  }
  
  private function activate_license(){
    $json = file_get_contents($this->api('activate_license'));
    $json = json_decode($json);
    if($json->license == 'valid'){
      return true;
    }
    else{
      return false;  
    }
  }
  
  private function was_key_changed(){
    if($this->key != $this->keyCheck){
      return true;
    }
    else{
      return false;
    }
  }
  
  public function activation_form(){?>
    <input type="text" id="<?php echo $this->function_name; ?>" name="<?php echo $this->function_name; ?>" value="<?php echo $this->key; ?>">
    <?php
    if($this->was_key_changed() == true){
        $activation = $this->activate_license();
        update_option($this->friendly_name.'_key_check',$this->key);
      if($activation == true){
        update_option($this->friendly_name.'_license_valid',true);
      }
      else{
        echo '<div id="message" class="notice notice-warning"><p>';
        echo "license activation for <strong>".$this->plugin_name."</strong> failed - Are you sure you entered your key correctly?";
        echo "</p></div>";
        update_option($this->friendly_name.'_license_valid',false);
      }
    }
  }
  
  public function register_setting(){
    add_settings_field($this->function_name,$this->plugin_name.' License:',$this->function_name,"ebl-addon-licenses","ebl_addon_licenses");
    register_setting("ebl_addon_licenses",$this->function_name);
  }
}