<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Cheatin’ uh?' );
}

// Nobs Shortcode rendering
if ( function_exists( 'register_block_type' ) ) {
    
    // Hook server side rendering into render callback
    register_block_type(
        'nobs/share-buttons', [
            'render_callback' => 'nobs_share_buttons_block_callback',
            'attributes'  => array(
                'counters' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'buttons'    => array(
                    'type'  => 'array',
                    'default'   => [],
                    'items'   => [
                        'type' => 'string',
                    ],
                ),
                'network'    => array(
                    'type'  => 'string',
                    'default'   => '',
                ),
                'url' => array(
                    'type' => 'string',
                    'default' => ''
                )
            ),
        ]
    );
}

function nobs_share_buttons_block_callback( $attributes ) {

    include_once( dirname( JUIZ_SPS_FILE ) . '/inc/front/enqueues.php'  );
    include_once( dirname( JUIZ_SPS_FILE ) . '/inc/front/buttons.php'   );
    include_once( dirname( JUIZ_SPS_FILE ) . '/inc/front/shortcode.php' );
    
    $buttons = '';
    $juiz_sps_options  = jsps_get_option();
    $juiz_sps_networks = $juiz_sps_options['juiz_sps_networks'];
    $order = ! empty( $networks ) ? $networks : $juiz_sps_options['juiz_sps_order'];
    $sorted_networks = jsps_get_displayable_networks( $juiz_sps_networks, $order );

    foreach( $sorted_networks as $k => $v ) {
        // If not visible, got to next item.
        if ( ! isset( $v['visible'] ) || ( isset( $v['visible'] ) && $v['visible'] === 0 ) ) {
            continue;
        }

        $buttons .= $k . ',';
    }
    $buttons = trim($buttons, ',');

    $attributes = array(
        'buttons'   => $buttons,
        'counters'  => $juiz_sps_options['juiz_sps_counter'],
        'current'   => 1,
        'url'       => NULL
    );
    
    $buttons = sc_4_jsps( $attributes );
    
    return $buttons;
}
