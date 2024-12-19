<?php
$items = array(
	array(
		'title' => ( STM_Theme_Support::get_developer_access_link() ) ? 'Developer Access Enabled' : 'Generate Developer Access',
		'desc'  => ( STM_Theme_Support::get_developer_access_link() ) ? 'Click the button to review the access link' : 'Generates admin access to the website.',
		'icon'  => 'key',
		'url'   => '#',
		'id'    => 'generate-dev-access-btn',
		'class' => '',
		'data'  => ( STM_Theme_Support::get_developer_access_link() ) ? 'created' : '',
	),
	array(
		'title' => 'Facebook Community',
		'desc'  => 'Share your experience with theme users',
		'icon'  => 'facebook',
		'url'   => STM_FACEBOOK_COMMUNITY,
		'id'    => '',
		'class' => '',
	),
	array(
		'title' => 'Documentation',
		'desc'  => 'Detailed guide about all theme functionalities.',
		'icon'  => 'documentation',
		'url'   => STM_DOCUMENTATION_URL,
		'id'    => '',
		'class' => '',
	),
	array(
		'title' => 'Submit a ticket',
		'desc'  => 'Get premium customer support via our ticket system.',
		'icon'  => 'ticket',
		'url'   => STM_SUBMIT_A_TICKET,
		'id'    => '',
		'class' => '',
	),
	array(
		'title' => 'Video Tutorials',
		'desc'  => 'Video materials describing theme features',
		'icon'  => 'youtube',
		'url'   => STM_VIDEO_TUTORIALS,
		'id'    => '',
		'class' => '',
	),
);
?>

<div class="stm-admin-support_community">
	<div class="__top">
		<h3>Support and Community</h3>
		<!--<div class="__top-info">
			<span class="__info"><i class="stmadmin-icon-warning-filled"></i> Your support has expired</span>
			<a href="#">Renew Support</a>
		</div>-->
	</div>
	<div class="list-wrap">
		<ul>
		<?php
		foreach ( $items as $item ) :
			if ( ! empty( $item['url'] ) ) :
				$id    = ( ! empty( $item['id'] ) ) ? "id={$item['id']}" : '';
				$class = ( ! empty( $item['class'] ) ) ? "class={$item['class']}" : '';
				$data  = ( ! empty( $item['data'] ) ) ? "data-gda={$item['data']}" : '';
				?>
				<li>
					<a
						href="<?php echo esc_url( $item['url'] ); ?>"
						<?php echo esc_attr( $id . ' ' . $class . ' ' . $data ); ?>
						target="_blank">
						<i class="stmadmin-icon-<?php echo esc_attr( $item['icon'] ); ?>"></i>
						<div class="list-info">
							<h4><?php echo esc_attr( $item['title'] ); ?></h4>
							<span><?php echo esc_attr( $item['desc'] ); ?></span>
						</div>
					</a>
				</li>
				<?php
			endif;
		endforeach;
		?>
		</ul>
	</div>
</div>
