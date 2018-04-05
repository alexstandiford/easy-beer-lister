<?php
/**
 * Beer filter component
 * Overwrite this file by adding 'easy-beer-lister/component/filter.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <strong>Filter Beers By</strong>
  <div id="beer-filters">
    <button class="js--ebl-filter-on-tap">On Tap</button>
    <button class="js--ebl-filter-in-season">In Season</button>
    <button class="js--ebl-filter-out-of-season">Out of Season</button>
    <button class="js--ebl-filter-year-round">Year Round</button>
    <button class="js--ebl-filter-reset">Reset</button>
  </div>
</div>
