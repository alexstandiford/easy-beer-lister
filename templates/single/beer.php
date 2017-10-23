<?php
/**
 * Single Beer Item
 * Overwrite this file by adding 'easy-beer-lister/single/beer.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer();
?>
<div <?= $this->wrapperClasses(); ?> <?= $this->wrapperArgs(); ?>>
  <div class="ebl-info-wrapper">
    <?php $this->getPartial('component', 'beer-info-basic'); ?>
    <?php $this->getPartial('component', 'beer-stats'); ?>
    <?php $this->getPartial('component', 'beer-pairings'); ?>
    <?php $this->getPartial('component', 'beer-availability'); ?>
  </div>
  <?php $this->getPartial('component', 'beer-glass'); ?>
  <?php $this->getPartial('component', 'beer-video'); ?>

</div>