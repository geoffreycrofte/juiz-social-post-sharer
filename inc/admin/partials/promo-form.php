<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

function jsps_promo_form() {
	/**
	 * Decides if it displays promotion sections within the admin, or not.
	 * 
	 * @hook jsps_admin_show_promotion
	 * 
	 * @since  2.0.0 First version
	 * 
	 * @param  {boolean} $show_promo=true   `true` or `false` weither you want to display promo or not
	 * @return {boolean}                    Returns the value of `$show_promo`
	 *
	 */
	if ( apply_filters( 'jsps_admin_show_promotion', true ) ) {

		$uniqid = '_' . uniqid();
?>
<div id="jsps-shop-promotion<?php echo $uniqid; ?>" class="jsps-shop-promotion">
	<p class="jsps-shop-promo-text">
		<span class="jsps-promo-big-text">
			<?php _e( 'New Skin Shop', 'juiz-social-post-sharer'); ?>
		</span>
		<span class="jsps-promo-text">
			<?php _e( 'Free &amp; Premium Button Skins', 'juiz-social-post-sharer' ); ?>
		</span>
	</p>
	<div class="jsps-shop-promo-form">
		<!-- Begin Mailchimp Signup Form -->
		<div id="mc_embed_signup<?php echo $uniqid; ?>">
			<!-- form -->
			<div id="mc_embed_signup_scroll<?php echo $uniqid; ?>">
				<div class="mc-field-group">
					<label for="mce-EMAIL<?php echo $uniqid; ?>" form="mc-embedded-subscribe-form">
						<?php _e( 'Soon! Be the first to know', 'juiz-social-post-sharer' ); ?>
						<span><?php _e( 'Email Address', 'juiz-social-post-sharer' ); ?></span>
					</label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL<?php echo $uniqid; ?>" form="mc-embedded-subscribe-form">
				</div>

				<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_5339b8dfa2b000a82251effc3_c9c0f762f1" tabindex="-1" value=""></div>

				<div class="mc-submit">
					<input type="hidden" value="Admin-Skin-Shop" name="CAMPAIGN" id="mce-CAMPAIGN<?php echo $uniqid; ?>" form="mc-embedded-subscribe-form">
					
					<button form="mc-embedded-subscribe-form" type="submit" name="subscribe" id="mc-embedded-subscribe<?php echo $uniqid; ?>" class="button"><img src="<?php echo JUIZ_SPS_PLUGIN_ASSETS; ?>img/icon-send.svg" alt="<?php echo esc_attr( __('Subscribe', 'juiz-social-post-sharer') ); ?>" title="<?php echo esc_attr( __('Subscribe', 'juiz-social-post-sharer') ); ?>" width="24" height="24" /></button>
				</div>
			</div>
			<!-- /form -->
		</div>
		<!--End mc_embed_signup-->

	</div>

</div>
<?php
	}
}
