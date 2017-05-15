jQuery(document).ready(function($) {
	
	/**
	 * Setup site list, sorting, and filtering
	 */
	var options = {
		valueNames:['site-name'],
		order: 'asc'
	};
	
	var fourtwoSiteList = new List('fourtwo-mum-sites-list', options);
	fourtwoSiteList.sort('site-name', { order: "asc" });
	
	/**
	 * Check/Uncheck all button
	 */
	$('#fourtwo-mum-select-all-sites').click(function() {
		
		var checkboxes = $('#fourtwo-mum-sites-list .list .site');
		
		if ($(this).hasClass('unchecked')) {
			
			$(this).removeClass('unchecked').addClass('checked');
			$(this).text(fourtwo_mum_vars.deselect_all_sites);
			
			$.each(checkboxes, function(idx, val) {
				$(this).prop('checked', true);
			});
			
		} else {
			
			$(this).removeClass('checked').addClass('unchecked');
			$(this).text(fourtwo_mum_vars.select_all_sites);
			
			$.each(checkboxes, function(idx, val) {
				$(this).prop('checked', false);
			});
			
		}
		
	});
	
	/**
	 * User AJAX Search
	 */
	
	$('.fourtwo-mum-user-search').autocomplete({
		source: function(request, response) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: 'action=fourtwo_mum_user_search&search=' + request.term,
				success: function(data) {
					
					var obj = $.parseJSON(data);
					
					if (obj.status == 1) {
						
						var items = [];
						var users = obj.users;
						
						for (i=0; i < users.length; i++) {
							
							var user = users[i];
							var item = {
								label: user.first_name + ' ' + user.last_name + ' (' + user.user_email + ')',
								value: user.id
							};
							items.push(item);
							
						}
						
						response(items);
						
					}
					
				},
				error: function(error) {
					console.log(error);
				}
			});
		},
		minLength: 1,
		scroll: true,
		search: function(event, ui) {
			$('.fourtwo-mum-loader').show();
			fourtwo_mum_clear_infobox();
		},
		response: function(event, ui) {
			$('.fourtwo-mum-loader').hide();
		},
		select: function(event, ui) {
			event.preventDefault();
			$('.fourtwo-mum-user-search').val(ui.item.label);
			$('.fourtwo-mum-user-search').data('user-id', ui.item.value);
		},
		focus: function(event, ui) {
			event.preventDefault();
			$('.fourtwo-mum-user-search').val(ui.item.label);
			$('.fourtwo-mum-user-search').attr('data-user-id', ui.item.value);
		}
	});
	
	/**
	 * Add Permissions Button Click
	 */
	
	$('#fourtwo-mum-perms-add').click(function() {
		
		$('#fourtwo-mum-add-loader').show();
		
		var user_id = $('.fourtwo-mum-user-search').attr('data-user-id');
		var sites = $('.fourtwo-mum-sites-section .list .site:checked');
		var role = $('#fourtwo-mum-role option:selected').val();
		
		// Perform some client side validation
		if (user_id == undefined && sites.length == 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_user_selected_label + '</strong>: ' + fourtwo_mum_vars.no_user_selected_message + '<br /><br /><strong>' + fourtwo_mum_vars.no_sites_selected_label + '</strong>: ' + fourtwo_mum_vars.no_sites_selected_message;
			
			$('#fourtwo-mum-add-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else if (user_id == undefined && sites.length != 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_user_selected_label + '</strong>: ' + fourtwo_mum_vars.no_user_selected_message;
			
			$('#fourtwo-mum-add-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else if (user_id != undefined && sites.length == 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_sites_selected_label + '</strong>: ' + fourtwo_mum_vars.no_sites_selected_message;
			
			$('#fourtwo-mum-add-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else {
			
			var sitesObj = [];
		
			for (i=0; i<sites.length; i++) {
				var site = $(sites[i]).val();
				sitesObj.push(site);
			}
			
			var sitesList = sitesObj.join();
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: 'action=fourtwo_mum_add_user&user_id=' + user_id + '&role=' + role + '&sites=' + sitesList,
				success: function(response) {
					console.log(response);
					
					$('#fourtwo-mum-add-loader').hide();
					
					var obj = $.parseJSON(response);
					
					if (obj.status == 0) {
						
						$('#fourtwo-mum-infobox-inner').text(obj.message);
						$('#fourtwo-mum-infobox').addClass('fourtwo-mum-error').fadeIn();
						
					} else {
						
						$('#fourtwo-mum-infobox-inner').text(obj.message);
						$('#fourtwo-mum-infobox').addClass('fourtwo-mum-success').fadeIn();
						
					}
					
				},
				error: function(error) {
					console.log(error);
				}
			});
			
		}
		
	});
	
	/**
	 * Remove Permissions Button Click
	 */
	
	$('#fourtwo-mum-perms-remove').click(function() {
		
		$('#fourtwo-mum-remove-loader').show();
		
		var user_id = $('.fourtwo-mum-user-search').attr('data-user-id');
		var sites = $('.fourtwo-mum-sites-section .list .site:checked');
		var reassign_id = $('#fourtwo-mum-reassign option:selected').val();
		
		// Perform some client side validation
		if (user_id == undefined && sites.length == 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_user_selected_label + '</strong>: ' + fourtwo_mum_vars.no_user_selected_message + '<br /><br /><strong>' + fourtwo_mum_vars.no_sites_selected_label + '</strong>: ' + fourtwo_mum_vars.no_sites_selected_message;
			
			$('#fourtwo-mum-remove-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else if (user_id == undefined && sites.length != 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_user_selected_label + '</strong>: ' + fourtwo_mum_vars.no_user_selected_message;
			
			$('#fourtwo-mum-remove-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else if (user_id != undefined && sites.length == 0) {
			
			var message = '<strong>' + fourtwo_mum_vars.no_sites_selected_label + '</strong>: ' + fourtwo_mum_vars.no_sites_selected_message;
			
			$('#fourtwo-mum-remove-loader').hide();
			$('#fourtwo-mum-infobox-inner').html(message);
			$('#fourtwo-mum-infobox').addClass('fourtwo-mum-warning').fadeIn();
			
		} else {
			
			
			var sitesObj = [];
		
			for (i=0; i<sites.length; i++) {
				var site = $(sites[i]).val();
				sitesObj.push(site);
			}
			
			var sitesList = sitesObj.join();
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: 'action=fourtwo_mum_remove_user&user_id=' + user_id + '&reassign_id=' + reassign_id + '&sites=' + sitesList,
				success: function(response) {
					console.log(response);
					
					$('#fourtwo-mum-remove-loader').hide();
					
					var obj = $.parseJSON(response);
					
					if (obj.status == 0) {
						
						$('#fourtwo-mum-infobox-inner').text(obj.message);
						$('#fourtwo-mum-infobox').addClass('fourtwo-mum-error').fadeIn();
						
					} else {
						
						$('#fourtwo-mum-infobox-inner').text(obj.message);
						$('#fourtwo-mum-infobox').addClass('fourtwo-mum-success').fadeIn();
						
					}
					
				},
				error: function(error) {
					console.log(error);
				}
			});
			
		}
		
	});
	
	
	$('#fourtwo-mum-infobox-close').click(fourtwo_mum_clear_infobox);
	
	function fourtwo_mum_clear_infobox() {
		
		$('#fourtwo-mum-infobox').hide();
		$('#fourtwo-mum-infobox').removeClass();
		$('#fourtwo-mum-infobox-inner').html('');
		
	}
	
});