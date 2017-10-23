<?php
/**
 * Beer pairings component
 * Overwrite this file by adding 'easy-beer-lister/component/beer-pairings.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
$beer = ebl_get_beer($this->postID);
?>
<?php if(!empty($beer->getPairings())): ?>
  <div <?= $this->wrapperClasses() ?> <?= $this->wrapperArgs(); ?>>
    <strong>Pairs With</strong>
    <ul class="ebl-pairings-list">
      <?php foreach($beer->getPairings() as $pairing): ?>
        <li><a href="<?= get_term_link($pairing); ?>"><?= $pairing->name; ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>