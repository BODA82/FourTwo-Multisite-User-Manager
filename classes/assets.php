<?php
class FourTwo_MUM_Assets {
	
	function __construct() {
		
		add_action('admin_enqueue_scripts', array($this, 'assets'));
		
		add_action('admin_enqueue_scripts', array($this, 'localize_scripts'));
		
	}
	
	// Queue up plugin's assets
	function assets($hook) {
	
		if ('toplevel_page_fourtwo-mum' != $hook) {
			return;
		}
		
		wp_enqueue_script('fourtwo-mum-listjs', FOURTWO_MUM_URL . 'assets/js/list.min.js', array('jquery'), '1.5.0', true);
		wp_enqueue_script('fourtwo-mum-pluginjs', FOURTWO_MUM_URL . 'assets/js/plugin.js', array('jquery'), FOURTWO_MUM_VERSION, true);
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-autocomplete');
		
		wp_enqueue_style('fourtwo-mum-fontawesome', FOURTWO_MUM_URL . 'assets/css/font-awesome.min.css', array(), FOURTWO_MUM_VERSION, 'all');
		wp_enqueue_style('fourtwo-mum-list-styles', FOURTWO_MUM_URL . 'assets/css/list-style.css', array(), FOURTWO_MUM_VERSION, 'all');
		wp_enqueue_style('fourtwo-mum-styles', FOURTWO_MUM_URL . 'assets/css/plugin.css', array(), FOURTWO_MUM_VERSION, 'all');
		wp_enqueue_style('fourtwo-mum-jqueryui-styles', FOURTWO_MUM_URL . 'assets/css/jquery-ui.min.css', array(), '1.12.1', 'all');
		wp_enqueue_style('fourtwo-mum-jqueryui-structure', FOURTWO_MUM_URL . 'assets/css/jquery-ui.structure.min.css', array(), '1.12.1', 'all');
		wp_enqueue_style('fourtwo-mum-jqueryui-theme', FOURTWO_MUM_URL . 'assets/css/jquery-ui.theme.min.css', array(), '1.12.1', 'all');
		
	}
	
	
	public function localize_scripts() {
		
		$vars = array(
			'select_all_sites' => __('Select All Sites', FOURTWO_MUM_TEXTDOMAIN),
			'deselect_all_sites' => __('Deselect All Sites', FOURTWO_MUM_TEXTDOMAIN),
			'no_user_selected_label' => __('No User Selected', FOURTWO_MUM_TEXTDOMAIN),
			'no_user_selected_message' => __('Use the search box below to search for and select a user you want to modify permissions for.', FOURTWO_MUM_TEXTDOMAIN),
			'no_sites_selected_label' => __('No Sites Selected', FOURTWO_MUM_TEXTDOMAIN),
			'no_sites_selected_message' => __('Select the sites from the sites list below you want to add/remove user permissions for.', FOURTWO_MUM_TEXTDOMAIN)
		);
		
		// Localize Scripts
		wp_localize_script('fourtwo-mum-pluginjs', 'fourtwo_mum_vars', $vars);
		
	}
	
}