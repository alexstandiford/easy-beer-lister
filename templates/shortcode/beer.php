<?php
/**
 * Beer Shortcode component
 * Overwrite this file by adding 'easy-beer-lister/shortcode/beer.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
if(!$this->shortcode instanceof \ebl\app\shortcode\beerShortcode) return; //Bail early if this isn't inside a shortcode object
$beers = ebl_get_beer_list();
if($beers->haveBeers()): while($beers->haveBeers()): $beers->theBeer();
  $the_shortcode = $this->shortcode;
  ?>
  <a data-modal-id="<?= $the_shortcode::$instance; ?>" href="<?= get_post_permalink(); ?>" class="<?= EBL_PREFIX.'-shortcode' ?>"><?= $the_shortcode->text(); ?></a>
  <div <?= $this->wrapperClasses([EBL_PREFIX.'-shortcode-modal-'.$the_shortcode::$instance, EBL_PREFIX.'-hover-info']) ?> data-modal-id="<?= $the_shortcode::$instance; ?>" <?= $this->wrapperArgs(); ?>>
    <?php $this->getPartial('component', 'beer-glass'); ?>
    <?php $this->getPartial('component', 'beer-info-basic'); ?>
  </div>
<?php endwhile; endif; ?>