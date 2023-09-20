<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Adds the Summary Widget in the Admin Dashboard
 *
 * @return void
 * @author Nicolas Guitton
 * @author Geoffrey Crofte
 *
 * @since 2.4.0 Introduces the widget
 */
function nobs_shared_posts_summary_widget() {
    wp_add_dashboard_widget(
        'custom_help_widget',
        __('Nobs • Shared Posts', 'juiz-social-post-sharer'),
        'nobs_shared_posts_summary_output'
    );
}
add_action( 'wp_dashboard_setup', 'nobs_shared_posts_summary_widget');

if ( ! function_exists( 'nobs_shared_posts_summary_output' ) ) {
    
    /**
     * Generates the content of the Nobs Dashboard Widget, displaying counters
     * 
     * @return void
     * @author Nicolas Guitton
     * @author Geoffrey Crofte
     *
     * @since 2.4.0 Introduces the widget
     */
    function nobs_shared_posts_summary_output() {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_jsps_counters',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => '_jsps_counters',
                    'compare' => '!=',
                    'value'   => '',
                )
            ),
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            $sharedPosts = array();

            while ( $query->have_posts() ) {
                $query -> the_post();
                $counters = get_post_meta( get_the_ID(), '_jsps_counters', true );
                $totalCount = 0;

                if ( is_array( $counters ) ) {
                    foreach ( $counters as $counter ) {
                        $totalCount += $counter;
                    }
                    $sharedPosts[ get_the_ID() ] = $totalCount;
                }
            }

            arsort( $sharedPosts );
            $sharedPosts = array_slice( $sharedPosts, 0, 10, true );
            
            echo '<h3>' . __( 'Most shared posts', 'juiz-social-post-sharer') . '</h3>';

            if ( count( $sharedPosts ) > 0 ) {
                echo '<ol class="nobs-most-shared-list">';
                
                foreach ( $sharedPosts as $sharedPostId => $totalCount ) {
                    echo '<li class="nobs-most-shared-list-item">';
                    echo '<span class="nobs-most-shared-list-count">' . sprintf( _n( '%s share', '%s shares', $totalCount, 'text-domain' ), number_format_i18n( $totalCount ) ) . '</span>';
                    echo ' <span class="nobs-most-shared-list-dash">-</span> <a data-id="' . $sharedPostId . '" href="' . get_permalink($sharedPostId) . '" target="_blank" class="nobs-most-shared-list-item-link">' . get_the_title( $sharedPostId ) . '</a></li>';
                }
                
                echo '</ol>';
            } else  {
                echo '<p class="nobs-most-shared-empty">';
                printf(
                    __( 'You do not have any post shared at the moment, or the counters are disabled. You can activate the counters in the %sNobs Plugin Settings%s.' ),
                    '<a href="' . jsps_get_settings_url() . '">',
                    '</a>'
                );
                echo '</p>';
            }
        }
    }
}