<?php function tasbb_register_addons_page(){
	add_submenu_page(
		'edit.php?post_type=beers',
		__( 'Easy Beer Lister Extensions' ),
		__( 'Extensions' ),
		'manage_options',
		'easybeerlister-addons',
		'tasbb_addons_page'
	);

	add_submenu_page(
		'edit.php?post_type=menus',
		__( 'Menu Themes' ),
		__( 'Themes' ),
		'manage_options',
		'easybeerlister-themes',
		'tasbb_themes_page'
	);

}
add_action('admin_menu','tasbb_register_addons_page');

function tasbb_addons_page(){
	$json = file_get_contents('http://easybeerlister.com/edd-api/products/');
	$json = json_decode($json);
	if(get_option('tasbb_referral_id') != null){
		$referral = '/?ref='.get_option('tasbb_referral_id');
	}
?><div class="tasbb-addon-product-wrapper"><?php
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

function tasbb_themes_page(){
 // Themes will go here once I have a few done :)
}
