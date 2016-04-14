<?php
function ebl_default_menu_scripts(){
  echo '<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'style/ebl-tv.css">'; 
}
add_action('ebl_menu_head_scripts','ebl_default_menu_scripts');

ebl_menu_head();
$ebl_menu = new ebl_menu;
?>
<header>
  <h1><?php echo get_the_title(); ?></h1>
  <h2><?php echo $ebl_menu->subheading; ?></h2>
  <p><?php echo $ebl_menu->beforeMenu; ?></p>
</header>
<ul>
<?php
$beers = new WP_Query($ebl_menu->args());
if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post();
$beer_width = 100 / (ceil($beers->found_posts / $ebl_menu->beersPerColumn));
$beer_height = 100 / $ebl_menu->beersPerColumn;
?>
	<li class="print-wrap" style="height: <?php echo $beer_height; ?>%; width:<?php echo $beer_width; ?>%;">
      <aside <?php if($ebl_menu->filter['show_image'] == true){ echo 'class="has-img"'; }; ?>>
        <?php if($ebl_menu->filter['show_image'] == true){ ?><img src="<?php echo the_post_thumbnail_url();?>"><?php };?>
        <div class="beer-info">
          <h1><?php echo get_the_title(); ?></h1>
          <?php
          //--- PRICE ---//
          if($ebl_menu->filter['show_price'] == TRUE && ebl_beer_info_exists('ebl_price')){?>
          <span class="price"> - 
             $<?php ebl_beer_info('ebl_price');?>
          </span>
          <?php }; ?>
          <?php if($ebl_menu->filter['show_style'] == TRUE){?>
          <em><?php ebl_beer_info('style',null,null,true); ?></em>
          <?php }; ?>
          <?php
          //--- DESCRIPTION ---//
          if($ebl_menu->filter['show_description'] == TRUE){?>
          <p><?php echo get_the_excerpt();?></p>
           <?php }; ?>
           <?php if($ebl_menu->filter['show_ibu'] == TRUE || $ebl_menu->filter['show_abv'] == TRUE || $ebl_menu->filter['show_og'] == TRUE){?>
             <div>
              <?php if(ebl_beer_info_exists('ebl_abv') == true){ ?>  <span>ABV: <?php if($ebl_menu->filter['show_abv'] == TRUE){ ebl_beer_info('ebl_abv'); };?></span><?php  }; ?>
              <?php if(ebl_beer_info_exists('ebl_ibu') == true){ ?>  <span>IBU: <?php if($ebl_menu->filter['show_ibu'] == TRUE){ ebl_beer_info('ebl_ibu'); };?></span><?php  }; ?>
              <?php if(ebl_beer_info_exists('ebl_og') == true){ ?>  <span>OG: <?php if($ebl_menu->filter['show_og'] == TRUE){ ebl_beer_info('ebl_og'); };?></span><?php  }; ?>
             </div>
           </div>
		</aside>
	</li>
<?php }; ?>
<?php endwhile; endif; ?>
</ul>
<?php wp_reset_query(); ?>
<p class="after-menu  "><?php echo $ebl_menu->afterMenu; ?></p>
