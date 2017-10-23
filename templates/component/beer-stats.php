<?php
/**
 * Beer stats component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-stats.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID);
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <?php if($beer->hasAny(['abv', 'ibu', 'og'])): ?>
    <?php if($beer->getABV()): ?><strong>ABV:</strong>
      <span class="ebl-abv"><?= $beer->getABV(); ?></span><?php endif; ?>
    <?php if($beer->getIBU()): ?><strong>IBU:</strong>
      <span class="ebl-ibu"><?= $beer->getIBU(); ?></span><?php endif; ?>
    <?php if($beer->getOG()): ?><strong>OG:</strong>
      <span class="ebl-og"><?= $beer->getOG(); ?></span><?php endif; ?>
  <?php endif; ?>
</div>