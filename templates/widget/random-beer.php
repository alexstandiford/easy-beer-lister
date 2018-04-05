<?php
/**
 * Random Beer Widget
 * Overwrite this file by adding 'easy-beer-lister/widget/random-beer.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer_list();
if($beer->haveBeers()) : while($beer->haveBeers()) : $beer->theBeer();
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <?php $this->getPartial('component','beer-glass'); ?>
  <?php $this->getPartial('component','beer-info-basic'); ?>
</div>
<?php endwhile; endif; ?>