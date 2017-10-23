<?php
/**
 * Beer availability component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-availability.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID); ?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <strong>Availability:</strong>
  <?php if($beer->getAvailabilityStartDate() || $beer->isOnTap()): ?>
    <?php if($beer->getAvailabilityStartDate() && !$beer->isOnTap()): ?>
      <span class="availability-start-date"><?= $beer->getAvailabilityStartDate(); ?></span>
    <?php endif; ?>
    <?php if($beer->getAvailabilityEndDate() && !$beer->isOnTap()): ?>
       - <span class="availability-end-date"><?= $beer->getAvailabilityEndDate(); ?></span>
    <?php endif; ?>
    <?php if($beer->isOnTap()): ?><span class="on-tap-badge">On Tap Now!</span><?php endif; ?>
  <?php else: ?>
    <span class="ebl-availability">Currently Unavailable</span>
  <?php endif; ?>
</div>
