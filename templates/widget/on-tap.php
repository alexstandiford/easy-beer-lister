<?php
/**
 * On Tap Component
 * Overwrite this file by adding 'easy-beer-lister/widget/on-tap.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beers = ebl_get_beer_list();
?>
<div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
  <?php if($beers->haveBeers()): ?>
    <ul>
      <?php while($beers->haveBeers()): $beers->theBeer(); ?>
        <li><a href="<?= get_post_permalink(); ?>"><?= get_the_title(); ?></a></li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>There are no beers currently on-tap!</p>
  <?php endif; ?>
</div>
