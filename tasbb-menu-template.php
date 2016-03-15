<?php
tasbb_menu_head();
$tasbb_menu = new tasbb_menu;
?>
<h1><?php echo $tasbb_menu->heading; ?></h1>
<h2><?php echo $tasbb_menu->subheading; ?></h2>
<p><?php echo $tasbb_menu->beforeMenu; ?></p>
<dl>
<?php
$beers = new WP_Query($tasbb_menu->args());
if($beers->have_posts()) : while($beers->have_posts()) : $beers->the_post(); ?>
	<div class="print-wrap">
		<dt><?php
		//--- IMAGE ---//
		if($tasbb_menu->filter['show_image'] == true){
			the_post_thumbnail('medium');
		}; ?>
			<?php echo get_the_title(); ?>
		<?php
		//--- PRICE ---//
		if($tasbb_menu->filter['show_price'] == TRUE && tasbb_beer_info_exists('tasbb_price')){?>
		<span class="price"> - 
			$<?php tasbb_beer_info('tasbb_price');?>
		</span>
		</dt>
		<?php }; ?>
		<?php if($tasbb_menu->filter['show_style'] == TRUE){?>
		<dd>
			<em><?php tasbb_beer_info('style',null,null,true); ?></em>
		</dd>
		<?php }; ?>
		<?php
		//--- DESCRIPTION ---//
		if($tasbb_menu->filter['show_description'] == TRUE){?>
		<dd class="tasbb-shortcode-beer-description">
			<?php echo get_the_excerpt();?>
			<?php }; ?>
			<?php
			if($tasbb_menu->filter['show_price'] == TRUE || $tasbb_menu->filter['show_description'] == TRUE){?>
		</dd>
		<?php }; ?>
		<?php if($tasbb_menu->filter['show_ibu'] == TRUE || $tasbb_menu->filter['show_abv'] == TRUE || $tasbb_menu->filter['show_og'] == TRUE){?>
		<dd>
			<?php if(tasbb_beer_info_exists('tasbb_abv') == true){ ?>  <span>ABV: <?php if($tasbb_menu->filter['show_abv'] == TRUE){ tasbb_beer_info('tasbb_abv'); };?></span><?php  }; ?>
			<?php if(tasbb_beer_info_exists('tasbb_ibu') == true){ ?>  <span>IBU: <?php if($tasbb_menu->filter['show_ibu'] == TRUE){ tasbb_beer_info('tasbb_ibu'); };?></span><?php  }; ?>
			<?php if(tasbb_beer_info_exists('tasbb_og') == true){ ?>  <span>OG: <?php if($tasbb_menu->filter['show_og'] == TRUE){ tasbb_beer_info('tasbb_og'); };?></span><?php  }; ?>
		</dd>
	</div>
<?php }; ?>
<?php endwhile; endif; ?>
</dl>
<?php wp_reset_query(); ?>
<p><?php echo $tasbb_menu->afterMenu; ?></p>
</html>