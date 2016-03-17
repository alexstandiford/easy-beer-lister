<?php
function tasbb_default_menu_scripts(){
  echo '<link rel="stylesheet" href="'.plugin_dir_url(__FILE__).'style/tasbb-tv.css">'; 
}
add_action('tasbb_menu_head_scripts','tasbb_default_menu_scripts');

tasbb_menu_head();
$tasbb_menu = new tasbb_menu(4);

?>
<header>
  <h1><?php echo get_the_title(); ?></h1>
  <h2><?php echo $tasbb_menu->subheading; ?></h2>
  <p><?php echo $tasbb_menu->beforeMenu; ?></p>
</header>
<ul>
<?php
$beers = new WP_Query($tasbb_menu->args());
if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post();
$beer_width = 100 / (ceil($beers->found_posts / $tasbb_menu->beersPerColumn));
$beer_height = 100 / $tasbb_menu->beersPerColumn;

?>
	<li class="print-wrap" style="height: <?php echo $beer_height; ?>%; width:<?php echo $beer_width; ?>%;">
      <aside <?php if($tasbb_menu->filter['show_image'] == true){ echo 'class="has-img"'; }; ?>>
        <?php if($tasbb_menu->filter['show_image'] == true){ ?><img src="<?php echo the_post_thumbnail_url();?>"><?php };?>
        <div class="beer-info">
          <h1><?php echo get_the_title(); ?></h1>
          <?php
          //--- PRICE ---//
          if($tasbb_menu->filter['show_price'] == TRUE && tasbb_beer_info_exists('tasbb_price')){?>
          <span class="price"> - 
             $<?php tasbb_beer_info('tasbb_price');?>
          </span>
          <?php }; ?>
          <?php if($tasbb_menu->filter['show_style'] == TRUE){?>
          <em><?php tasbb_beer_info('style',null,null,true); ?></em>
          <?php }; ?>
          <?php
          //--- DESCRIPTION ---//
          if($tasbb_menu->filter['show_description'] == TRUE){?>
          <p><?php echo get_the_excerpt();?></p>
           <?php }; ?>
           <?php if($tasbb_menu->filter['show_ibu'] == TRUE || $tasbb_menu->filter['show_abv'] == TRUE || $tasbb_menu->filter['show_og'] == TRUE){?>
             <div>
              <?php if(tasbb_beer_info_exists('tasbb_abv') == true){ ?>  <span>ABV: <?php if($tasbb_menu->filter['show_abv'] == TRUE){ tasbb_beer_info('tasbb_abv'); };?></span><?php  }; ?>
              <?php if(tasbb_beer_info_exists('tasbb_ibu') == true){ ?>  <span>IBU: <?php if($tasbb_menu->filter['show_ibu'] == TRUE){ tasbb_beer_info('tasbb_ibu'); };?></span><?php  }; ?>
              <?php if(tasbb_beer_info_exists('tasbb_og') == true){ ?>  <span>OG: <?php if($tasbb_menu->filter['show_og'] == TRUE){ tasbb_beer_info('tasbb_og'); };?></span><?php  }; ?>
             </div>
           </div>
		</aside>
	</li>
<?php }; ?>
<?php endwhile; endif; ?>
</ul>
<?php wp_reset_query(); ?>
<p class="after-menu  "><?php echo $tasbb_menu->afterMenu; ?></p>
