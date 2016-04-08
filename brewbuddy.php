<?php
/*
Plugin Name: BrewBuddy
Description: Manage Your Beers, Beer Pairings, and What's on Tap Easily
Version:     1.02
Author:      Alex Standiford
Author URI:  http://www.fillyourtaproom.com
*/


/*--- ADDS REFERRAL INFO TO DB ---*/
function tasbb_check_for_referral(){
	if(file_exists(plugin_dir_path(__FILE__).'ref.txt')){
		if(get_option('tasbb_referral_id') == false){
			$ref_id = file_get_contents(plugin_dir_path(__FILE__).'ref.txt');
			add_option('tasbb_referral_id',$ref_id);
			echo "added to DB";
		}
	};
}
add_action( 'init', 'tasbb_check_for_referral');

/*--- REGISTERS BEER POST TYPE ---*/
function tasbb_beer_page_init(){
  register_post_type(
    'beers',
    [
      'public'             => true,
      'has_archive'        => true,
      'capability_type'    => 'post',
      'show_in_menu'       => true,
      'show_ui'            => true,
      'show_in_admin_bar'  => true,
      'can_export'         => true,
      'menu_position'      => 5,
      'supports'           => array( 'title','editor','excerpt', 'revisions', 'thumbnail' ),
      'menu_icon'          => plugin_dir_url( __FILE__ ).'/media/beer-icon.png',
      //---BEGIN LABELS---//
      'labels'             =>[
        'name'               => __( 'Beers'),
        'singular_name'      => __( 'Beer'),
      'label'              => __( 'Beers' ),
		'add_new'            => _x( 'Add New', 'beer'),
		'add_new_item'       => __( 'Add New Beer'),
		'new_item'           => __( 'New Beer'),
		'edit_item'          => __( 'Edit Beer'),
		'view_item'          => __( 'View Beer'),
		'all_items'          => __( 'All Beers'),
		'search_items'       => __( 'Search Beers'),
		'not_found'          => __( 'No beer found.'),
		'not_found_in_trash' => __( 'No beer found in Trash.')
      ],
    ]
  );
}
add_action( 'init', 'tasbb_beer_page_init' );

/*--- REGISTERS MENU POST TYPE ---*/
function tasbb_menu_page_init(){
  register_post_type(
    'menus',
    [
      'public'             => true,
      'has_archive'        => false,
      'capability_type'    => 'page',
      'post-formats'       => true,
      'show_in_menu'       => true,
      'show_ui'            => true,
      'show_in_admin_bar'  => true,
      'exclude_from_search'=> true,
      'show_in_nav_menus'  => false,
      'menu_position'      => 6,
      'supports'           => array('revisions', 'title','template'),
      'menu_icon'          => plugin_dir_url( __FILE__ ).'/media/menu-icon.png',
      //---BEGIN LABELS---//
      'labels'             =>[
        'name'               => __( 'Menus'),
        'singular_name'      => __( 'Menu'),
      'label'              => __( 'Menus' ),
		'add_new'            => _x( 'Add New', 'menu'),
		'add_new_item'       => __( 'Add New Menu'),
		'new_item'           => __( 'New Menu'),
		'edit_item'          => __( 'Edit Menu'),
		'view_item'          => __( 'View Menu'),
		'all_items'          => __( 'All Menus'),
		'search_items'       => __( 'Search Menus'),
		'not_found'          => __( 'No menu found.'),
		'not_found_in_trash' => __( 'No menu found in Trash.')
      ],
    ]
  );
}
add_action( 'init', 'tasbb_menu_page_init' );

function tasbb_beer_taxonomy_init(){
	register_taxonomy(
		'style',
		'beers',
		array(
			'label' => __( 'Beer Styles' ),
			'rewrite' => array( 'slug' => 'style' ),
         'hierarchical' => true,
		)
	);
}
add_action( 'init', 'tasbb_beer_taxonomy_init' );

function tasbb_beer_pairing_taxonomy_init(){
	register_taxonomy(
		'pairing',
		'beers',
		array(
			'label' => __( 'Pairings' ),
		)
	);
}
add_action( 'init', 'tasbb_beer_pairing_taxonomy_init' );

function tasbb_beer_availability_taxonomy_init(){
	register_taxonomy(
		'availability',
		'beers',
		array(
			'label' => __( 'Availability' ),
         'hierarchical' => true,
		)
	);
}
add_action( 'init', 'tasbb_beer_availability_taxonomy_init' );

/*---REGISTERS DEFAULT AVAILABILITY TAXONOMY VALUES---*/
function tasbb_beer_taxonomy_defaults_init(){
  $terms = ['On Tap','Year Round','Spring','Summer','Fall','Winter'];
  foreach($terms as $term){
    $nice_term = strtolower($term);
    $nice_term = str_replace(' ','-',$term);
    if(term_exists( $term , 'availability' ) == null){
      wp_insert_term(
        $nice_term, // the term 
        'availability', // the taxonomy
        array(
          'description'=> $term,
          'slug' => $nice_term,
        )
      );
    }
  };
}

add_action( 'init', 'tasbb_beer_taxonomy_defaults_init' );


function tasbb_beer_tags_taxonomy_init(){
	register_taxonomy(
		'tags',
		'beers',
		array(
			'label' => __( 'Tags' ),
		)
	);
}
add_action( 'init', 'tasbb_beer_tags_taxonomy_init' );

/*---REGISTERS DEFAULT BEER PAGE TEMPLATE---*/
function tasbb_beer_page_template( $template ) {
	if (is_singular('beers') && !file_exists(get_template_directory().'/single-beers.php')) {
		$new_template = dirname(__FILE__).'/tasbb-beer-template.php';
			return $new_template ;
	}
	return $template;
}
add_filter( 'template_include', 'tasbb_beer_page_template');

/*---REGISTERS DEFAULT MENU PAGE TEMPLATE---*/
function tasbb_menu_page_template( $template ) {
	global $post;
	if (is_singular('menus') && tasbb_locate_menu_template($post->post_name) == false) {
		$object_slug = get_post_meta(get_the_id(),'tasbb_menu_template',true);
		$template = tasbb_get_menu_template($object_slug);
		$template = $template->directory.'/'.$template->file_name;
	}
	elseif(is_singular('menus') && tasbb_locate_menu_template($post->post_name) == true){
		$template = tasbb_locate_menu_template($post->post_name);
	}
	return $template;
}
add_filter( 'template_include', 'tasbb_menu_page_template');

/*--- CUSTOM STYLES ---*/
function tasbb_beer_styles_init(){
  $styles = [
    'tasbb.css'
  ];
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).'style/'.$style);
  }
}
add_action('wp_enqueue_scripts','tasbb_beer_styles_init');


/*--- CUSTOM STYLES FOR SETTINGS PAGES ---*/
function tasbb_beer_admin_styles_init(){
  $styles = [
    'tasbb-settings.css'
  ];
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).'style/'.$style);
  }
}
add_action('admin_enqueue_scripts','tasbb_beer_admin_styles_init');


function tasbb_beer_scripts_init(){
  $scripts = [
    'tasbb.js'
  ];
  foreach($scripts as $script){
    wp_enqueue_script($script,plugin_dir_url(__FILE__).'js/'.$script);
  }
}
add_action('wp_footer','tasbb_beer_scripts_init');

/*--- STYLE TAGS ---*/
function tasbb_beer_inline_style_overrides(){
  $e .= '<!--- BeerBuddy Style Overrides --->';
  $e .= '<!--- These values can be adjusted in the BeerBuddy settings --->';
  $e .= '<style>';
  $e .=   '.beer-popup{';
  $e .=     'transform:translate('.get_option('tasbb_js_hover_x').'px,'.get_option('tasbb_js_hover_y').'px);';
  $e .=   '}';
  $e .= '</style>';
  echo $e;
}
add_action('wp_head','tasbb_beer_inline_style_overrides',30);

function tasbb_meta_scripts() {
	wp_enqueue_script('media-upload.js',plugin_dir_url(__FILE__).'js/media-upload.js');
}

/*------ SETTINGS SIDEBAR ACTIONS ------*/
function tasbb_settings_sidebar_cta(){?>
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
		<div class="mc-field-group input-group hidden">
				<ul><li><input checked type="checkbox" value="1" name="group[18977][1]" id="mce-group[18977]-18977-0"><label for="mce-group[18977]-18977-0">BrewBuddy User</label></li>
		<li><input checked type="checkbox" value="2" name="group[18977][2]" id="mce-group[18977]-18977-1"><label for="mce-group[18977]-18977-1">Website Efficency Workflow</label></li>
		</ul>
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
<?php }
add_action('tasbb_settings_sidebar', 'tasbb_settings_sidebar_cta');
include_once(dirname(__FILE__).'/fields.php');
include_once(dirname(__FILE__).'/functions.php');
include_once(dirname(__FILE__).'/tasbb-settings.php');
include_once(dirname(__FILE__).'/widgets.php');
include_once(dirname(__FILE__).'/tasbb-menu-framework.php');