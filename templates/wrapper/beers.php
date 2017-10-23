<?php
/**
 * Beer Loop Wrapper
 * Overwrite this file by adding 'easy-beer-lister/wrapper/beers.php' to your theme
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


if(!defined('ABSPATH')) exit;
if(!$this instanceof \ebl\app\templateLoader) return; //Bail early if we're not in a template loader object
?>
<article <?= $this->wrapperClasses(); ?> <?= $this->wrapperArgs(); ?>>
  <?php $this->getPartial(); ?>
</article>