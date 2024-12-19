<?php
/**
 * Account dashboard
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/dashboard.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */
use uListing\Classes\StmListingTemplate;
use uListing\Classes\StmUser;

$user = new StmUser( $user );

?>

<?php StmListingTemplate::load_template( 'account/navigation', ['user' => $user], true );?>

<div class="my-account">
    <div class="stm-row">
        <div class="stm-col-12">
            <div class="stm-row">
                <div class="stm-col-3 avatar-wrap">
					<?php if( !empty( $user->getAvatarUrl() ) ) : ?>
                        <div class="avatar"><img src="<?php echo esc_url( $user->getAvatarUrl() ); ?>" alt="<?php echo esc_attr( $user->user_login ); ?>" /></div>
					<?php else: ?>
                        <div class="avatar"><img src="<?php echo ULISTING_URL."/assets/img/placeholder-ulisting.png" ?>" alt="<?php echo esc_attr( $user->user_login ); ?>" /></div>
					<?php endif;?>
                </div>
                <div class="stm-col-9 user-info">
                    <div class="user-top-info">
                        <div class="user-personal-info-top">
							<?php if( !empty( $user->nickname ) ) { ?><h2 class="page-title"><?php echo esc_attr( $user->nickname ); ?></h2><?php } ?>
                        </div>
                        <div class="edit-account-button">
                            <a class="motors-button heading-font" href="<?php echo StmUser::getUrl("edit-profile"); ?>"><?php esc_html_e( 'Edit account', 'motors' ); ?></a>
                        </div>
                    </div>
                    <div class="user-middle-info">
                        <div class="user-info-1">
                            <?php if( !empty( $user->phone ) || !empty( $user->cful_office ) || !empty( $user->cful_fax ) ) { ?>
                                <div class="user_box">
                                    <?php if( !empty( $user->phone ) ) { ?>
                                        <div class="user_box_field">
                                            <span class="user_box_label"><i class="icon-smartphone"></i><?php esc_html_e( 'Mobile:', 'motors' ); ?></span>
                                            <span class="user_box_value heading-font"><?php echo esc_html( $user->phone ); ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if( !empty( $user->cful_office ) ) { ?>
                                        <div class="user_box_field">
                                            <span class="user_box_label"><i class="icon-telephone2"></i><?php esc_html_e( 'Office:', 'motors' ); ?></span>
                                            <span class="user_box_value heading-font"><?php echo esc_html( $user->cful_office ); ?></span>
                                        </div>
                                    <?php } ?>
                                    <?php if( !empty( $user->cful_fax ) ) { ?>
                                        <div class="user_box_field">
                                            <span class="user_box_label"><i class="icon-printer"></i><?php esc_html_e( 'Fax:', 'motors' ); ?></span>
                                            <span class="user_box_value heading-font"><?php echo esc_html( $user->cful_fax ); ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="user-info-2">
                            <div class="user_box">
                                <?php if( !empty( $user->cful_address ) ) { ?>
                                    <div class="user_box_field">
                                        <span class="user_box_label"><i class="icon-map-marker"></i></span>
                                        <span class="user_box_value heading-font"><a href="<?php echo esc_url( $user->cful_address ); ?>" target="_blank"><?php echo esc_attr( $user->cful_address ); ?></a></span>
                                    </div>
                                <?php } ?>
                                <?php if( !empty( $user->cful_license ) ) { ?>
                                    <div class="user_box_field">
                                        <span class="user_box_label"><i class="icon-license2"></i></span>
                                        <span class="user_box_value heading-font"> <?php esc_html_e( 'License:', 'motors' ); ?> <?php echo esc_attr( $user->cful_license  ); ?></span>
                                    </div>
                                <?php } ?>
                                <?php if( !empty( $user->cful_tax_number ) ) { ?>
                                    <div class="user_box_field">
                                        <span class="user_box_label"><i class="icon-document"></i></span>
                                        <span class="user_box_value heading-font"><?php esc_html_e( 'Tax number:', 'motors' ); ?> <?php echo esc_attr( $user->cful_tax_number ); ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="user-bottom-info">
                        <ul class="user-personal-socials-box">
							<?php if( !empty( $user->facebook ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->facebook ); ?>" target="_blank" rel="nofollow"><i class="fab fa-facebook-f"></i></a></li>
							<?php } ?>
							<?php if( !empty( $user->twitter ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->twitter ); ?>" target="_blank" rel="nofollow"><i class="fab fa-twitter"></i></a></li>
							<?php } ?>
							<?php if( !empty( $user->google_plus ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->google_plus ); ?>" target="_blank" rel="nofollow"><i class="fab fa-google-plus-g"></i></a></li>
							<?php } ?>
							<?php if( !empty( $user->youtube_play ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->youtube_play ); ?>" target="_blank" rel="nofollow"><i class="fab fa-youtube"></i></a></li>
							<?php } ?>
							<?php if( !empty( $user->linkedin ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->linkedin ); ?>" target="_blank" rel="nofollow"><i class="fab fa-linkedin-in"></i></a></li>
							<?php } ?>
							<?php if( !empty( $user->instagram ) ) { ?>
                                <li><a href="<?php echo esc_url( $user->instagram ); ?>" target="_blank" rel="nofollow"><i class="fab fa-instagram"></i></a></li>
							<?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  do_action('ulisting-account-dashboard-top', [ 'user' => $user] )?>
<?php  do_action('ulisting-account-dashboard-center', [ 'user' => $user ])?>
<?php  do_action('ulisting-account-dashboard-bottom', [ 'user' => $user ])?>
