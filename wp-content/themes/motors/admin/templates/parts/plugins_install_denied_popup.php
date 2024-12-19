<div class="demo_not_installed_popup">
    <div class="dni_popup">
        <div class="icon-wrap">
            <i class="stmadmin-icon-lock"></i>
        </div>
        <h4>
			<?php if ( empty( STM_Theme_Info::get_activation_token() ) ) : ?>
                <a href="<?php echo add_query_arg( array( 'page' => 'stm-admin' ), get_admin_url() . 'admin.php' ); ?>">Activate
                    the theme first!</a>
			<?php else : ?>
                <a href="<?php echo add_query_arg( array( 'page' => 'stm-admin-demos' ), get_admin_url() . 'admin.php' ); ?>">Select
                    a Demo for import first</a>
			<?php endif; ?>
        </h4>
        <div class="desc">
			<?php if ( empty( STM_Theme_Info::get_activation_token() ) ) : ?>
                Please activate your copy of the theme to unlock this feature.
			<?php else : ?>
                Please select the desired theme layout, and our demo import tool will install all the required plugins for you.
			<?php endif; ?>
        </div>
    </div>
</div>