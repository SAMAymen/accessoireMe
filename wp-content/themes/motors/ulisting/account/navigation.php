<?php
/**
 * Account navigation
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/navigation.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.3.0
 */
use uListing\Classes\StmUser;
$active = ulisting_page_endpoint();

if(empty($active))
	$active = "dashboard";
?>

<div class="account-nav-title-wrap">
    <h2><?php the_title(); ?></h2>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="heading-font nav-link <?php echo (esc_attr($active) == 'dashboard')?'active':null?>" href="<?php echo StmUser::getProfileUrl()?>"><?php esc_html_e('Dashboard', 'motors')?></a>
        </li>
        <?php foreach (StmUser::get_account_link('account-navigation') as $item):?>
            <li class="nav-item">
                <a class="nav-link heading-font <?php echo (esc_attr($active) == $item['var'])?'active':null?>" href="<?php echo StmUser::getUrl($item['var'])?>"><?php  esc_html_e($item['title'], 'motors')?></a>
            </li>
        <?php endforeach;?>
    </ul>
</div>


