<?php

class IndeedJobsShortcode {
	private $indeed_jobs_shortcode_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'indeed_jobs_shortcode_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'indeed_jobs_shortcode_page_init' ) );
	}

	public function indeed_jobs_shortcode_add_plugin_page() {
		add_menu_page(
			'Indeed Jobs Shortcode', // page_title
			'Indeed Jobs Shortcode', // menu_title
			'manage_options', // capability
			'indeed-jobs-shortcode', // menu_slug
			array( $this, 'indeed_jobs_shortcode_create_admin_page' ), // function
			'dashicons-admin-generic'
		);
	}

	public function indeed_jobs_shortcode_create_admin_page() {
		$this->indeed_jobs_shortcode_options = get_option( 'indeed_jobs_shortcode_option_name' ); ?>

		<div class="wrap">
			<h2>Indeed Jobs Shortcode</h2>
			<p>Indeed Jobs Shortcode Settings</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'indeed_jobs_shortcode_option_group' );
					do_settings_sections( 'indeed-jobs-shortcode-admin' );
				?>
				<h4>Available types</h4>
				HTML tags, [jobtitle], [company], [city], [state], [country], [formattedLocation], [source], [date], [snippet], [url], [onmousedown], [latitude], [longitude], [jobkey], [sponsored], [expired], [formattedLocationFull], [formattedRelativeTime]
				<p>Save when blank to reset to default</p>
				<?php
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function indeed_jobs_shortcode_page_init() {
		register_setting(
			'indeed_jobs_shortcode_option_group', // option_group
			'indeed_jobs_shortcode_option_name', // option_name
			array( $this, 'indeed_jobs_shortcode_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'indeed_jobs_shortcode_setting_section', // id
			'Settings', // title
			array( $this, 'indeed_jobs_shortcode_section_info' ), // callback
			'indeed-jobs-shortcode-admin' // page
		);

		add_settings_field(
			'indeed_api_key_0', // id
			'Indeed API Key', // title
			array( $this, 'indeed_api_key_0_callback' ), // callback
			'indeed-jobs-shortcode-admin', // page
			'indeed_jobs_shortcode_setting_section' // section
		);

		add_settings_field(
			'layout_template_1', // id
			'Layout Template', // title
			array( $this, 'layout_template_1_callback' ), // callback
			'indeed-jobs-shortcode-admin', // page
			'indeed_jobs_shortcode_setting_section' // section
		);
	}

	public function indeed_jobs_shortcode_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['indeed_api_key_0'] ) ) {
			$sanitary_values['indeed_api_key_0'] = sanitize_text_field( $input['indeed_api_key_0'] );
		}

		if ( isset( $input['layout_template_1'] ) && !empty( $input['layout_template_1']) ) {
			$sanitary_values['layout_template_1'] = esc_textarea( $input['layout_template_1'] );
		} else {
			$sanitary_values['layout_template_1'] = '<a href="[url]" target="_blank" rel="nofollow" onmousedown="[onmousedown]">Job title: [jobtitle]</a>
Company name: [company]
Location: [city] [state] [country]
Source: [source]
Date: [date]
Description: [snippet]
<a href="[url]" target="_blank" rel="nofollow" onmousedown="[onmousedown]">Read more</a>
';
		}

		return $sanitary_values;
	}

	public function indeed_jobs_shortcode_section_info() {

	}

	public function indeed_api_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="indeed_jobs_shortcode_option_name[indeed_api_key_0]" id="indeed_api_key_0" value="%s">',
			isset( $this->indeed_jobs_shortcode_options['indeed_api_key_0'] ) ? esc_attr( $this->indeed_jobs_shortcode_options['indeed_api_key_0']) : ''
		);
	}

	public function layout_template_1_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="indeed_jobs_shortcode_option_name[layout_template_1]" id="layout_template_1">%s</textarea>',
			isset( $this->indeed_jobs_shortcode_options['layout_template_1'] ) ? esc_attr( $this->indeed_jobs_shortcode_options['layout_template_1']) : '<a href="[url]" target="_blank" rel="nofollow" onmousedown="[onmousedown]">Job title: [jobtitle]</a>
Company name: [company]
Location: [city] [state] [country]
Source: [source]
Date: [date]
Description: [snippet]
<a href="[url]" target="_blank" rel="nofollow" onmousedown="[onmousedown]">Read more</a>
'
		);
	}

}
if ( is_admin() )
	$indeed_jobs_shortcode = new IndeedJobsShortcode();

/*
 * Retrieve this value with:
 * $indeed_jobs_shortcode_options = get_option( 'indeed_jobs_shortcode_option_name' ); // Array of All Options
 * $indeed_api_key_0 = $indeed_jobs_shortcode_options['indeed_api_key_0']; // Indeed API Key
 * $layout_template_1 = $indeed_jobs_shortcode_options['layout_template_1']; // Layout Template
 */
