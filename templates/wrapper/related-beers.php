<?php
/**
 * Similar Beers component
 * Overwrite this file by adding 'easy-beer-lister/wrapper/similar-beers.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beers = ebl_get_beer_list();
if(!$beers instanceof \ebl\app\beerList) return; //Bail early if there isn't an active query
$beers->getSimilarBeers();
?>

<?php if($beers->similar->haveBeers()): ?>
  <div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
    <h3>Related Beers:</h3>
    <div class="ebl-archive-wrapper">
      <?php while($beers->similar->haveBeers()): $beers->similar->theBeer(); ?>
        <?php $this->getPartial('archive', 'beer'); ?>
      <?php endwhile; ?>
    </div>
  </div>
<?php endif; ?>

