<?php
if ( class_exists( 'Nobs_License_Manager_Client' ) ) {
	return;
}

class Nobs_License_Manager_Client {
	/**
	 * The API endpoint. Configured through the class's constructor.
	 *
	 * @var String  The API endpoint.
	 */
	private $api_endpoint;
	 
	/**
	 * The product id (slug) used for this product on the License Manager site.
	 * Configured through the class's constructor.
	 *
	 * @var int     The product id of the related product in the license manager.
	 */
	private $product_id;
	 
	/**
	 * The name of the product using this class. Configured in the class's constructor.
	 *
	 * @var int     The name of the product (plugin / theme) using this class.
	 */
	private $product_name;

	/**
	 * The text domain of the plugin or theme using this class.
	 * Populated in the class's constructor.
	 *
	 * @var String  The text domain of the plugin / theme.
	 */
	private $text_domain;
	 
	/**
	 * The type of the installation in which this class is being used.
	 *
	 * @var string  'theme' or 'plugin'.
	 */
	private $type;
	 
	/**
	 * @var string  The absolute path to the plugin's main file. Only applicable when using the
	 *              class with a plugin.
	 */
	private $plugin_file;

	/**
	 * Initializes the license manager client.
	 *
	 * @param $pct_data.id         string  The text id (uniqid of the product) of the product on the license manager site.
	 * @param $pct_data.name       string  The name of the product, used for menus.
	 * @param $pct_data.txtdomain  string  The text domain for translations.
	 * @param $pct_data.api_url    string  The URL to the license manager API (your license server)
	 * @param $pct_data.type       string  The type of project this class is being used in ('theme' or 'plugin')
	 * @param $pct_data.file       string  The full path to the plugin's main file (only for plugins)
	 */
	public function __construct( $pct_data = array() ) {
		
		// Store setup data
		$this->product_id = isset( $pct_data['id'] ) ? $pct_data['id'] : '';
		$this->product_name = isset( $pct_data['name'] ) ? $pct_data['name'] : '';
		$this->text_domain = isset( $pct_data['txtdomain'] ) ? $pct_data['txtdomain'] : 'juiz-social-post-sharer';
		$this->api_endpoint = isset( $pct_data['api_url'] ) ? $pct_data['api_url'] : '';
		$this->type = isset( $pct_data['type'] ) ? $pct_data['type'] : 'plugin';
		$this->plugin_file = isset( $pct_data['file'] ) ? $pct_data['file'] : '';

		// Some other actions if the API call is for a specific product.
		if ( $this->product_name !== '' ) {
			// Add the menu screen for inserting license information
			add_action( 'admin_menu', array( $this, 'add_license_settings_page' ) );
			add_action( 'admin_init', array( $this, 'add_license_settings_fields' ) );

			// Add a nag text for reminding the user to save the license information
			add_action( 'admin_notices', array( $this, 'show_admin_notices' ) );

			// Check for updates (for themes or plugins)
			// pre_set_site_transient_update_themes or pre_set_site_transient_update_plugins
			add_filter( 'pre_set_site_transient_update_' . $type . 's', array( $this, 'check_for_update' ) );

			// Showing plugin information
			if ( $type == 'plugin') {
				add_filter( 'plugins_api', array( $this, 'plugins_api_handler' ), 10, 3 );
			}
		}
	}

	/**
	 * Creates the settings items for entering license information (email + license key).
	 */
	public function add_license_settings_page() {
		$title = sprintf( __( '%s License', $this->text_domain ), $this->product_name );
	 
		add_options_page(
			$title,
			$title,
			'read',
			$this->get_settings_page_slug(),
			array( $this, 'render_licenses_menu' )
		);
	}
	 
	/**
	 * Creates the settings fields needed for the license settings menu.
	 */
	public function add_license_settings_fields() {
		$settings_group_id = $this->product_id . '-license-settings-group';
		$settings_section_id = $this->product_id . '-license-settings-section';
	 
		register_setting( $settings_group_id, $this->get_settings_field_name() );
	 
		add_settings_section(
			$settings_section_id,
			__( 'License', $this->text_domain ),
			array( $this, 'render_settings_section' ),
			$settings_group_id
		);
	 
		add_settings_field(
			$this->product_id . '-license-email',
			__( 'Email address', $this->text_domain ),
			array( $this, 'render_email_settings_field' ),
			$settings_group_id,
			$settings_section_id
		);
	 
		add_settings_field(
			$this->product_id . '-license-key',
			__( 'License key', $this->text_domain ),
			array( $this, 'render_license_key_settings_field' ),
			$settings_group_id,
			$settings_section_id
		);
	}

	/**
	 * Renders the description for the settings section.
	 */
	public function render_settings_section() {
		_e( 'Insert your license information to enable updates.', $this->text_domain);
	}
	 
	/**
	 * Renders the settings page for entering license information.
	 */
	public function render_licenses_menu() {
		$title = sprintf( __( '%s License', $this->text_domain ), $this->product_name );
		$settings_group_id = $this->product_id . '-license-settings-group';
	 
		?>
			<div class="wrap">
				<form action='options.php' method='post'>
	 
					<h1><?php echo $title; ?></h1>
					<div class="card">
					<?php
						settings_fields( $settings_group_id );
						do_settings_sections( $settings_group_id );
						submit_button();
					?>
					</div>
	 
				</form>
			</div>
		<?php
	}
	 
	/**
	 * Renders the email settings field on the license settings page.
	 */
	public function render_email_settings_field() {
		$settings_field_name = $this->get_settings_field_name();
		$options = get_option( $settings_field_name );
		?>
			<input type='text' name='<?php echo $settings_field_name; ?>[email]'
			   value='<?php echo $options['email']; ?>' class='regular-text'>
		<?php
	}
	 
	/**
	 * Renders the license key settings field on the license settings page.
	 */
	public function render_license_key_settings_field() {
		$settings_field_name = $this->get_settings_field_name();
		$options = get_option( $settings_field_name );
		?>
			<input type='text' name='<?php echo $settings_field_name; ?>[license_key]'
			   value='<?php echo $options['license_key']; ?>' class='regular-text'>
		<?php
	}

	/**
	 * @return string   The name of the settings field storing all license manager settings.
	 */
	protected function get_settings_field_name() {
		return $this->product_id . '-license-settings';
	}
	 
	/**
	 * @return string   The slug id of the licenses settings page.
	 */
	protected function get_settings_page_slug() {
		return $this->product_id . '-licenses';
	}

	/**
	 * If the license has not been configured properly, display an admin notice.
	 */
	public function show_admin_notices() {
		$options = get_option( $this->get_settings_field_name() );
	 
		if ( !$options || ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ||
			$options['email'] == '' || $options['license_key'] == '' ) {
	 
			$msg = __( 'Please enter your email and license key to enable updates to %s.', $this->text_domain );
			$msg = sprintf( $msg, '<strong>' . $this->product_name . '</strong>' );
			?>
				<div class="notice notice-warning is-dismissible">
					<p>
						<?php echo $msg; ?>
					</p>
	 
					<p>
						<a class="button" href="<?php echo admin_url( 'options-general.php?page=' . $this->get_settings_page_slug() ); ?>">
							<?php _e( 'Complete the setup', $this->text_domain ); ?>
						</a>
					</p>
				</div>
			<?php
		}
	}

	//
	//
	// API HELPER FUNCTIONS
	//
	//
	 
	/**
	 * Makes a call to the Juiz License Manager API.
	 *
	 * @param $method   String  The API action to invoke on the license manager site
	 * @param $params   array   The parameters for the API call
	 * @return          array   The API response
	 */
	private function call_api( $action, $params ) {
		$url = $this->api_endpoint . '/' . $action;
	 
		// Append parameters for GET request
		$url .= '?' . http_build_query( $params );
	 
		// Send the request
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
			return false;
		}
			 
		$response_body = wp_remote_retrieve_body( $response );
		$result = json_decode( $response_body );
		 
		return $result;
	}

	/**
	 * Checks the API response to see if there was an error.
	 *
	 * @param $response mixed|object    The API response to verify
	 * @return bool     True if there was an error. Otherwise false.
	 */
	private function is_api_error( $response ) {
		if ( $response === false ) {
			return true;
		}
	 
		if ( ! is_object( $response ) ) {
			return true;
		}
	 
		if ( isset( $response->error ) ) {
			return true;
		}
	 
		return false;
	}

	/**
	 * Calls the License Manager API to get the license information for the
	 * current product.
	 *
	 * @return object|bool   The product data, or false if API call fails.
	 */
	public function get_license_info() {
		$options = get_option( $this->get_settings_field_name() );

		if ( ! isset( $options['email'] ) || ! isset( $options['license_key'] ) ) {
			// User hasn't saved the license to settings yet. No use making the call.
			return false;
		}

		$info = $this->call_api(
			'info',
			array(
				'p' => $this->product_id,
				'e' => $options['email'],
				'l' => $options['license_key']
			)
		);

		return $info;
	}

	/**
	 * Calls the License Manager API to get the last Skins Available.
	 *
	 * @return  object|bool The products list, or false if API call fails.
	 */
	public function get_all_products() {
		$list = $this->call_api(
			'all',
			array()
		);

		return $list;
	}

	/**
	 * Checks the license manager to see if there is an update available for this theme.
	 *
	 * @return object|bool  If there is an update, returns the license information.
	 *                      Otherwise returns false.
	 */
	public function is_update_available() {
		$license_info = $this->get_license_info();

		if ( $this->is_api_error( $license_info ) ) {
			return false;
		}

		if ( version_compare( $license_info->version, $this->get_local_version(), '>' ) ) {
			return $license_info;
		}

		return false;
	}

	/**
	 * @return string   The theme / plugin version of the local installation.
	 */
	private function get_local_version() {
		if ( $this->type == 'theme' ) {
			$theme_data = wp_get_theme();
			return $theme_data->Version;
		} else {
			$plugin_data = get_plugin_data( $this->plugin_file, false );
			return $plugin_data['Version'];
		}
	}

	/**
	 * The filter that checks if there are updates to the theme or plugin
	 * using the Juiz License Manager API.
	 *
	 * @param $transient    mixed   The transient used for WordPress
	 *                              theme / plugin updates.
	 *
	 * @return mixed        The transient with our (possible) additions.
	 */
	public function check_for_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
	 
		$info = $this->is_update_available();
		
		if ( $info !== false ) {
			
			if ( $this->type == 'theme' ) {
				// Theme update
				$theme_data = wp_get_theme();
				$theme_slug = $theme_data->get_template();
	 
				$transient->response[$theme_slug] = array(
					'new_version' => $info->version,
					'package' => $info->package_url,
					'url' => $info->description_url
				);
			} else {
				$plugin_slug = plugin_basename( $this->plugin_file );
				$transient->response[$plugin_slug] = (object) array(
					'new_version' => $info->version,
					'package' => $info->package_url,
					'slug' => $plugin_slug
				);
			}
		}
	 
		return $transient;
	}

	/**
	 * A function for the WordPress "plugins_api" filter. Checks if
	 * the user is requesting information about the current plugin and returns
	 * its details if needed.
	 *
	 * This function is called before the Plugins API checks
	 * for plugin information on WordPress.org.
	 *
	 * @param $res      bool|object The result object, or false (= default value).
	 * @param $action   string      The Plugins API action. We're interested in 'plugin_information'.
	 * @param $args     array       The Plugins API parameters.
	 *
	 * @return object   The API response.
	 */
	public function plugins_api_handler( $res, $action, $args ) {
		if ( $action == 'plugin_information' ) {
	 
			// If the request is for this plugin, respond to it
			if ( isset( $args->slug ) && $args->slug == plugin_basename( $this->plugin_file ) ) {
				$info = $this->get_license_info();
	 
				$res = (object) array(
					'name' => isset( $info->name ) ? $info->name : '',
					'version' => $info->version,
					'slug' => $args->slug,
					'download_link' => $info->package_url,
	 
					'tested' => isset( $info->tested ) ? $info->tested : '',
					'requires' => isset( $info->requires ) ? $info->requires : '',
					'last_updated' => isset( $info->last_updated ) ? $info->last_updated : '',
					'homepage' => isset( $info->description_url ) ? $info->description_url : '',
	 
					'sections' => array(
						'description' => $info->description,
					),
	 
					'banners' => array(
						'low' => isset( $info->banner_low ) ? $info->banner_low : '',
						'high' => isset( $info->banner_high ) ? $info->banner_high : ''
					),
	 
					'external' => true
				);
	 
				// Add change log tab if the server sent it
				if ( isset( $info->changelog ) ) {
					$res['sections']['changelog'] = $info->changelog;
				}
	 
				return $res;
			}
		}
	 
		// Not our request, let WordPress handle this.
		return false;
	}
}
