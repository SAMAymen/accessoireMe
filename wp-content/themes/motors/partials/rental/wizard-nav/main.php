<div class="container">
    <div class="stm_wizard_title heading-font">
        <?php esc_html_e('Reservation', 'motors'); ?>
    </div>
    <div class="row">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4 col-sm-12">
                <div class="stm_nav_wizard_step stm_nav_wizard_step_<?php echo intval($i); ?>">
                    <?php get_template_part('partials/rental/wizard-nav/nav-step', $i); ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>