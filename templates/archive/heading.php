<?php
/**
 * Beer image component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-info-basic.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer();
?>
<?php if(is_tax(['style', 'pairing', 'tags'])): ?>
  <div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
    <h1><?= ucfirst(get_query_var('taxonomy')).': '.ucfirst(str_replace('-',' ',get_query_var('term'))); ?></h1>
    <p><a href="<?= get_post_type_archive_link('beers'); ?>">View All Beers</a></p>
  </div>
<?php else: ?>
  <?php $this->getPartial('component', 'filter'); ?>
<?php endif; ?>