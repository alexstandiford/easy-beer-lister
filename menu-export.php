<?php
    require( dirname( __FILE__ ) . '/../../../' . 'wp-blog-header.php' );
		if(!is_user_logged_in()){
			echo '<h1>Please log in to view this content</h1>';
			die;
		}
	if(file_exists(get_template_directory().'/beer-menu.php')){
		include(get_template_directory().'/beer-menu.php');
		die;
	};
?>
<?php
$a = [
    'sort' => 'asc',
    'on-tap'  => get_option('tasbb_export_ontap'),
    'pairings' => get_option('tasbb_export_pairings'),
    'tags' => get_option('tasbb_export_tags'),
    'style' => get_option('tasbb_export_styles'),
    'availability' => get_option('tasbb_export_availability'),
    'show_description' => get_option('tasbb_export_show_description'),
		'show_price' => get_option('tasbb_export_show_price'),
		'show_image' => get_option('tasbb_export_show_img'),
		'show_ibu' => get_option('tasbb_export_show_ibu'),
		'show_abv' => get_option('tasbb_export_show_abv'),
		'show_og' => get_option('tasbb_export_show_og'),
		'show_style' => get_option('tasbb_export_show_style'),
];
  $args = [
    'post_type' => 'beers',
    'order'     => 'asc',
		'orderby'   => 'name',
    "tax_query" => []
    ];
  
    //--- ON TAP ---//
  if($a['on-tap'] != null){
    array_push($args['tax_query'], [
        "taxonomy"  => "availability",
        "field" => "slug",
        "terms" => "on-tap"
      ]);
  };
    //--- Pairings ---//
  if($a['pairings'] != null){
    $a['pairings'] = str_replace(' ','-',$a['pairings']);
    $a['pairings'] = strtolower($a['pairings']);
    $a['pairings'] = str_getcsv($a['pairings']);
    array_push($args['tax_query'],[
      'taxonomy' => 'pairing',
      'field' => 'slug',
      'terms' => $a['pairings']
    ]);
  };
    //--- Tags ---//
  if($a['tags'] != null){
    $a['tags'] = str_replace(' ','-',$a['tags']);
    $a['tags'] = strtolower($a['tags']);
    $a['tags'] = str_getcsv($a['tags']);
    array_push($args['tax_query'],[
      'taxonomy' => 'tags',
      'field' => 'slug',
      'terms' => $a['tags']
    ]);
  };
    //--- Availability ---//
  if($a['availability'] != null){
    $a['availability'] = str_replace(' ','-',$a['availability']);
    $a['availability'] = strtolower($a['availability']);
    $a['availability'] = str_getcsv($a['availability']);
    array_push($args['tax_query'],[
      'taxonomy' => 'availability',
      'field' => 'slug',
      'terms' => $a['availability']
    ]);
  };
    //--- type ---//
  if($a['style'] != null){
    $a['style'] = str_replace(' ','-',$a['style']);
    $a['style'] = strtolower($a['style']);
    $a['style'] = str_getcsv($a['style']);
    array_push($args['tax_query'],[
      'taxonomy' => 'style',
      'field' => 'slug',
      'terms' => $a['style']
    ]);
  };
?>
<!DOCTYPE HTML>
<head>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'tasbb-print.css'?>">
</head>
<style>
<?php echo get_option('tasbb_export_menu_css'); ?>
</style>
<html>
<h1><?php echo get_option('tasbb_export_menu_heading'); ?></h1>
<h2><?php echo get_option('tasbb_export_menu_subheading'); ?></h2>
<p><?php echo get_option('tasbb_export_menu_before_menu'); ?></p>
<dl>
<?php
$beers = new WP_Query($args);
if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post(); ?>
	<dt><?php
	//--- IMAGE ---//
	if($a['show_image'] == true){
		the_post_thumbnail('medium');
	}; ?>
		<?php echo get_the_title(); ?>
	<?php
	//--- PRICE ---//
	if($a['show_price'] == TRUE && beer_info_exists('price')){?>
	<span class="price"> - 
		$<?php beer_info('price');?>
	</span>
	</dt>
	<?php }; ?>
<?php if($a['show_style'] == TRUE){?>
<dd>
	<em><?php beer_info('style',null,null,true); ?></em>
</dd>
<?php }; ?>
<?php
//--- DESCRIPTION ---//
if($a['show_description'] == TRUE){?>
<dd class="tasbb-shortcode-beer-description">
	<?php echo get_the_excerpt();?>
	<?php }; ?>
	<?php
	if($a['show_price'] == TRUE || $a['show_description'] == TRUE){?>
</dd>
<?php }; ?>
<?php if($a['show_ibu'] == TRUE || $a['show_abv'] == TRUE || $a['show_og'] == TRUE){?>
<dd>
	<span>ABV: <?php if($a['show_abv'] == TRUE){ beer_info('abv'); };?></span>
	<span>IBU: <?php if($a['show_ibu'] == TRUE){ beer_info('ibu'); };?></span>
	<span>OG: <?php if($a['show_og'] == TRUE){ beer_info('og'); };?></span>
</dd>
<?php }; ?>
<?php endwhile; endif; ?>
</dl>
<p><?php echo get_option('tasbb_export_menu_after_menu'); ?></p>
</html>