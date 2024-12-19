<?php
/**
 * Listing inventory breadcrumbs
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-list/breadcrumbs.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

if(function_exists('bcn_display_list')) echo '<ul class="stm-breadcrumbs heading-font">' . bcn_display_list(true) . '</ul>';
?>