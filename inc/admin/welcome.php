<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'add_juiz_sps_welcome_page' ) ) {
	/**
	 * Create a page for the Welcome.
	 *
	 * @since   2.0.0
	 * @author  Geoffrey Crofte
	 */
	function add_juiz_sps_welcome_page() {
		// The hidden Welcome page
		add_submenu_page(
			null, // Makes it a third level page (hidden)
			__( 'Social Post Sharer', 'juiz-social-post-sharer' ),
			__( 'Social Post Sharer', 'juiz-social-post-sharer' ),
			'administrator',
			JUIZ_SPS_SLUG . '-welcome',
			'juiz_sps_welcome_page'
		);
	}
	add_action( 'admin_menu', 'add_juiz_sps_welcome_page' );
}

if ( ! function_exists( 'juiz_sps_welcome_page' ) ) {

	function juiz_sps_welcome_page() {
	?>
	<div id="juiz-sps" class="wrap welcome-page">

		<div class="jsps-main-content">
			<div class="jsps-main-header">
				<h1><i class="jsps-icon-share" role="presentation"></i>&nbsp;<?php echo JUIZ_SPS_PLUGIN_NAME; ?>&nbsp;<small>v.&nbsp;<?php echo JUIZ_SPS_VERSION; ?></small></h1>
			</div>

			<div class="jsps-main-body">
				
				<p class="juiz-sps-big">
					<?php printf( esc_html__( 'Welcome in version %s', 'juiz-social-post-sharer'), '<span class="jsps-version-number">' . JUIZ_SPS_VERSION . '</span>' ); ?>
				</p>

				<p class="juiz-sps-subtitle">
					<?php printf( esc_html__('The good way to make people share your content %swithout sacrificing%s your website performance!' , 'juiz-social-post-sharer'), '<strong>', '</strong>') ?>
				</p>


				<div class="jsps-row">
					<div class="jsps-col">
						<p class="jsps-h2" role="heading" aria-level="2"><?php esc_html_e('Responsive Skins' , 'juiz-social-post-sharer') ?></p>

						<p>
							<?php esc_html_e( 'The list of default skins proposed is growing up and the existing ones have being rebuilt with the fresh new brand colors.', 'juiz-social-post-sharer' ); ?>
						</p>
					</div>
					<div class="jsps-col">
						<p class="jsps-h2" role="heading" aria-level="2"><?php esc_html_e('Drag &amp; Drop Buttons' , 'juiz-social-post-sharer') ?></p>

						<p>
							<?php printf( esc_html__( 'You can now re-order your buttons by simply drag and drop those in the %ssettings page%s. Click on it to activate, drag to order!', 'juiz-social-post-sharer'), '<a href="' . admin_url( 'options-general.php?page=' . JUIZ_SPS_SLUG ) . '">', '</a>' ); ?>
						</p>
					</div>
					<div class="jsps-col">
						<p class="jsps-h2" role="heading" aria-level="2"><?php esc_html_e('Performant' , 'juiz-social-post-sharer') ?></p>
					</div>
				</div>
			
			</div>

		</div><!-- .jsps-main-content -->

		<script>
			document.querySelector('title').innerHTML = 'Welcome!';
		</script>
	</div><!-- .wrap -->

	<?php
	}
}