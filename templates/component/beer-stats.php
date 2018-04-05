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
    <?php if($beer->getABV()): ?><strong class="ebl-stat-title ebl-abv">ABV:</strong>
      <span class="ebl-stat ebl-abv"><?= $beer->getABV(); ?></span><?php endif; ?>
    <?php if($beer->getIBU()): ?><strong class="ebl-stat-title ebl-ibu">IBU:</strong>
      <span class="ebl-stat ebl-ibu"><?= $beer->getIBU(); ?></span><?php endif; ?>
    <?php if($beer->getOG()): ?><strong class="ebl-stat-title ebl-og">OG:</strong>
      <span class="ebl-stat ebl-og"><?= $beer->getOG(); ?></span><?php endif; ?>
  <?php endif; ?>
</div>