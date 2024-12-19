<?php
/**
 * Account my listing
 *
 * Template can be modified by copying it to yourtheme/ulisting/account/my-listing.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.5.7
 */

use uListing\Classes\StmListingTemplate;
use uListing\Classes\StmUser;
use uListing\Classes\UlistingUserRole;

wp_enqueue_script( 'ulisting-my-listing', ULISTING_URL . '/assets/js/frontend/ulisting-my-listing.js', array( 'vue' ), ULISTING_VERSION, true );

$limit = 9;
$sections = [];
$view_type = "list";
$default_listing_type = null;
$upload = wp_get_upload_dir();
$query_var = explode( '/', get_query_var( ulisting_page_endpoint() ) );
$data['query_var'] = $query_var;
$page = isset( $query_var[0] ) ? intval( $query_var[0] ) : 0;
$data['user_id'] = get_current_user_id();
$params = array( 'limit' => $limit, 'offset' => ( $page > 1 ) ? ( ( $page - 1 ) * $limit ) : 0 );
$deleteListings = get_option( 'allow_delete_listings' );
$deleteListings = strval( $deleteListings ) === 'true';
if ( isset( $_GET['order'] ) ) $params['order'] = esc_attr( $_GET['order'] );

if ( isset( $_GET['order_by'] ) ) $params['order_by'] = esc_attr( $_GET['order_by'] );
?>

<?php StmListingTemplate::load_template( 'account/navigation', [ 'user' => $user ], true ); ?>

    <div id="ulisting_my_listing" class="custom-panel p-t-30 p-b-30 ulisting_my_listing">
<?php
$i = 0;
$listing_types = uListing_all_listing_types();
?>
    <div class="stm-my-listings-sidebar">
		<?php if ( !empty( $listing_types ) ) : ?>
            <div class="ulisting-user-listings">
                <h4><?php echo esc_html__( 'Transport Type', 'motors' ); ?></h4>
				<?php foreach ( $listing_types as $index => $listing_type ): ?>
					<?php
					wp_enqueue_style( 'ulisting_builder_stytle_' . "ulisting_item_card_" . $index . "_list", $upload['baseurl'] . "/ulisting/css/" . "ulisting_item_card_" . $index . "_list" . ".css" );
					$count = $user->getListings( true, [ 'listing_type_id' => $index ], '' );

					if ( $i === 0 ) {
						$default_listing_type = isset( $query_var[1] ) ? intval( $query_var[1] ) : $index;
						$data['default_type'] = $default_listing_type;
					}

					$i++;
					?>

					<?php if ( $count > 0 ): ?>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" v-bind:checked="listing_type === <?php echo esc_attr( $index ) ?>"
                                       v-on:change="changeType(<?php echo esc_attr( $index ) ?>)"
                                       class="form-check-input"
                                       name="listing_types"><?php echo esc_attr( $listing_type ); ?>
                            </label>
                        </div>
					<?php endif; ?>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
		<?php foreach ( $listing_types as $type_index => $listing_type ): ?>
            <div class="ulisting-my-listing-sidebar" v-if="listing_type == <?php echo esc_attr( $type_index ); ?>">
                <h4><?php echo esc_html__( 'Sort by status', 'motors' ); ?></h4>
                <ul class="my-listing-sidebar-wrap">
                    <li @click="change('all')" :class="{'is-active': isActive === 'all'}"
                        class="my-listing-sidebar-item heading-font">
                        <span><?php echo __( 'All', 'motors' ); ?></span><span>(<?php echo esc_html( $user->getListings( true, [ 'listing_type_id' => $type_index ], '' ) ) ?>
                            )</span></li>
                    <li @click="change('publish')" :class="{'is-active': isActive === 'publish'}"
                        class="my-listing-sidebar-item heading-font">
                        <span><?php echo __( 'Publish', 'motors' ); ?></span><span>(<?php echo esc_html( $user->getListings( true, [ 'listing_type_id' => $type_index ], 'publish' ) ) ?>
                            )</span></li>
                    <li @click="change('pending')" :class="{'is-active': isActive === 'pending'}"
                        class="my-listing-sidebar-item heading-font">
                        <span><?php echo __( 'Pending', 'motors' ); ?></span><span>(<?php echo esc_html( $user->getListings( true, [ 'listing_type_id' => $type_index ], 'pending' ) ) ?>
                            )</span></li>
                    <li @click="change('draft')" :class="{'is-active': isActive === 'draft'}"
                        class="my-listing-sidebar-item heading-font"><span><?php echo __( 'Draft', 'motors' ); ?></span><span>(<?php echo esc_html( $user->getListings( true, [ 'listing_type_id' => $type_index ], 'draft' ) ) ?>
                            )</span></li>
                    <li @click="change('trash')" :class="{'is-active': isActive === 'trash'}"
                        class="my-listing-sidebar-item heading-font"><span><?php echo __( 'Trash', 'motors' ); ?></span><span>(<?php echo esc_html( $user->getListings( true, [ 'listing_type_id' => $type_index ], 'trash' ) ) ?>
                            )</span></li>
                </ul>
            </div>
		<?php endforeach; ?>
    </div>
    <div class="user-listings-list">
<?php

        $status = '';
        $capabilities = null;

        $stmUser = new StmUser(get_current_user_id());
        $userRoles = new UlistingUserRole();

        foreach ($stmUser->roles as $user_role_value) {
            foreach ($userRoles->roles as $role_key => $role) {
                if ($role_key === $user_role_value)
                    $capabilities = $role['capabilities'];
            }
        }

        if ($capabilities && (isset($capabilities['listing_moderation']) && $capabilities['listing_moderation'])) {
            $status = true;
        }
foreach ( $listing_types as $id => $value ):
	?>
    <div class="stm-row" v-if="listing_type == <?php echo esc_attr( $id ) ?> && hasAccess">
        <template v-for="(listing, index) in listings[listing_type]">
            <div class="stm-col-12" v-if="isActive === 'all' || isActive === listing.status">
                <div class="stm-row">
                    <div class="stm-col-12">
                        <div class="my-listing-item-wrap">
                            <div v-html="listing.html"></div>
                            <div class="actions-wrap">
                                <button class="btn btn-primary w-full m-t-15 heading-font"
                                        @click="()=>panel_feature_switch(listing.id)"><?php _e( 'Featured', 'motors' ) ?></button>
                                <a class="btn btn-success w-full"
                                   v-bind:href="'<?php echo ulisting_get_page_link( 'add_listing' ) ?>?edit=' + listing.id"><i
                                            class="fas fa-pencil-alt"></i></a>
                                <div v-if="listing.status === 'publish'" class="listing-status-name published"
                                     v-bind:class="{'current': listing.active}">
									<div class="status-actions" @click.prevent="changeStatus(listing.id, 'draft')">
										<span><?php esc_attr_e( 'Unpublish', 'motors' ); ?></span>
									</div>
                                </div>
                                <div v-else-if="listing.status === 'draft'" class="listing-status-name drafted"
                                     v-bind:class="{'current': listing.active}">
                                    <div class="status-actions" @click.prevent="changeStatus(listing.id, 'pending')">
                                        <span><?php esc_attr_e( 'Publish', 'motors' ); ?></span>
                                    </div>
                                </div>
								<?php if ( $deleteListings ): ?>
                                    <div class="delete-actions" @click.prevent="deleteListing(listing.id)">
                                        <span><?php esc_attr_e( 'Delete', 'motors' ); ?></span>
                                    </div>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ulisting-account-my-listing-feature-plan" v-if="feature_panel == listing.id">
                    <p v-if="loading"><?php echo __( 'Loading', 'motors' ); ?></p>
                    <div v-if="!loading">
                        <div class="stm-row">
                            <div v-for="plan in feature_plans" class="stm-col-6">
                                <div class="card-body">
                                    <div class="status-wrapp">
                                        <span v-if="plan.id == feature_plan_select" class="badge badge-success">Active</span>
                                        <span v-if="plan.id == selected_plan" class="badge badge-success">Select</span>
                                    </div>
                                    <h5 class="card-title">{{plan.name}}</h5>
                                    <p v-if="plan.payment_type == 'subscription'" class="card-text">
                                        {{plan.feature_limit}} / {{plan.use_feature_limit}}</p>
                                    <p v-if="plan.payment_type == 'one_time'" class="card-text"> Limit :
                                        {{plan.feature_limit}}</p>
                                    <v-timer
                                            v-else-if="listing.listing_info"
                                            inline-template
                                            :starttime="moment.utc(listing.listing_info.created_date).local().format('MM DD YYYY h:mm:ss')"
                                            :endtime="moment.utc(listing.listing_info.expired_date).local().format('MM DD YYYY h:mm:ss')"
                                            trans='{
                                                             "day":"d",
                                                             "hours":"h",
                                                             "minutes":"m",
                                                             "seconds":"s",
                                    "expired":"<?php esc_attr_e( 'Promotion over.', 'motors' ); ?>",
                                    "running":"<?php esc_attr_e( 'Promotion ends:', 'motors' ); ?>",
                                    "upcoming":"<?php esc_attr_e( 'Promotion will start:', 'motors' ); ?>",
                                                             "status": {
                                    "expired":"<?php esc_attr_e( 'Expired', 'motors' ); ?>",
                                    "running":"<?php esc_attr_e( 'Running', 'motors' ); ?>",
                                    "upcoming":"<?php esc_attr_e( 'Future', 'motors' ); ?>"
                                                               }}'>
                                        <div class="promoted-count-box">
                                            <div class="promoted-count-title">{{ message }}</div>
                                            <div class="promoted-count">
                                                <span v-if="days != 0">{{ days }}{{ wordString.day }}</span>
                                                <span v-if="hours != 0">{{ hours }}{{ wordString.hours }}</span>
                                                <span>{{ minutes }}{{ wordString.minutes }}</span>
                                            </div>
                                        </div>

                                    </v-timer>
                                    <button v-if="!feature_plan_select_is_one_tome"
                                            @click="select_feature_plan(plan)" class="btn btn-primary">
										<?php echo __( 'select', 'motors' ) ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <ul v-if="errors">
                            <li v-for=" (val, key) in errors">{{val}}</li>
                        </ul>
                        <p v-if="message">{{message}}</p>
                        <span v-if="loading_save"><?php echo __( 'Loading...', 'motors' ) ?></span>
                        <button v-if="!loading_save" @click="save(listing.id, listing.status)" class="btn btn-success">
							<?php _e( "Save", "motors" ) ?>
                        </button>
                    </div>
                </div>
            </div>
    </div>

    </div>
    </template>
    </div>
<?php endforeach; ?>
    <!--<div class="stm-justify-content-center stm-add-btn-wrap" v-if="hasAccess || hasFail">
                <a class="btn btn-success heading-font" href="<?php /*echo ulisting_get_page_link( 'add_listing' ) */ ?>"> <?php /*_e( '+Add listing', 'motors' ) */ ?> </a>
            </div>-->
    <div class="stm-row stm-justify-content-center">
        <div class="stm-col-4" v-if="hasFail">
            <p class="m-t-5"><?php echo __( "No listings found", "motors" ) ?></p>
        </div>
    </div>
    <div v-if="!preLoader" class="stm-row stm-justify-content-center" style="margin: 10px 0">
        <div class="stm-spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    </div>
<?php
$data['pagination_settings'] = array( 'maxPagesToShow' => 8, 'class' => 'nav nav-pills', 'item_class' => 'nav-item', 'link_class' => 'nav-link', );
?>
<?php foreach ( $listing_types as $id => $value ): ?>
    <template v-if="listing_type == <?php echo esc_attr( $id ) ?> && hasAccess">
        <div class="stm-justify-content-center" v-html="paginator[listing_type]"></div>
    </template>
<?php endforeach; ?>
    </div>
<?php
wp_add_inline_script( 'ulisting-my-listing', "var ulisting_my_listing_data = json_parse('" . ulisting_convert_content( json_encode( $data ) ) . "');", 'before' );