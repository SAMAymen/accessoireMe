<?php
$devAccessLink = STM_Theme_Support::get_developer_access_link();
$devAccessLinkPreLoader = ( !STM_Theme_Support::get_developer_access_link() ) ? 'preloader' : '';
?>
<div class="developer_access_popup_wrap ">
    <div class="developer_access_bg"></div>
    <div class="developer_access_window">
        <h4>Developer access Link</h4>
        <div class="link-wrapper <?php echo esc_attr( $devAccessLinkPreLoader ); ?>">
            <textarea class="dev_access_link"><?php echo ! empty( $devAccessLink ) ? urldecode( $devAccessLink ) : ''; ?></textarea>
        </div>
        <div class="btns-wrapp">
            <a href="#" id="copy_developer_access_link">Copy link</a>
            <a href="#" id="revoke_developer_access">Revoke Developer Access</a>
        </div>
        <div class="close-gda">
            <i class="stmadmin-icon-cross"></i>
        </div>
    </div>
</div>
