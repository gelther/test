<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WACF_Admin_Settings.
 *
 * Admin settings class handles everything related to settings.
 *
 * @class		WACF_Admin_Settings
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WACF_Admin_Settings {

	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = apply_filters( 'woocommerce_advanced_checkout_field_settings', array(

			array(
				'title' => __( 'General', 'woocommerce-advanced-checkout-fields' ),
				'type'  => 'title',
			),

			array(
				'title'    => __( 'Enable Advanced Checkout Fields ', 'woocommerce-advanced-checkout-fields' ),
				'desc'     => __( 'When disabled you will still be able to manage the checkout fields, but no modifications will be done on the front-end.', 'woocommerce-advanced-checkout-fields' ),
				'id'       => 'enable_woocommerce_advanced_checkout_fields',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false
			),

			array(
				'title' => __( 'Advanced Checkout Fields', 'woocommerce-advanced-checkout-fields' ),
				'type'  => 'advanced_checkout_field_settings_table',
			),

			array(
				'type' => 'sectionend',
			),

		) );

		return $settings;

	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function update_options() {
		WC_Admin_Settings::save_fields( $this->get_settings() );

	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.0
	 */
	public function menu_highlight() {

		global $parent_file, $submenu_file, $post_type;

		if ( 'checkout_field_group' == $post_type ) :
			$parent_file  = 'woocommerce';
			$submenu_file = 'wc-settings';
		endif;

	}


	/**
	 * Add shipping section.
	 *
	 * Add a new 'extra shipping options' section under the shipping tab.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $sections List of existing shipping sections.
	 * @return array           List of modified shipping sections.
	 */
	public function add_checkout_fields_section( $sections ) {

		$sections['advanced_checkout_field_options'] = __( 'Checkout fields', 'woocommerce-advanced-checkout-fields' );

		return $sections;

	}


	/**
	 * WACF settings.
	 *
	 * Add the settings to the Extra Shipping Options shipping section.
	 *
	 * @since 1.0.0
	 *
	 * @param string $current_section Slug of the current section
	 */
	public function advanced_checkout_field_options_section_settings( $current_section ) {

		global $current_section;

		if ( 'advanced_checkout_field_options' !== $current_section ) :
			return;
		endif;

		$settings = $this->get_settings();
		WC_Admin_Settings::output_fields( $settings );

	}


	/**
	 * Table field type.
	 *
	 * Load and render table as a field type.
	 *
	 * @return string
	 */
	public function generate_table_field() {
		// Checkout fields table
		require_once plugin_dir_path( __FILE__ ) .'views/html-advanced-checkout-fields-table.php';

	}


}
