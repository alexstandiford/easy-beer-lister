<?php get_header();?>
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
	<div id="primary" class="content-heading-wrapper">
        <div class="content-heading-overlay">
            <div class="content-heading">
                <h2><?php the_title();?></h2>
                <?php if(function_exists('beer_info')){beer_info('style','h2',null,true);};?>
                <?php if(function_exists('beer_is_on_tap')){
                if(beer_is_on_tap()){ ?>
                <h4 class="on-tap"><a href="<?php beer_info_url('availability',null,true) ?>">On Tap Now!</a></h4>
                <?php };};?>
                <hr>
                <p class="categories">Availability:<?php if(function_exists('beer_info')){beer_info('availability','span');};?></p>
            </div>
        </div>
    </div>
    <div id="primary" class="tasbb-primary-content-wrapper">
        <div class="tasbb-primary-content">
        <blockquote>
        <?php the_excerpt();?>
        </blockquote>
        <?php if(function_exists('beer_info')){?>
          <?php if(beer_info_exists('untappd_url')){?>
          <a href="<?php beer_info('untappd_url');?>" target="blank">View on Untappd</a>
          <?php }; ?>
          <?php if(beer_info_exists('abv') || beer_info_exists('ibu') || beer_info_exists('og')){?>
					<h3>Beer Info</h3>
				<div class="tasbb-beer-info-wrapper">
				 <?php the_post_thumbnail();?>
         <dl class="tasbb-beer-info">
           <?php if(beer_info_exists('abv')){?>
           <div>
           <dt>ABV:</dt>
         <?php beer_info('abv','dd'); ?>
           </div>
           <?php };
            if(beer_info_exists('ibu')){?>
           <div>
           <dt>IBU:</dt>
         <?php beer_info('ibu','dd'); ?>
           </div>
           <?php };
            if(beer_info_exists('og')){?>
           <div>
           <dt>Original Gravity:</dt>
         <?php beer_info('og','dd'); ?>
           </div>
           <?php };
            //if(beer_info_exists('pairing')){?>
           <div>
           <dt>Pairs With:</dt>
         <?php beer_info('pairing','dd'); ?>
           </div>
           <?php // }; ?>
         </dl>
					</div>
					<span class="tasbb-row"></span>
          <?php }; ?>
          <?php if(beer_info_exists('additional_photos')){?>
          <div class="beer-gallery-wrapper">
         <?php beer_photos();?>
          </div>
          <?php }; ?>
         <?php
         };
		   the_content();
         if(function_exists('beer_info')){
          beer_video();
        }
        ?>
        </div>
    </div>
 <?php
endwhile;
?>
<?php endif; ?>
<?php get_footer();?>