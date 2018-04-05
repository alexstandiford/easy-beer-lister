<?php
/**
 * Single Beer in-archive Component
 * Overwrite this file by adding 'easy-beer-lister/archive/beer.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer();
?>
<article <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <?php $this->getPartial('component','beer-glass'); ?>
  <?php $this->getPartial('component', 'beer-info-basic'); ?>
</article>