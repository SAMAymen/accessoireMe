<!--Post thumbnail-->
<?php if ( has_post_thumbnail() ): ?>
    <div class="interview-top">
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
        </div>

        <div class="top-data">
            <div class="container">
                <div class="top-label heading-font">
                    <?php echo esc_html__('Interview', 'motors'); ?>
                </div>
                <h1><?php the_title(); ?></h1>
                <div class="excerpt">
                   <span class="quote heading-font">“</span><?php the_excerpt(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>