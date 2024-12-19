<?php
/**
 * Account profile
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/profile.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */
use uListing\Classes\StmUser;
use uListing\Classes\StmListingTemplate;

$user = new StmUser( get_current_user_id() );
$view = ( ulisting_page_endpoint() ) ? ulisting_page_endpoint() : 'dashboard';
?>
<div class="account-page">
	<?php StmListingTemplate::load_template( 'account/'.$view, ['user' => $user], true ); ?>
</div>