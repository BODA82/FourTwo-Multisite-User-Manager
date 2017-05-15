<?php
class FourTwo_MUM_Ajax {
	
	function __construct() {
		
		add_action('wp_ajax_fourtwo_mum_user_search', array($this, 'user_search'));
		
		add_action('wp_ajax_fourtwo_mum_add_user', array($this, 'add_user'));
		
		add_action('wp_ajax_fourtwo_mum_remove_user', array($this, 'remove_user'));
		
	}
	
	public function user_search() {
		
		// Check user capabilities
	    if (!current_user_can('manage_network_options')) {
	        return;
	    }
		
		$search = $_POST['search'];
		
		$args = array(
			'search' => '*' . esc_attr($search) . '*',
			'search_columns' => array('user_login', 'user_email'),
			'order' => 'ASC',
			'orderby' => 'last_name'
		);
		
		$user_query = new WP_User_Query($args);
		$users = $user_query->get_results();
		
		if (!empty($users)) {
			
			$results = array(
				'status' => 1,
				'message' => __('Users Found', FOURTWO_MUM_TEXTDOMAIN)
			);
			
			foreach ($users as $user) {
				
				$results['users'][] = array(
					'id' => $user->ID,
					'first_name' => $user->first_name,
					'last_name' => $user->last_name,
					'user_login' => $user->user_login,
					'user_email' => $user->user_email
				);
				
			}
			
		} else {
			
			$results = array(
				'status' => 0,
				'message' => __('No Users Found', FOURTWO_MUM_TEXTDOMAIN)	
			);
			
		}
		
		echo json_encode($results);
		
		wp_die();
		
	}
	
	public function add_user() {
		
		// Check user capabilities
	    if (!current_user_can('manage_network_options')) {
	        return;
	    }
		
		$user_id = $_POST['user_id'];
		$role = $_POST['role'];
		$sites = $_POST['sites'];
		
		/**
		 * Perform some validation. These errors shouldn't make it through since we're check in the 
		 * JavaScript before making the AJAX request, but we'll go ahead and check anyway.
		 */
		 
		// No user or sites provided
		if (empty($user) && empty($sites)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No user or blogs were provided. Please select an existing WordPress user and select the blogs you want to modify permissions for.', FOURTWO_MUM_TEXTDOMAIN)
			);
		
		// No user provided
		} elseif (empty($user_id) && !empty($sites)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No user was provided. Please select an existing WordPress user.', FOURTWO_MUM_TEXTDOMAIN)
			);
		
		// No sites provided
		} elseif (empty($sites) && !empty($user)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No blogs were provided. Please select the blogs you want to modify permissions for.', FOURTWO_MUM_TEXTDOMAIN)	
			);
		
		// Everything was provided, start adding them
		} else {
			
			$sites_obj = explode(',', $sites);
			$errors = array();
			
			// Try to add the user to each of the selected sites
			foreach ($sites_obj as $site_id) {
				
				// Check if the user is already a member of this blog
				if (!is_user_member_of_blog($user_id, $site_id)) {
				
					$add_user = add_user_to_blog($site_id, $user_id, $role);
					
					if (is_wp_error($add_user)) {
						$blog_details = get_blog_details($site_id);
						$errors[] = $blog_details->blogname;
					}
					
				}
			}
			
			// There were errors, so let the visitor know what sites errored out
			if (!empty($errors)) {
				
				$error_sites = implode(', ', $errors);
				$error_message = __('There was an error adding the user to the following blogs: ' . $error_sites, FOURTWO_MUM_TEXTDOMAIN);
				
				$results = array(
					'status' => 0,
					'message' => $error_message
				);
			
			// No errors, user was added to all sites successfully
			} else {
				
				$results = array(
					'status' => 1,
					'message' => __('User successfully added!', FOURTWO_MUM_TEXTDOMAIN)
				);
				
			}
			
		}
		
		echo json_encode($results);
		
		wp_die();
		
	}
	
	public function remove_user() {
		
		// Check user capabilities
	    if (!current_user_can('manage_network_options')) {
	        return;
	    }
		
		$user_id = $_POST['user_id'];
		$reassign_id = $_POST['reassign_id'];
		$sites = $_POST['sites'];
		
		/**
		 * Perform some validation. These errors shouldn't make it through since we're check in the 
		 * JavaScript before making the AJAX request, but we'll go ahead and check anyway.
		 */
		 
		// No user or sites provided
		if (empty($user) && empty($sites)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No user or blogs were provided. Please select an existing WordPress user and select the blogs you want to modify permissions for.', FOURTWO_MUM_TEXTDOMAIN)
			);
		
		// No user provided
		} elseif (empty($user_id) && !empty($sites)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No user was provided. Please select an existing WordPress user.', FOURTWO_MUM_TEXTDOMAIN)
			);
		
		// No sites provided
		} elseif (empty($sites) && !empty($user)) {
			
			$results = array(
				'status' => 0,
				'message' => __('No blogs were provided. Please select the blogs you want to modify permissions for.', FOURTWO_MUM_TEXTDOMAIN)	
			);
		
		// Everything was provided, start adding them
		} else {
		
			$sites_obj = explode(',', $sites);
			$errors = array();
			
			// Try to add the user to each of the selected sites
			foreach ($sites_obj as $site_id) {
				
				// Check if the user is already a member of this blog
				if (is_user_member_of_blog($user_id, $site_id)) {
					
					if (!is_user_member_of_blog($reassign_id, $site_id)) {
						add_user_to_blog($site_id, $reassign_id, 'contributor'); // need to revisit this (hardcoding contrib)
					}
					
					$remove_user = remove_user_from_blog($user_id, $site_id, $reassign_id);
					
					if (is_wp_error($remove_user)) {
						$blog_details = get_blog_details($site_id);
						$errors[] = $blog_details->blogname;
					}
					
				}
			}
			
			// There were errors, so let the visitor know what sites errored out
			if (!empty($errors)) {
				
				$error_sites = implode(', ', $errors);
				$error_message = __('There was an error removing the user to the following blogs: ' . $error_sites, FOURTWO_MUM_TEXTDOMAIN);
				
				$results = array(
					'status' => 0,
					'message' => $error_message
				);
			
			// No errors, user was added to all sites successfully
			} else {
				
				$results = array(
					'status' => 1,
					'message' => __('User successfully removed!', FOURTWO_MUM_TEXTDOMAIN)
				);
				
			}	
		
		}
		
		echo json_encode($results);
		
		wp_die();
		
	}
	
}