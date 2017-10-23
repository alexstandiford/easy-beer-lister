<?php
/**
 * Archive Title Area
 * Overwrite this file by adding 'easy-beer-lister/archive/title.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer();
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <h1>Beers</h1>
  <?php $this->getPartial('component','filter'); ?>
</div>