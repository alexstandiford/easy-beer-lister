<?php
/**
 * Default Wrapper for Beers archive
 * Overwrite this file by adding 'easy-beer-lister/wrapper/default.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beers = ebl_get_beer_list();
if(!$this->isApi) get_header();
?>
<?php if($this->isApi != true): ?>
<?php $this->getPartial('archive', 'heading'); ?>
<div id="js--filter-target">
  <?php endif; ?>
  <div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
    <?php if($beers->haveBeers()): ?>
      <?php while($beers->haveBeers()): $beers->theBeer(); ?>
        <?php $this->getPartial('archive', 'beer'); ?>
      <?php endwhile; ?>
    <?php endif; ?>
    <?php if(!$this->isApi != true && is_tax(['style', 'pairing', 'tags'])) ?></div>
  <div id="ebl-loading"></div>
</div>

<?php if(!$this->isApi) get_footer(); ?>
