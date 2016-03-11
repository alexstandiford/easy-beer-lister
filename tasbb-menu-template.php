<?php
tasbb_menu_head();

?>
<?php
$a = [
    'sort' => get_post_meta(get_the_ID(),'tasbb_export_sort_order',true),
    'sortby' => get_post_meta(get_the_ID(),'tasbb_export_sortby',true),
    'on-tap'  => get_post_meta(get_the_ID(),'tasbb_export_ontap',true),
    'pairings' => get_post_meta(get_the_ID(),'tasbb_export_pairings',true),
    'tags' => tasbb_parse_taxonomy_checkbox('tags'),
    'style' => tasbb_parse_taxonomy_checkbox('style'),
    'availability' => tasbb_parse_taxonomy_checkbox('availability'),
    'show_description' => get_post_meta(get_the_ID(),'tasbb_export_show_description',true),
		'show_price' => get_post_meta(get_the_ID(),'tasbb_export_show_price',true),
		'show_image' => get_post_meta(get_the_ID(),'tasbb_export_show_img',true),
		'show_ibu' => get_post_meta(get_the_ID(),'tasbb_export_show_ibu',true),
		'show_abv' => get_post_meta(get_the_ID(),'tasbb_export_show_abv',true),
		'show_og' => get_post_meta(get_the_ID(),'tasbb_export_show_og',true),
		'show_style' => get_post_meta(get_the_ID(),'tasbb_export_show_style',true),
];

  $args = [
    'post_type' => 'beers',
    'order'     => $a['sort'],
		'orderby' => 'meta_value_num',
		'meta_key' => $a['sortby'],
    "tax_query" => [],
		'posts_per_page' => -1
    ];
  
	if($a['sortby'] == 'name'){
		$args['orderby'] = 'name';
		unset($args['meta_key']);
	}
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
    array_push($args['tax_query'],[
      'taxonomy' => 'tags',
      'field' => 'slug',
      'terms' => $a['tags']
    ]);
  };
    //--- Availability ---//
  if($a['availability'] != null){
    array_push($args['tax_query'],[
      'taxonomy' => 'availability',
      'field' => 'slug',
      'terms' => $a['availability']
    ]);
  };
    //--- type ---//
  if($a['style'] != null){
    array_push($args['tax_query'],[
      'taxonomy' => 'style',
      'field' => 'slug',
      'terms' => $a['style']
    ]);
  };
?>
<!DOCTYPE HTML>
<head>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'style/tasbb-print.css'?>">
</head>
<style>
<?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_css',true); ?>
</style>
<html>
	<h1 class="warning">There is a known bug with Google Chrome that prevents menus from printing properly.  To get the best print results, use <a href="http://www.firefox.com/">Mozilla Firefox</a>, or <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">Microsoft Edge.</a></h1>
<h1><?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_heading',true); ?></h1>
<h2><?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_subheading',true); ?></h2>
<p><?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_before_menu',true); ?></p>
<dl>
<?php
$beers = new WP_Query($args);
if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post(); ?>
	<div class="print-wrap">
		<dt><?php
		//--- IMAGE ---//
		if($a['show_image'] == true){
			the_post_thumbnail('medium');
		}; ?>
			<?php echo get_the_title(); ?>
		<?php
		//--- PRICE ---//
		if($a['show_price'] == TRUE && tasbb_beer_info_exists('tasbb_price')){?>
		<span class="price"> - 
			$<?php tasbb_beer_info('tasbb_price');?>
		</span>
		</dt>
		<?php }; ?>
		<?php if($a['show_style'] == TRUE){?>
		<dd>
			<em><?php tasbb_beer_info('style',null,null,true); ?></em>
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
			<span>ABV: <?php if($a['show_abv'] == TRUE){ tasbb_beer_info('tasbb_abv'); };?></span>
			<span>IBU: <?php if($a['show_ibu'] == TRUE){ tasbb_beer_info('tasbb_ibu'); };?></span>
			<span>OG: <?php if($a['show_og'] == TRUE){ tasbb_beer_info('tasbb_og'); };?></span>
		</dd>
	</div>
<?php }; ?>
<?php endwhile; endif; ?>
</dl>
<?php wp_reset_query(); ?>
<p><?php echo get_post_meta(get_the_ID(),'tasbb_export_menu_after_menu',true); ?></p>
</html>