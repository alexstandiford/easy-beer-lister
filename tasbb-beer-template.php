<?php get_header();?>
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
	<div id="primary" class="content-heading-wrapper">
        <div class="content-heading-overlay">
            <div class="content-heading">
                <h2><?php the_title();?></h2>
                <?php if(function_exists('tasbb_beer_info')){tasbb_beer_info('style','h2',null,true);};?>
                <?php if(function_exists('tasbb_beer_is_on_tap')){
                if(tasbb_beer_is_on_tap()){ ?>
                <h4 class="on-tap"><a href="<?php tasbb_beer_info_url('availability',null,true) ?>">On Tap Now!</a></h4>
                <?php };};?>
                <hr>
                <p class="categories">Availability:<?php if(function_exists('tasbb_beer_info')){tasbb_beer_info('availability','span');};?></p>
            </div>
        </div>
    </div>
    <div id="primary" class="tasbb-primary-content-wrapper">
        <div class="tasbb-primary-content">
        <blockquote>
        <?php the_excerpt();?>
        </blockquote>
        <?php if(function_exists('tasbb_beer_info')){?>
          <?php if(tasbb_beer_info_exists('tasbb_untappd_url')){?>
          <a class="untappd-url btn" href="<?php tasbb_beer_info('tasbb_untappd_url');?>" target="blank">View on Untappd</a>
          <?php }; ?>
          <?php if(tasbb_beer_info_exists('tasbb_abv') || tasbb_beer_info_exists('tasbb_ibu') || tasbb_beer_info_exists('tasbb_og')){?>
					<h3>Beer Info</h3>
				<div class="tasbb-beer-info-wrapper">
				 <?php the_post_thumbnail();?>
         <dl class="tasbb-beer-info">
           <?php if(tasbb_beer_info_exists('tasbb_abv')){?>
           <div>
           <dt>ABV:</dt>
             <dd><?php tasbb_beer_info('tasbb_abv'); ?></dd>
           </div>
           <?php };
            if(tasbb_beer_info_exists('tasbb_ibu')){?>
           <div>
           <dt>IBU:</dt>
             <dd><?php tasbb_beer_info('tasbb_ibu'); ?></dd>
           </div>
           <?php };
            if(tasbb_beer_info_exists('tasbb_og')){?>
           <div>
           <dt>Original Gravity:</dt>
             <dd><?php tasbb_beer_info('tasbb_og'); ?></dd>
           </div>
           <?php };
            if(tasbb_beer_info_exists('pairing')){?>
           <div>
             
           <dt>Pairs With:</dt>
         <?php tasbb_beer_info('pairing','dd'); ?>
           </div>
           <?php  }; ?>
         </dl>
					</div>
					<span class="tasbb-row"></span>
          <?php }; 
         };
		   the_content();
         if(function_exists('tasbb_beer_info')){
          tasbb_beer_video();
        }
        ?>
        </div>
    </div>
 <?php
endwhile;
?>
<?php endif; ?>
<?php get_footer();?>