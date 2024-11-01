<?php

/*
  Plugin Name: WooCommerce Simple Frontend Manager
  Plugin URI: https://wpfrontendadmin.com
  Description: Create and edit products from the frontend
  Version: 1.0.3
  Author: JoseVega
  Author Email: josevega@wpfrontendadmin.com
  Author URI: https://wpsheeteditor.com
  License:

  Copyright 2018 JoseVega (josevega@vegacorp.me)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 */


if (!class_exists('VGFA_WooCommerce_Frontend_Manager')) {

	class VGFA_WooCommerce_Frontend_Manager {

		static private $instance = false;
		static $textname = 'VGFA_WooCommerce_Frontend_Manager';
		static $dir = __DIR__;
		static $version = '1.0.3';
		static $name = 'Simple Frontend Manager';
		var $args = array();

		private function __construct() {
			
		}

		function init() {
			add_action('admin_menu', array($this, 'register_menu_page'));
			add_filter('vg_admin_to_frontend/allowed_urls', array($this, 'set_allowed_urls'));
			add_filter('vg_admin_to_frontend/plugin_sdk_args', array($this, 'set_plugin_sdk_args'));
			add_filter('vg_admin_to_frontend/wrong_plan/free_version_features_text', array($this, 'set_free_version_features_text'));
			add_filter('vg_admin_to_frontend/settings_page/args', array($this, 'set_settings_page_args'));
			add_action('admin_head', array($this, 'cleanup_admin_page_for_frontend'));
			require __DIR__ . '/vendor/display-admin-page-on-frontend/index.php';
		}

		function cleanup_admin_page_for_frontend() {
			?>
			<style>
				.vgca-only-admin-content #product-type,
				.vgca-only-admin-content .attribute_tab {
					display: none !important;
				}
			</style>
			<?php

		}

		function set_settings_page_args($args) {
			$args['display_name'] = __('WooCommerce Simple Frontend Manager', VGFA_WooCommerce_Frontend_Manager::$textname);
			$args['page_title'] = __('WooCommerce Simple Frontend Manager', VGFA_WooCommerce_Frontend_Manager::$textname);
			$args['page_parent'] = 'options-general.php';
			return $args;
		}

		function set_free_version_features_text($text) {
			return __('You are using the Free plugin. You can view these pages in the frontend: the products list and the products editor.', VGFA_WooCommerce_Frontend_Manager::$textname);
		}

		function set_plugin_sdk_args($args) {
			$args = array(
				'main_plugin_file' => __FILE__,
				'show_welcome_page' => true,
				'welcome_page_file' => VGFA_WooCommerce_Frontend_Manager::$dir . '/views/welcome-page-content.php',
				'plugin_name' => VGFA_WooCommerce_Frontend_Manager::$name,
				'plugin_prefix' => 'wpatof_',
				'plugin_version' => VGFA_WooCommerce_Frontend_Manager::$version,
				'plugin_options' => get_option(VGFA_WooCommerce_Frontend_Manager::$textname, false),
				'buy_link' => 'https://wpfrontendadmin.com/woo-buy-now',
				'buy_link_text' => __('Try premium plugin for FREE - 7 Days', VGFA_WooCommerce_Frontend_Manager::$textname),
			);
			$this->args = $args;
			return $args;
		}

		function set_allowed_urls($allowed_urls) {
			$allowed_urls = array(
				admin_url('edit.php?post_type=product'),
				admin_url('post-new.php?post_type=product'),
				admin_url('post.php?action=edit'),
			);
			return $allowed_urls;
		}

		function register_menu_page() {
			add_submenu_page('woocommerce', VGFA_WooCommerce_Frontend_Manager::$name, VGFA_WooCommerce_Frontend_Manager::$name, 'manage_options', 'wpatof_welcome_page', array(VG_Admin_To_Frontend_Obj()->vg_plugin_sdk, 'render_welcome_page')
			);
		}

		/**
		 * Creates or returns an instance of this class.
		 */
		static function get_instance() {
			if (null == VGFA_WooCommerce_Frontend_Manager::$instance) {
				VGFA_WooCommerce_Frontend_Manager::$instance = new VGFA_WooCommerce_Frontend_Manager();
				VGFA_WooCommerce_Frontend_Manager::$instance->init();
			}
			return VGFA_WooCommerce_Frontend_Manager::$instance;
		}

		function __set($name, $value) {
			$this->$name = $value;
		}

		function __get($name) {
			return $this->$name;
		}

	}

	if (!function_exists('VGFA_WooCommerce_Frontend_Manager_Obj')) {

		function VGFA_WooCommerce_Frontend_Manager_Obj() {
			return VGFA_WooCommerce_Frontend_Manager::get_instance();
		}

	}

	VGFA_WooCommerce_Frontend_Manager_Obj();
}
