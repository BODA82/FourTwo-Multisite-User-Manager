<?php
class FourTwo_MUM_Admin_Page {
	
	function __construct() {
		
		add_action('network_admin_menu', array($this, 'menu_page'));
		
	}
	
	public function menu_page() {
		add_menu_page( 
			__('Multisite User Manager', FOURTWO_MUM_TEXTDOMAIN), 
			__('Multisite User Manager', FOURTWO_MUM_TEXTDOMAIN), 
			'manage_network_options', 
			'fourtwo-mum', 
			array($this, 'menu_page_callback'), 
			'dashicons-groups'
		);	
	}

	public function menu_page_callback() {
	
		// Check user capabilities
	    if (!current_user_can('manage_network_options')) {
	        return;
	    }
	    ?>
		
		<div class="wrap">
        	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			
			<div class="fourtwo-mum-section fourtwo-mum-group">
				
				<div class="fourtwo-mum-col fourtwo-mum-span_6_of_12">
					
					<div id="fourtwo-mum-sites-list">
						
						<h2><?php _e('Quick Add/Remove User to Blogs', FOURTWO_MUM_TEXTDOMAIN); ?></h2>
						
						<div class="fourtwo-mum-inner-wrap">
							
							<div id="fourtwo-mum-infobox">
								<div id="fourtwo-mum-infobox-close"><i class="fa fa-close fa-2x"></i></div>
								<div id="fourtwo-mum-infobox-inner"></div>
							</div>
								
							<div class="fourtwo-mum-user-section">
								
								<h4><?php _e('Select Existing WordPress User', FOURTWO_MUM_TEXTDOMAIN); ?></h4>
								
								<input class="fourtwo-mum-user-search" placeholder="<?php _e('Search By Email Address', FOURTWO_MUM_TEXTDOMAIN); ?>" /> <span class="fourtwo-mum-loader"><i class="fa fa-refresh fa-spin fa-2x"></i></span>
								
							</div>
							
							<div class="fourtwo-mum-sites-section">
								
								<h4><?php _e('Select Site(s) to Grant or Remove Permissions', FOURTWO_MUM_TEXTDOMAIN); ?></h4>
								
								<input class="fourtwo-mum-list-search search" placeholder="<?php _e('Filter List By Site Name', FOURTWO_MUM_TEXTDOMAIN); ?>"/> <a id="fourtwo-mum-select-all-sites" class="unchecked button" href="javascript:void(0);"><?php _e('Select All Sites', FOURTWO_MUM_TEXTDOMAIN); ?></a>
								
								<h4><?php _e('Available Sites', FOURTWO_MUM_TEXTDOMAIN); ?></h4>
								
								<ul class="list">
								<?php
								$all_sites = get_sites();
								
								foreach($all_sites as $site) { 
									$blog_details = get_blog_details($site->blog_id);
									echo '<li><input type="checkbox" class="site" name="ite-id" value="' . $blog_details->blog_id . '" /><span class="site-name">' . $blog_details->blogname . '</span></li>';
								}	
								?>
								</ul>
								
							</div>
							
							<div class="fourtwo-mum-perms-section">
								
								<div class="fourtwo-mum-add-perms">
									
									<h4><?php _e('Add Permissions for Selected User', FOURTWO_MUM_TEXTDOMAIN); ?></h4>
									
									<a id="fourtwo-mum-perms-add" class="button button-primary" href="javascript:void(0);">Add Permissions</a>
									
									<label><?php _e('Set Role:', FOURTWO_MUM_TEXTDOMAIN); ?></label>
									<select id="fourtwo-mum-role">
									<?php
									global $wp_roles;
									$available_roles = $wp_roles->roles;
									
									foreach ($available_roles as $key => $value) {
										echo '<option value="' . $key . '" ' . selected($key, 'subscriber') . '>' . ucwords($key) . '</option>';
									}
									?>
									</select>
									
									<span id="fourtwo-mum-add-loader"><i class="fa fa-refresh fa-spin fa-2x"></i></span>
									
								</div>
								
								<div class="fourtwo-mum-remove-perms">
									
									<h4><?php _e('Remove Permissions for Selected User', FOURTWO_MUM_TEXTDOMAIN); ?></h4>
									
									<a id="fourtwo-mum-perms-remove" class="button fourtwo-mum-button-remove" href="javascript:void(0);">Remove Permissions</a>
									
									<label><?php _e('Reassign Content To:', FOURTWO_MUM_TEXTDOMAIN); ?></label>
									<select id="fourtwo-mum-reassign">
									<?php
									$super_admins = get_super_admins();
									foreach ($super_admins as $admin) {
										$user = get_user_by('login', $admin);
										echo '<option value="' . $user->ID . '">' . $user->display_name . ' (' . $user->user_email . ')</option>';
									}
									?>
									</select>
									
									<span id="fourtwo-mum-remove-loader"><i class="fa fa-refresh fa-spin fa-2x"></i></span>
									
								</div>
								
							</div>
							
						</div>
						
					</div>
					
				</div>
				
				<div class="fourtwo-mum-col fourtwo-mum-span_6_of_12">
					
					<div id="fourtwo-mum-user-box">
					
						<h2><?php _e('User Lookup / Modify Permissions', FOURTWO_MUM_TEXTDOMAIN); ?></h2>
						
					</div>
					
				</div>
				
			</div>
        
    	</div>
	
	<?php	
	}
	
}