<?php
/**
 * Beer list shortcode component
 * Overwrite this file by adding 'easy-beer-lister/shortcode/beer-list.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer();
$beers = ebl_get_beer_list();
if($beers->haveBeers()):
  ?>
  <div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
    <?php while($beers->haveBeers()): $beers->theBeer(); ?>
      <dl><a href="<?= get_post_permalink(); ?>"><?= get_the_title(); ?></a></dl>
      <dd><?= get_the_excerpt(); ?><br/><?php $this->getPartial('component','beer-stats'); ?></dd>
    <?php endwhile; ?>
  </div>
<?php endif; ?>