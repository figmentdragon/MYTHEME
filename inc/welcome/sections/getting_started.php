	<div class="theme-steps-list-wrap two-col">

		<div class="theme-steps col">
			<div class="step-1-right recommend-col">
				<h3><?php echo esc_html__('Links to Customizer Settings', 'MYTHEME'); ?></h3>
				<div class="item-wrap">
				<?php
				$data    = array(
				array(
					'icon' => 'dashicons-format-gallery',
					'text' => __( 'Upload Logo', 'MYTHEME' ),
					'link' => add_query_arg( array( 'autofocus[section]' => 'title_tagline' ), admin_url( 'customize.php' ) ),
				),
				array(
					'icon' => 'dashicons-images-alt2',
					'text' => __( 'Scroll Page section', 'MYTHEME' ),
					'link' => add_query_arg( array( 'autofocus[panel]' => 'MYTHEME_panel_scroll_page_sections' ), admin_url( 'customize.php' ) ),
				),
				array(
					'icon' => 'dashicons-format-aside',
					'text' => __( 'Blog Settings', 'MYTHEME' ),
					'link' => add_query_arg( array( 'autofocus[section]' => 'MYTHEME_blog_settings' ), admin_url( 'customize.php' ) ),
				),
				array(
					'icon' => 'dashicons-admin-appearance',
					'text' => __( 'Template Color', 'MYTHEME' ),
					'link' => add_query_arg( array( 'autofocus[section]' => 'colors' ), admin_url( 'customize.php' ) ),
				),
				array(
					'icon' => 'dashicons-businessperson',
					'text' => __( 'Portfolio Options', 'MYTHEME' ),
					'link' => add_query_arg( array( 'autofocus[section]' => 'MYTHEME_portfolio_settings' ), admin_url( 'customize.php' ) ),
				),

			); 
			foreach ( $data as $customizer_item ) {
				 ?>
				<div class="ti-customizer-item ">
					<i class="dashicons <?php echo esc_attr( $customizer_item['icon']); ?> "></i><a href="<?php echo esc_url( $customizer_item['link'] ); ?>"><?php echo wp_kses_post( $customizer_item['text'] ); ?></a>
				</div>
			<?php } ?>

			</div>
		</div>
		<div class="step-1-left">
			<h3><?php echo esc_html__('Step 1 - Checkout starter sites (Demos) ', 'MYTHEME'); ?></h3>
			<p><?php /* translators: %s : Theme Name */ printf(esc_html__('%1$s now comes with a sites library with 1 starter sites to pick from. You can check theme out and decide which one to start with. However you can decide not to use any one of them and start building your site from scratch.', 'MYTHEME'),$this->theme_name); ?></p>
			<a class="nav-tab demo_import button" href="<?php echo esc_url(admin_url('/themes.php?page=welcome-page#demo_import')); ?>"><?php echo esc_html__('See Demos', 'MYTHEME'); ?></a>
		</div>
		
	</div>

	<div class="theme-steps col">
		<h3><?php echo esc_html__('Step 2 - Import demo of your choice ', 'MYTHEME'); ?></h3>
		<p><?php echo esc_html__('Once you chose one of the available starter sites (demos) - you can install it. Please be noted that once you install the demo, it will install all the required plugins too. It is not recommended to install demo on your existing content. A fresh WordPress installation would be required to install demo to replicate demo content exactly. ', 'MYTHEME'); ?></p>
		<a class=" nav-tab demo_import button" href="<?php echo esc_url(admin_url('/themes.php?page=welcome-page#demo_import')); ?>"><?php echo esc_html__('Install Demo', 'MYTHEME'); ?></a>
	</div>
	<div class="theme-steps col">
		<h3><?php echo esc_html__('Step 3 - Start editing the demo content and making your site! ', 'MYTHEME'); ?></h3>
		<p><?php echo esc_html__('Once you install the demo, you can start editing the content, replacing images etc.', 'MYTHEME'); ?></p>
	</div>
	<div class="theme-steps col">
		<h3><?php echo esc_html__('Step 4 - You \'re done! ', 'MYTHEME'); ?></h3>
		<p><?php echo esc_html__('Go live with the website and get some rest', 'MYTHEME'); ?></p>
	</div>
</div>