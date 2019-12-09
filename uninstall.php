<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

delete_option( 'juiz_SPS_settings' );
delete_site_option( 'juiz_SPS_settings' );