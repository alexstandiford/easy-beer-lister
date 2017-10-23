<?php
/**
 * Wrapper for single beer item
 * Overwrite this file by adding 'easy-beer-lister/wrapper/default.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


use ebl\app\beerList;

if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
global $wp_query;
get_header();
$beers = ebl_get_beer_list();
?>

<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <div class="ebl-wrapper-content">
    <?php if($beers->haveBeers()): ?>
      <?php while($beers->haveBeers()): $beers->theBeer(); ?>
        <?php $this->getPartial('wrapper','beers'); ?>
      <?php endwhile; ?>
    <?php endif; ?>
    <?php $this->getPartial('wrapper', 'related-beers'); ?>
  </div>
</div>

<?php get_footer(); ?>