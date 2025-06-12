var $ = jQuery;


jQuery(document).ready(function($) {
	
	
	
//$('.uultra-tooltip').qtip();
	jQuery("#uultra-add-new-custom-field-frm").slideUp();	 
	jQuery( "#tabs-bupro" ).tabs({collapsible: false	});
	jQuery( "#tabs-bupro-settings" ).tabs({collapsible: false	});	
	jQuery( ".getbwp-datepicker" ).datepicker({changeMonth:true,changeYear:true,yearRange:"1940:2025"});
	
	// Adding jQuery Datepicker
	jQuery(function() {
			
			var uultra_date_format =  jQuery('#uultrgetbwp-widgets-icon-close-opena_date_format').val();			
			if(uultra_date_format==''){uultra_date_format='m/d/Y'}		
			
			
			jQuery( ".bupro-datepicker" ).datepicker({ 
				showOtherMonths: true, 
				dateFormat: getbwp_admin_v98.bb_date_picker_format, 
				closeText: GBPDatePicker.closeText,
				currentText: GBPDatePicker.currentText,
				monthNames: GBPDatePicker.monthNames,
				monthNamesShort: GBPDatePicker.monthNamesShort,
				dayNames: GBPDatePicker.dayNames,
				dayNamesShort: GBPDatePicker.dayNamesShort,
				dayNamesMin: GBPDatePicker.dayNamesMin,
				firstDay: GBPDatePicker.firstDay,
				isRTL: GBPDatePicker.isRTL,
				 minDate: 0
			 });
			
			
		
			jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
		});
		
		
	function getbwp_set_auto_c()
	{
			$("#bupclientsel").autocomplete({
				
			
			source: function( request, response ) {
					$.ajax({
						url: ajaxurl,
						dataType: "json",
						data: {
							action: 'getbwp_autocomple_clients_tesearch',
							term: this.term
						},
						
						success: function( data ) {
							
							response( $.map( data.results, function( item ) {
			                return {
								id: item.id,
			                	label: item.label,
			                	value: item.label,
								clinfo: item.clinfo
			                }
			           		 }));
							 
							 
							
						},
						
						error: function(jqXHR, textStatus, errorThrown) 
						{
							console.log(jqXHR, textStatus, errorThrown);
						}
						
					});
				},
			
				minLength: 2,			
				
				// optional (if other layers overlap autocomplete list)
				open: function(event, ui) {
					
					var dialog = $(this).closest('.ui-dialog');
					if(dialog.length > 0){
						
						//$('.ui-autocomplete.ui-front').zIndex(dialog.zIndex()+1);
					}
				},
				
				select: function( event, ui ) {
					
					//ui.item.ur
					
					jQuery( "#getbwp_client_id" ).val(ui.item.id);
					jQuery("#getbwp-client-details-tab").html(ui.item.clinfo);
					jQuery("#getbwp-client-details-tab").slideDown(400);
					jQuery("#getbwp-client-autofill-box").html(ui.item.clinfo);
					jQuery("#getbwp-client-autofill-box").slideDown(400);				
						
				}
			
			});
	}
	

	jQuery(document).on("click", ".buppro-message-close", function(e) {
		
		e.preventDefault();	
		
		var message_id =  jQuery(this).attr("message-id");
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_hide_proversion_message","message_id": message_id 
						 },
						
						success: function(data){
							
							window.location.reload();
							//alert('blocked');
								
							
							}
					});
			
	});
	
	//seet gcal				
	$(document).on("click", "#getbwp-backenedb-set-gacal-adm", function(e) {			
			
			var google_calendar =   $('#getbwp_staff_calendar_list').val();
			var staff_id =  jQuery(this).attr("staff-id");	
			
					
			
			$("#getbwp-gcal-message3").html(getbwp_admin_v98.msg_wait);
						
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "getbwp_set_default_gcal_staff", "google_calendar": google_calendar,  "staff_id": staff_id },
				
				success: function(data){									
					
					jQuery("#getbwp-gcal-message1").hide();
					jQuery("#getbwp-gcal-message2").hide();					
					$("#getbwp-gcal-message3").html(data);									
					
					
					}
			});
			
    		e.preventDefault();		 
				
        });
	
	/* 	Close Open Sections in Dasbhoard */

	jQuery(document).on("click", ".getbwp-widget-home-colapsable", function(e) {
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
		
		
		if(jQuery("#getbwp-main-cont-home-"+widget_id).is(":visible")) 
	  	{
					
			jQuery( "#getbwp-close-open-icon-"+widget_id ).removeClass( "fa-sort-asc" ).addClass( "fa-sort-desc" );
			
		}else{
			
			jQuery( "#getbwp-close-open-icon-"+widget_id ).removeClass( "fa-sort-desc" ).addClass( "fa-sort-asc" );			
	 	 }
		
		
		jQuery("#getbwp-main-cont-home-"+widget_id).slideToggle();	
					
		return false;
	});
	
	
	
	jQuery(document).on("click", ".getbwp-service-cate-parent", function(e) {
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				jQuery('.getbwp-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',1);										
					$("#getbwp-price-"+$(this).val()).prop("disabled",false);	
					$("#getbwp-qty-"+$(this).val()).prop("disabled",false);
								
				 });	
			
			}else{
			
				jQuery('.getbwp-service-cate-'+service_id).each(function () {
						  
					$(this).prop('checked',0);										
					$("#getbwp-price-"+$(this).val()).prop("disabled",true);	
					$("#getbwp-qty-"+$(this).val()).prop("disabled",true);
								
				 });
			}
			
	});
	
	
	//this will crop the avatar and redirect
	jQuery(document).on("click touchstart", "#uultra-confirm-avatar-cropping", function(e) {
			
			e.preventDefault();			
			
			var x1 = jQuery('#x1').val();
			var y1 = jQuery('#y1').val();
			
			
			var w = jQuery('#w').val();
			var h = jQuery('#h').val();
			var image_id = $('#image_id').val();
			var user_id = $('#user_id').val();				
			
			if(x1=="" || y1=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			}
			
			
			jQuery('#getbwp-cropping-avatar-wait-message').html(message_wait_availability);
			
			
			
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "getbwp_crop_avatar_user_profile_image", "x1": x1 , "y1": y1 , "w": w , "h": h  , "image_id": image_id , "user_id": user_id},
				
				success: function(data){					
					//redirect				
					var site_redir = jQuery('#site_redir').val();
					window.location.replace(site_redir);	
								
					
					
					}
			});
			
		     	
			 return false;
    		e.preventDefault();
			 
				
        });
		
	
	
	jQuery(document).on("click", "#getbwp-disconnect-gcal-user", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_disconnect_user_gcal", "user_id": user_id },
					
					success: function(data){
						
						getbwp_load_staff_details(user_id);
												
																
						
					}
				});
			
			
    		e.preventDefault();
			 
				
    });

	jQuery(document).on("click", "#getbwp-disconnect-zoom-user", function(e) {
			
		e.preventDefault();
		
		var user_id =  jQuery(this).attr("user-id");
		
		jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {"action": "getbwp_disconnect_user_zoom", "user_id": user_id },
				
				success: function(data){
					
					getbwp_load_staff_details(user_id);
											
															
					
				}
			});
		
		

		 
			
	});

	jQuery(document).on("click", "#btn-delete-user-avatar", function(e) {
			
			e.preventDefault();
			
			var user_id =  jQuery(this).attr("user-id");
			var redirect_avatar =  jQuery(this).attr("redirect-avatar");
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_delete_user_avatar", "user_id": user_id },
					
					success: function(data){
												
						refresh_my_avatar();
						
						if(redirect_avatar=='yes')
						{
							var site_redir = jQuery('#site_redir').val();
							window.location.replace(site_redir);
							
						}else{
							
							refresh_my_avatar();
							
						}
											
						
					}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
	
	function refresh_my_avatar ()
		{
			
			 jQuery.post(ajaxurl, {
							action: 'refresh_avatar'}, function (response){									
																
							jQuery("#uu-backend-avatar-section").html(response);
					
			});
			
		}
	
	
	jQuery(document).on("click", "#getbwp_re_schedule", function(e) {
			
			
			
			if ($(this).is(":checked")) 
			{
                $("#getbwp-availability-box").slideDown();
				$("#getbwp-availability-box-btn").slideDown();
				
            } else {
				
				$("#getbwp-availability-box-btn").slideUp();				
                $("#getbwp-availability-box").slideUp();
            }			
			
				 
				
        });
		
	jQuery(document).on("click", "#bupadmin-btn-validate-copy", function(e) {	
	
	
		 e.preventDefault();
		 
		 var p_ded =  $('#p_serial').val();
		 
		 jQuery("#loading-animation").slideDown();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_vv_c_de_a", 
						"p_s_le": p_ded },
						
						success: function(data){
							
							jQuery("#loading-animation").slideUp();							
						
								jQuery("#getbwp-validation-results").html(data);
								jQuery("#getbwp-validation-results").slideDown();								
								setTimeout("hidde_noti('getbwp-validation-results')", 8000)
								
								window.location.reload();
							
							}
					});
			
		 	
		
				
		return false;
	});
		
	
		/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	jQuery('#uu-fields-sortable').on('click','.getbwp-btn-close-edition-field',function(e)
	{		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#getbwp-edit-fields-bock-"+block_id).slideUp();				
		return false;
	});
    
    
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#getbwp-add-field-btn').on('click',function(e)
	{
		
		e.preventDefault();
			
		jQuery("#getbwp-add-new-custom-field-frm").slideDown(400);				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  Add New Field Form */
	jQuery('#getbwp-close-add-field-btn').on('click',function(e){
		
		e.preventDefault();
			
		jQuery("#getbwp-add-new-custom-field-frm").slideUp(400);				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
	jQuery('#uultra__custom_registration_form').on('change',function(e)
	{		
		e.preventDefault();
		getbwp_reload_custom_fields_set();
					
		return false;
	});
	
	
	/* Delete Users */
        
    jQuery('#getbwp-staff-details').on('click','#getbwp-staff-member-delete',function(e)
	{
        
        
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("staff-id");	
		  
		  
		  var doIt = false;
		
		  doIt=confirm(getbwp_admin_v98.msg_user_delete);
		  
		  if(doIt)  {
			  jQuery("#getbwp-spinner").show();
			  
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "getbwp_delete_staff_admin", 
							"staff_id": staff_id 
							 },
							
							success: function(data){				
							
								
								var staff_id = data;								
								jQuery("#getbwp-spinner").hide();
								getbwp_load_staff_list_adm();
								getbwp_load_staff_details(staff_id);			
							
							}
					});
				
				
				}
			
	});
	
	
	/* 	Update Details */

    jQuery(document).on("click", "#getbwp-btn-user-details-confirm", function(e) {
        
   
		e.preventDefault();	
			  
		  var staff_id =  jQuery(this).attr("data-field");	
		  
		  var staff_id =  jQuery('#staff_id').val();
		  var display_name =  jQuery('#reg_display_name').val();
		  var reg_telephone =  jQuery('#reg_telephone').val();
          var u_profession =  jQuery('#u_profession').val();
		  
		  var reg_email =  jQuery('#reg_email').val();
		  var reg_email2 =  jQuery('#reg_email2').val();
		  
		  jQuery("#getbwp-edit-details-message").html(message_wait_availability);	 
		
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_update_staff_admin", 
						"staff_id": staff_id , 
						"display_name": display_name ,
						"reg_email": reg_email , 
						"reg_email2": reg_email2 , 
						"reg_telephone": reg_telephone ,
                              "u_profession": u_profession },
						
						success: function(data){							
						
							jQuery("#getbwp-edit-details-message").html(data);				
						
							
							
							
							}
				});
			
		   	
				
		
	});
	
	/* 	FIELDS CUSTOMIZER - Delete Field */
	jQuery('#uu-fields-sortable').on('click','.getbwp-delete-profile-field-btn',function(e)
	{
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(custom_fields_del_confirmation);
		  
		  if(doIt)
		  {
			  
			  var p_id =  jQuery(this).attr("data-field");	
			  var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
		
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "delete_profile_field", 
						"_item": p_id , "getbwp_custom_form": uultra_custom_form },
						
						success: function(data){					
						
							jQuery("#getbwp-sucess-delete-fields-"+p_id).slideDown();
						    setTimeout("hidde_noti('getbwp-sucess-delete-fields-" + p_id +"')", 1000);
							jQuery( "#"+p_id ).addClass( "getbwp-deleted" );
							setTimeout("hidde_noti('" + p_id +"')", 1000);
							
							//reload fields list added 08-08-2014						
							getbwp_reload_custom_fields_set();		
							
							
							}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER - Add New Field Data */
	jQuery('#getbwp-btn-add-field-submit').on('click',function(e){
		e.preventDefault();
		
		
		var _position = $("#uultra_position").val();		
		var _type =  $("#uultra_type").val();
		var _field = $("#uultra_field").val();		
		
		var _meta_custom = $("#uultra_meta_custom").val();		
		var _name = $("#uultra_name").val();
		var _tooltip =  $("#uultra_tooltip").val();	
		var _help_text =  $("#uultra_help_text").val();		
	
		
		var _can_edit =  $("#uultra_can_edit").val();		
		var _allow_html =  $("#uultra_allow_html").val();
				
		var _private = $("#uultra_private").val();
		var _required =  $("#uultra_required").val();		
		var _show_in_register = $("#uultra_show_in_register").val();
		
		var _choices =  $("#uultra_choices").val();	
		var _predefined_options =  $("#uultra_predefined_options").val();		
		var uultra_custom_form =  $('#uultra__custom_registration_form').val();	
				
		var _icon =  $('input:radio[name=uultra_icon]:checked').val();
		
				
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "add_new_custom_profile_field", 
						"_position": _position , 
						"_type": _type ,
						"_field": _field ,
						"_meta_custom": _meta_custom ,
						"_name": _name  ,						
						"_tooltip": _tooltip ,
						
						"_help_text": _help_text ,	
						
						"_can_edit": _can_edit ,"_allow_html": _allow_html  ,
						"_private": _private, 
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices,  
						"_predefined_options": _predefined_options , 
						"getbwp_custom_form": uultra_custom_form,						
						"_icon": _icon },
						
						success: function(data){		
						
													
							jQuery("#getbwp-sucess-add-field").slideDown();
							setTimeout("hidde_noti('getbwp-sucess-add-field')", 3000)		
							//alert("done");
							window.location.reload();
							 							
							
							
							}
					});
			
		 
		
				
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER - Update Field Data */
	jQuery('#uu-fields-sortable').on('click','.getbwp-btn-submit-field',function(e){
		e.preventDefault();
		
		var key_id =  jQuery(this).attr("data-edition");	
		
		jQuery('#p_name').val()		  
		
		var _position = $("#uultra_" + key_id + "_position").val();		
		var _type =  $("#uultra_" + key_id + "_type").val();
		var _field = $("#uultra_" + key_id + "_field").val();		
		var _meta =  $("#uultra_" + key_id + "_meta").val();
		var _meta_custom = $("#uultra_" + key_id + "_meta_custom").val();		
		var _name = $("#uultra_" + key_id + "_name").val();
				
		var _tooltip =  $("#uultra_" + key_id + "_tooltip").val();	
		var _help_text =  $("#uultra_" + key_id + "_help_text").val();		
				
		var _can_edit =  $("#uultra_" + key_id + "_can_edit").val();		
		
		var _required =  $("#uultra_" + key_id + "_required").val();		
		var _show_in_register = $("#uultra_" + key_id + "_show_in_register").val();
				
		var _choices =  $("#uultra_" + key_id + "_choices").val();	
		var _predefined_options =  $("#uultra_" + key_id + "_predefined_options").val();		
		var _icon =  $('input:radio[name=uultra_' + key_id +'_icon]:checked').val();
		
		var uultra_custom_form =  $('#uultra__custom_registration_form').val();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "save_fields_settings", 
						"_position": _position , "_type": _type ,
						"_field": _field ,
						"_meta": _meta ,
						"_meta_custom": _meta_custom  
						,"_name": _name  ,											
						
						"_tooltip": _tooltip ,
						"_help_text": _help_text ,												
						"_icon": _icon ,						
						"_required": _required  ,
						"_show_in_register": _show_in_register ,						
						"_choices": _choices, 
						"_predefined_options": _predefined_options,
						"pos": key_id  , 
						"getbwp_custom_form": uultra_custom_form 
						
																	
						},
						
						success: function(data){		
						
												
						jQuery("#getbwp-sucess-fields-"+key_id).slideDown();
						setTimeout("hidde_noti('getbwp-sucess-fields-" + key_id +"')", 1000);
						
						getbwp_reload_custom_fields_set();		
						
							
							}
					});
			
		 
		
				
		return false;
	});
	
	
	/* 	FIELDS CUSTOMIZER -  Edit Field Form */
		
	jQuery(document).on("click", ".getbwp-btn-edit-field", function(e) {
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");			
		
		var uultra_custom_form = jQuery('#uultra__custom_registration_form').val();
		
		jQuery("#getbwp-spinner").show();
		
		jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_reload_field_to_edit", 
						"pos": block_id, "getbwp_custom_form": uultra_custom_form},
						
						success: function(data){
							
							
							jQuery("#getbwp-edit-fields-bock-"+block_id).html(data);							
							jQuery("#getbwp-edit-fields-bock-"+block_id).slideDown();							
							jQuery("#getbwp-spinner").hide();								
							
							
							}
					});
		
					
		return false;
	});
    
	
	jQuery(document).on("click", "#getbwp-adm-check-avail-btn", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#getbwp-category").val();
			var b_date=   jQuery("#getbwp-start-date").val();
			var b_staff=   jQuery("#getbwp-staff").val();	
			
			jQuery("#getbwp-steps-cont-res" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_check_adm_availability", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#getbwp-steps-cont-res").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
		jQuery(document).on("click", "#getbwp-adm-check-avail-btn-edit", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#getbwp-category").val();
			var b_date=   jQuery("#getbwp-start-date").val();
			var b_staff=   jQuery("#getbwp-staff").val();	
			
			jQuery("#getbwp-steps-cont-res-edit" ).html( message_wait_availability );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_check_adm_availability_admin", "b_category": b_category, "b_date": b_date , "b_staff": b_staff },
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#getbwp-steps-cont-res-edit").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
		
	
	jQuery(document).on("click", ".getbwp-btn-book-app", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("getbwp-data-date");
			var service_and_staff_id =  jQuery(this).attr("getbwp-data-service-staff");
			var time_slot =  jQuery(this).attr("getbwp-data-timeslot");
			
			jQuery("#getbwp_time_slot").val(time_slot);
			jQuery("#getbwp_booking_date").val(date_to_book);
			jQuery("#getbwp_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "getbwp_booking_date": date_to_book,
						   "getbwp_service_staff": service_and_staff_id,
						   "getbwp_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#getbwp-steps-cont-res").html(res);						

						}
				});				
			
				
    		e.preventDefault();		 
				
    });
	
	jQuery(document).on("click", ".getbwp-btn-book-app-admin", function(e) {
			
			e.preventDefault();			
			
			var date_to_book =  jQuery(this).attr("getbwp-data-date");
			var service_and_staff_id =  jQuery(this).attr("getbwp-data-service-staff");
			var time_slot =  jQuery(this).attr("getbwp-data-timeslot");
			
			jQuery("#getbwp_time_slot").val(time_slot);
			jQuery("#getbwp_booking_date").val(date_to_book);
			jQuery("#getbwp_service_staff").val(service_and_staff_id);
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "appointment_get_selected_time", 
						   "getbwp_booking_date": date_to_book,
						   "getbwp_service_staff": service_and_staff_id,
						   "getbwp_time_slot": time_slot},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#getbwp-steps-cont-res-edit").html(res);						

						}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	
	jQuery(document).on("click", ".getbwp-load-services-by-cate", function(e) {
		
		e.preventDefault();
		var category_id =  jQuery(this).attr("data-id");			
		
		getbwp_load_services(category_id);
		
			
					
	});
	
	
		
	jQuery(document).on("change", "#getbwp-category", function(e) {
			
			e.preventDefault();			
			
			var b_category=   jQuery("#getbwp-category").val();
			
			$('#getbwp-staff').prop('disabled', 'disabled');
			
			$('#getbwp-staff option:first-child').attr("selected", "selected");
			$('#getbwp-staff option:first-child').text(getbwp_admin_v98.message_wait_staff_box);
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#getbwp-staff-booking-list").html(res);					    
						

						}
				});			
			
			 return false;
    		e.preventDefault();		 
				
        });
	
	
	/* open staff member form */	
	jQuery( "#getbwp-staff-editor-box" ).dialog({
			autoOpen: false,			
			width: '400', // overcomes width:'auto' and maxWidth bug
   			maxWidth: 900,
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var staff_name=   jQuery("#staff_name").val();
				var staff_email=   jQuery("#staff_email").val();
				var staff_nick=   jQuery("#staff_nick").val();	
				jQuery("#getbwp-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
						
						success: function(data){
							
							
							var res = data;						
							
							if(isInteger(res))	
							{
								//load staff								
								getbwp_load_staff_adm(res);																
								jQuery("#getbwp-staff-editor-box" ).dialog( "close" );
								
														
							}else{
							
								jQuery("#getbwp-err-message" ).html( res );	
							
							}				
													
							 
							
							
							}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* open client member form */	
	jQuery( "#getbwp-client-new-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Add": function() {				
				
				var ret;
				
				var client_name=   jQuery("#client_name").val();
				var client_last_name=   jQuery("#client_last_name").val();
				var client_email=   jQuery("#client_email").val();
					
				jQuery("#getbwp-err-message" ).html( '' );		
							
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_add_client_confirm", "client_name": client_name, "client_last_name": client_last_name , "client_email": client_email },
						
						success: function(data){					
							
													
							var res =jQuery.parseJSON(data);				
							
							if(res.response=='OK')	
							{
																
								jQuery("#getbwp_client_id" ).val(res.user_id);	
								jQuery("#bupclientsel" ).val(res.content);	
								jQuery("#getbwp-client-new-box" ).dialog( "close" );							
														
							}else{ //ERROR
							
								jQuery("#getbwp-add-client-message" ).html( res.content );	
							
							}				
							
						}
					});
				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	jQuery(document).on("click", "#getbwp-btn-client-new-admin", function(e) {
			
			e.preventDefault();		
			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_client_get_add_form"},
					
					success: function(data){						
						
						var res = data;
						jQuery("#getbwp-client-new-box" ).html( res );
						jQuery("#getbwp-client-new-box" ).dialog( "open" );						

					}
				});				
			
				
			
    		e.preventDefault();		 
				
    });
	
	/* appointment created confirmation */	
	jQuery( "#getbwp-new-app-conf-message" ).dialog({
			autoOpen: false,			
			width: 'auto', // overcomes width:'auto' and maxWidth bug
   			maxWidth: 900,
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
						
			"Close": function() {
								
				jQuery( this ).dialog( "close" );
			},			
			
			},
			close: function() {
			
			
			}
	});
	
	
		
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#getbwp-create-new-app", function(e) {
			
			e.preventDefault();	
			
			jQuery("#getbwp-spinner").show();		
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_admin_new_appointment" },
					
					success: function(data){						
						
						var res = data;
						jQuery("#getbwp-appointment-new-box" ).html( res );
						jQuery("#getbwp-appointment-new-box" ).dialog( "open" );
						
						
						jQuery( ".bupro-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_admin_v98.bb_date_picker_format, 
							closeText: GBPDatePicker.closeText,
							currentText: GBPDatePicker.currentText,
							monthNames: GBPDatePicker.monthNames,
							monthNamesShort: GBPDatePicker.monthNamesShort,
							dayNames: GBPDatePicker.dayNames,
							dayNamesShort: GBPDatePicker.dayNamesShort,
							dayNamesMin: GBPDatePicker.dayNamesMin,
							firstDay: GBPDatePicker.firstDay,
							isRTL: GBPDatePicker.isRTL,
							 minDate: 0
						 });
						
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');						
						jQuery("#getbwp-spinner").hide();						
						getbwp_set_auto_c();	
					     
						
						
						}
				});
			
				
	});
	
	/* add Payment */	
	jQuery( "#getbwp-confirmation-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Ok": function() {				
				
				jQuery( this ).dialog( "close" );
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* add break */	
	jQuery( "#getbwp-breaks-new-box" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var getbwp_payment_amount=   jQuery("#getbwp_payment_amount").val();
				var getbwp_payment_transaction=   jQuery("#getbwp_payment_transaction").val();
				var getbwp_payment_date=   jQuery("#getbwp_payment_date").val();
				var getbwp_booking_id=   jQuery("#getbwp_appointment_id").val();
				var getbwp_payment_status=   jQuery("#getbwp_payment_status").val();	
				
				var getbwp_payment_id=   jQuery("#getbwp_payment_id").val();			
				
				if(getbwp_payment_amount==''){alert(err_message_payment_amount); return;}
				if(getbwp_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_staff_break_add_confirm", 
						       "getbwp_payment_amount": getbwp_payment_amount,
							   "getbwp_payment_transaction": getbwp_payment_transaction,
							   "getbwp_payment_date": getbwp_payment_date,
							   "getbwp_booking_id": getbwp_booking_id,
							   "getbwp_payment_id": getbwp_payment_id,
							   "getbwp_payment_status": getbwp_payment_status },
						
						success: function(data){	
							
							jQuery("#getbwp-new-payment-cont" ).html( data );
							jQuery("#getbwp-new-payment-cont" ).dialog( "close" );	
							getbwp_load_appointment_payments(getbwp_booking_id);						
							
							
							}
					});
					
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	/* add Payment */	
	jQuery( "#getbwp-new-payment-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var getbwp_payment_amount=   jQuery("#getbwp_payment_amount").val();
				var getbwp_payment_transaction=   jQuery("#getbwp_payment_transaction").val();
				var getbwp_payment_date=   jQuery("#getbwp_payment_date").val();
				var getbwp_booking_id=   jQuery("#getbwp_appointment_id").val();
				var getbwp_payment_status=   jQuery("#getbwp_payment_status").val();	
				
				var getbwp_payment_id=   jQuery("#getbwp_payment_id").val();			
				
				if(getbwp_payment_amount==''){alert(err_message_payment_amount); return;}
				if(getbwp_payment_date==''){alert(err_message_payment_date); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_admin_payment_confirm", 
						       "getbwp_payment_amount": getbwp_payment_amount,
							   "getbwp_payment_transaction": getbwp_payment_transaction,
							   "getbwp_payment_date": getbwp_payment_date,
							   "getbwp_booking_id": getbwp_booking_id,
							   "getbwp_payment_id": getbwp_payment_id,
							   "getbwp_payment_status": getbwp_payment_status },
						
						success: function(data){	
							
							jQuery("#getbwp-new-payment-cont" ).html( data );
							jQuery("#getbwp-new-payment-cont" ).dialog( "close" );	
							getbwp_load_appointment_payments(getbwp_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* add note */	
	jQuery( "#getbwp-new-note-cont" ).dialog({
			autoOpen: false,			
			width: '300', //   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {				
				
				jQuery( this ).dialog( "close" );
			},
			
			"Save": function() {				
				
				var getbwp_note_title=   jQuery("#getbwp_note_title").val();
				var getbwp_note_text=   jQuery("#getbwp_note_text").val();
				var getbwp_note_id=   jQuery("#getbwp_note_id").val();
				var getbwp_booking_id=   jQuery("#getbwp_appointment_id").val();
								
				if(getbwp_note_title==''){alert(err_message_note_title); return;}
				if(getbwp_note_text==''){alert(err_message_note_text); return;}	
							
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_admin_note_confirm", 
						       "getbwp_note_title": getbwp_note_title,
							   "getbwp_booking_id": getbwp_booking_id,
							   "getbwp_note_text": getbwp_note_text,
							   "getbwp_note_id": getbwp_note_id},
						
						success: function(data){	
							
							jQuery("#getbwp-new-note-cont" ).html( data );
							jQuery("#getbwp-new-note-cont" ).dialog( "close" );	
							getbwp_load_appointment_notes(getbwp_booking_id);						
							
							
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
			
			
			}
	});
	
	jQuery(document).on("click", ".getbwp-payment-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("getbwp-appointment-id");			
			var payment_id =  jQuery(this).attr("getbwp-payment-id");	 						
    		
			doIt=confirm(err_message_payment_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_delete_payment",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							getbwp_load_appointment_payments(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	
	jQuery(document).on("click", ".getbwp-note-deletion", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("getbwp-appointment-id");			
			var note_id =  jQuery(this).attr("getbwp-note-id");	 						
    		
			doIt=confirm(err_message_note_delete);
		  
		    if(doIt)
		    {
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_delete_note",  "note_id": note_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
							getbwp_load_appointment_notes(appointment_id);	
						
						
							
							}
					});
				
				
			}
			
    		e.preventDefault();
			 
				
        });
	
	jQuery(document).on("click", ".getbwp-payment-edit", function(e) {
			
			e.preventDefault();
			
			var appointment_id = jQuery(this).attr("getbwp-appointment-id");			
			var payment_id =  jQuery(this).attr("getbwp-payment-id");	 						
    		
			
			jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_get_payment_form",  "payment_id": payment_id ,  "appointment_id": appointment_id },
						
						success: function(data){	
						
						
							jQuery("#getbwp-new-payment-cont" ).html( data );	
							jQuery("#getbwp-new-payment-cont" ).dialog( "open" );	
							
							var uultra_date_format =  jQuery('#uultra_date_format').val();									
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: getbwp_admin_v98.bb_date_picker_format, 
								closeText: GBPDatePicker.closeText,
								currentText: GBPDatePicker.currentText,
								monthNames: GBPDatePicker.monthNames,
								monthNamesShort: GBPDatePicker.monthNamesShort,
								dayNames: GBPDatePicker.dayNames,
								dayNamesShort: GBPDatePicker.dayNamesShort,
								dayNamesMin: GBPDatePicker.dayNamesMin,
								firstDay: GBPDatePicker.firstDay,
								isRTL: GBPDatePicker.isRTL,
								 minDate: 0
							 });
						
							jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');				
						
							
							}
					});			
				

    		e.preventDefault();
			 
				
        });
	
	//
	jQuery(document).on("click", "#getbwp-adm-add-payment", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_payment_form"},
					
					success: function(data){					
												
						jQuery("#getbwp-new-payment-cont" ).html( data );	
						jQuery("#getbwp-new-payment-cont" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
						
						var uultra_date_format =  jQuery('#uultra_date_format').val();
									
						if(uultra_date_format==''){uultra_date_format='dd/mm/yy';}	
					
						jQuery( ".bupro-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_admin_v98.bb_date_picker_format, 
							closeText: GBPDatePicker.closeText,
							currentText: GBPDatePicker.currentText,
							monthNames: GBPDatePicker.monthNames,
							monthNamesShort: GBPDatePicker.monthNamesShort,
							dayNames: GBPDatePicker.dayNames,
							dayNamesShort: GBPDatePicker.dayNamesShort,
							dayNamesMin: GBPDatePicker.dayNamesMin,
							firstDay: GBPDatePicker.firstDay,
							isRTL: GBPDatePicker.isRTL,
							 minDate: 0
						 });
					
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');				
						
									     
						
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".getbwp-breaks-add", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();
			
			jQuery("#getbwp-break-add-break-" +day_id).show();			
			jQuery("#getbwp-break-add-break-" +day_id).html( message_wait_availability );
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_break_add", 
							"day_id": day_id,
							"staff_id": staff_id},
					
					success: function(data){								
												
						jQuery("#getbwp-break-add-break-" +day_id).html( data );
									
												
						
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	//confirm break addition
	jQuery(document).on("click", ".getbwp-button-submit-breaks", function(e) {
			
			e.preventDefault();	
						
			var day_id = jQuery(this).attr("day-id");
			var staff_id=   jQuery("#staff_id").val();	
			
			var getbwp_from=   jQuery("#getbwp-break-from-"+day_id).val();
			var getbwp_to=   jQuery("#getbwp-break-to-"+day_id).val();
			
		
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_break_add_confirm", 
							"day_id": day_id,
							"staff_id": staff_id,
							"from": getbwp_from,
							"to": getbwp_to},
					
					success: function(data){
						
						var res = data	;												
						jQuery("#getbwp-break-message-add-" +day_id).html( data );
						getbwp_reload_staff_breaks(staff_id, day_id);
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", "#getbwp-adm-add-note", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_note_form"},
					
					success: function(data){					
												
						jQuery("#getbwp-new-note-cont" ).html( data );	
						jQuery("#getbwp-new-note-cont" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".getbwp-note-edit", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();	
			
			var note_id = jQuery(this).attr("getbwp-note-id");	
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_note_form",
					       "note_id": note_id},
					
					success: function(data){					
												
						jQuery("#getbwp-new-note-cont" ).html( data );	
						jQuery("#getbwp-new-note-cont" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	/* edit appointment */	
	jQuery( "#getbwp-appointment-edit-box" ).dialog({
			autoOpen: false,			
			width: '880', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Close": function() {				
				jQuery("#getbwp-appointment-edit-box" ).html('');
				jQuery( this ).dialog( "close" );
			}			
			
			},
			close: function() {
				
				jQuery("#getbwp-appointment-edit-box" ).html('');
			
			
			}
	});

	jQuery( "#getbwp-appointment-new-box" ).dialog({
		autoOpen: false,			
		width: '990', // overcomes width:'auto' and maxWidth bug
		   
		responsive: true,
		fluid: true, //new option
		modal: true,
		buttons: {			
		
		"Close": function() {				
			jQuery("#getbwp-appointment-new-box" ).html('');
			jQuery( this ).dialog( "close" );
		}			
		
		},
		close: function() {
			
			jQuery("#getbwp-appointment-new-box" ).html('');
		
		
		}
});
	
	
	
	jQuery(document).on("click", "#getbwp-adm-update-info", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();
			
			
			var booking_id =  jQuery("#getbwp_appointment_id").val();	
			var serial_data = $('.getbwp-custom-field').serialize();
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_booking_info", "custom_fields": serial_data, "booking_id": booking_id},
					
					success: function(data){					
												
						jQuery("#getbwp-confirmation-cont" ).html( gen_message_infoupdate_conf);	 
						jQuery("#getbwp-confirmation-cont" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", "#getbwp-add-category-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();
			
			jQuery('#getbwp-service-add-category-box').dialog('option', 'title', getbwp_admin_v98.msg_category_add);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_category_add_form"},
					
					success: function(data){					
												
						jQuery("#getbwp-service-add-category-box" ).html( data);	 
						jQuery("#getbwp-service-add-category-box" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".getbwp-edit-category-btn", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();
			
			var category_id =  jQuery(this).attr("category-id");
			jQuery('#getbwp-service-add-category-box').dialog('option', 'title', getbwp_admin_v98.msg_category_edit);
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_category_add_form",
						"category_id": category_id},
					
					success: function(data){					
												
						jQuery("#getbwp-service-add-category-box" ).html( data);	 
						jQuery("#getbwp-service-add-category-box" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	

	
	
	// on window resize run function
	$(window).resize(function () {
		//fluidDialog();
	});
	
	// catch dialog if opened within a viewport smaller than the dialog width
	$(document).on("dialogopen", ".ui-dialog", function (event, ui) {
		//fluidDialog();
	});
	
	function fluidDialog()
	 {
		var $visible = $(".ui-dialog:visible");
		// each open dialog
		$visible.each(function () 
		{
			var $this = $(this);
			
			var dialog = $this.find(".ui-dialog-content").data("dialog");
			
			// if fluid option == true
			if (dialog.options.fluid) {
				var wWidth = $(window).width();
				// check window width against dialog width
				if (wWidth < dialog.options.maxWidth + 50) {
					// keep dialog from filling entire screen
					$this.css("max-width", "90%");
				} else {
					// fix maxWidth bug
					$this.css("max-width", dialog.options.maxWidth);
				}
				//reposition dialog
				dialog.option("position", dialog.options.position);
			}
		});
	
	}


	/* open service form */	
	jQuery( "#getbwp-service-editor-box" ).dialog({
			autoOpen: false,																							
			width: 550,
			modal: true,
			buttons: {
			"Update": function() {				
				
				var service_id=   jQuery("#getbwp-service-id").val();				
				var service_title=   jQuery("#getbwp-title").val();
				var service_desc=   jQuery("#getbwp-desc").val();
				var service_duration=   jQuery("#getbwp-duration").val();
				var service_price=   jQuery("#getbwp-price" ).val();
				var service_price_2=   jQuery("#getbwp-price-2" ).val();
				var service_capacity =  jQuery("#getbwp-capacity" ).val();
				var service_category =  jQuery("#getbwp-category" ).val();
				var service_color =  jQuery("#getbwp-service-color" ).val();
				var service_font_color =  jQuery("#getbwp-service-font-color" ).val();				
				var service_padding_before =  jQuery("#getbwp-padding-before" ).val();
				var service_padding_after =  jQuery("#getbwp-padding-after" ).val();				
				var service_groups =  jQuery("#getbwp-groups" ).val();
				var service_calculation =  jQuery("#getbwp-groups-calculation" ).val();
				var service_meeting_zoom =  jQuery("#getbwp-zoom" ).val();
				
				if(service_title==''){alert(getbwp_admin_v98.msg_service_input_title); return;}
				if(service_price==''){alert(getbwp_admin_v98.msg_service_input_price); return;}
				
				jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "getbwp_update_service",  "service_id": service_id ,
							"service_title": service_title,
							"service_desc": service_desc,
							"service_duration": service_duration,
							"service_price": service_price,
							"service_price_2": service_price_2,
							"service_capacity": service_capacity,
							"service_category": service_category,
							"service_color": service_color,
							"service_font_color": service_font_color,
							"service_padding_before": service_padding_before,
							"service_padding_after": service_padding_after,
							"service_groups": service_groups,
							"service_meeting_zoom": service_meeting_zoom,
							"service_calculation": service_calculation
														
							 },
							
							success: function(data){	
							
								jQuery("#getbwp-service-editor-box" ).dialog( "close" );				
								getbwp_load_services(service_category);
							
								
								
								}
						});
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	/* open category form */	
	jQuery( "#getbwp-service-add-category-box" ).dialog({
			autoOpen: false,																							
			width: 300,
			modal: true,
			buttons: {
			"Save": function() {
				
				var catetory_title=   jQuery("#but-category-name").val();
				var category_id=   jQuery("#getbwp_category_id").val();
				
				if(catetory_title==''){alert(err_message_category_name); return;}
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_add_category_confirm",
					"category_title": catetory_title,
					"category_id": category_id},
					
					success: function(data){		
								
						jQuery("#getbwp-spinner").hide();						
						jQuery("#getbwp-service-add-category-box" ).dialog( "close" );						
						getbwp_load_categories();
						
						
												
						
						}
				});				
				
				
			
			},
			
			"Cancel": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#getbwp-save-acc-settings-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("getbwp-staff-id");		
			
			var getbwp_per_backend_access=   jQuery("#getbwp_per_backend_access").val();
			var getbwp_upload_picture=   jQuery("#getbwp_upload_picture").val();			
			var getbwp_reschedule=   jQuery("#getbwp_reschedule").val();
			var getbwp_add_notes=   jQuery("#getbwp_add_notes").val();
			var getbwp_update_details=   jQuery("#getbwp_update_details").val();	
			var getbwp_profile_bg_color=   jQuery("#getbwp-profile-bg-color").val();	
			var getbwp_profile_bg_font_color=   jQuery("#getbwp-profile-bg-font-color").val();		
				
			jQuery("#getbwp-err-message" ).html( '' );	
			jQuery("#getbwp-loading-animation-acc-setting-staff" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_user_account_settings",
					"staff_id": staff_id,
					"getbwp_per_backend_access": getbwp_per_backend_access, "getbwp_upload_picture": getbwp_upload_picture ,
					"getbwp_reschedule": getbwp_reschedule, 
					"getbwp_reschedule": getbwp_reschedule ,
					"getbwp_add_notes": getbwp_add_notes,
					 "getbwp_update_details": getbwp_update_details, 
					 "getbwp_profile_bg_color": getbwp_profile_bg_color ,
					 "getbwp_profile_bg_font_color": getbwp_profile_bg_font_color 
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#getbwp-err-message" ).html( res );						
						jQuery("#getbwp-loading-animation-acc-setting-staff" ).hide( );	
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	 // This sends a reset link to a staff member
	jQuery(document).on("click", "#getbwp-save-acc-send-reset-link-staff", function(e) {
			
			var staff_id =  jQuery(this).attr("getbwp-staff-id");		
						
			jQuery("#getbwp-err-message" ).html( '' );	
			jQuery("#getbwp-loading-animation-acc-resetlink-staff" ).show( );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_send_welcome_email_to_staff",
					"staff_id": staff_id
					 
					 },
					
					success: function(data){					
						
						var res = data;	
						jQuery("#getbwp-loading-animation-acc-resetlink-staff" ).hide( );						
						jQuery("#getbwp-acc-resetlink-staff-message" ).html( res );						
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	

		
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#getbwp-save-glogal-business-hours", function(e) {
			
			e.preventDefault();			
			
			var getbwp_mon_from=   jQuery("#getbwp-mon-from").val();
			var getbwp_mon_to=   jQuery("#getbwp-mon-to").val();			
			var getbwp_tue_from=   jQuery("#getbwp-tue-from").val();
			var getbwp_tue_to=   jQuery("#getbwp-tue-to").val();			
			var getbwp_wed_from=   jQuery("#getbwp-wed-from").val();
			var getbwp_wed_to=   jQuery("#getbwp-wed-to").val();			
			var getbwp_thu_from=   jQuery("#getbwp-thu-from").val();
			var getbwp_thu_to=   jQuery("#getbwp-thu-to").val();			
			var getbwp_fri_from=   jQuery("#getbwp-fri-from").val();
			var getbwp_fri_to=   jQuery("#getbwp-fri-to").val();			
			var getbwp_sat_from=   jQuery("#getbwp-sat-from").val();
			var getbwp_sat_to=   jQuery("#getbwp-sat-to").val();			
			var getbwp_sun_from=   jQuery("#getbwp-sun-from").val();
			var getbwp_sun_to=   jQuery("#getbwp-sun-to").val();
			
			
			
				
			jQuery("#getbwp-err-message" ).html( '' );	
			jQuery("#getbwp-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_global_business_hours", 
					"getbwp_mon_from": getbwp_mon_from, "getbwp_mon_to": getbwp_mon_to ,
					"getbwp_tue_from": getbwp_tue_from, "getbwp_tue_to": getbwp_tue_to ,
					"getbwp_wed_from": getbwp_wed_from, "getbwp_wed_to": getbwp_wed_to ,
					"getbwp_thu_from": getbwp_thu_from, "getbwp_thu_to": getbwp_thu_to ,
					"getbwp_fri_from": getbwp_fri_from, "getbwp_fri_to": getbwp_fri_to ,
					"getbwp_sat_from": getbwp_sat_from, "getbwp_sat_to": getbwp_sat_to ,
					"getbwp_sun_from": getbwp_sun_from, "getbwp_sun_to": getbwp_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#getbwp-err-message" ).html( res );						
						jQuery("#getbwp-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", ".getbwp_restore_template", function(e) {
			
			
			var template_id =  jQuery(this).attr("b-template-id");
			jQuery("#email_template").val(template_id);
			jQuery("#reset_email_template").val('yes');
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "reset_email_template", 
					"email_template": template_id					
					
					 
					 },
					
					success: function(data){
						
						
						var res = data;								
						$("#b_frm_settings").submit();				
						
						
						}
				});
			
			
			
			
			
			
			 
				
        });
		
		//this adds the user and loads the user's details	
	jQuery(document).on("click", "#getbwp-save-glogal-business-hours-staff", function(e) {
			
			e.preventDefault();			
			
			var staff_id =  jQuery(this).attr("getbwp-staff-id");
			
			var getbwp_mon_from=   jQuery("#getbwp-mon-from").val();
			var getbwp_mon_to=   jQuery("#getbwp-mon-to").val();			
			var getbwp_tue_from=   jQuery("#getbwp-tue-from").val();
			var getbwp_tue_to=   jQuery("#getbwp-tue-to").val();			
			var getbwp_wed_from=   jQuery("#getbwp-wed-from").val();
			var getbwp_wed_to=   jQuery("#getbwp-wed-to").val();			
			var getbwp_thu_from=   jQuery("#getbwp-thu-from").val();
			var getbwp_thu_to=   jQuery("#getbwp-thu-to").val();			
			var getbwp_fri_from=   jQuery("#getbwp-fri-from").val();
			var getbwp_fri_to=   jQuery("#getbwp-fri-to").val();			
			var getbwp_sat_from=   jQuery("#getbwp-sat-from").val();
			var getbwp_sat_to=   jQuery("#getbwp-sat-to").val();			
			var getbwp_sun_from=   jQuery("#getbwp-sun-from").val();
			var getbwp_sun_to=   jQuery("#getbwp-sun-to").val();			
				
			jQuery("#getbwp-err-message" ).html( '' );	
			jQuery("#getbwp-loading-animation-business-hours" ).show( );		
			
			
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_staff_business_hours", 
					"staff_id": staff_id,					
					"getbwp_mon_from": getbwp_mon_from, "getbwp_mon_to": getbwp_mon_to ,
					"getbwp_tue_from": getbwp_tue_from, "getbwp_tue_to": getbwp_tue_to ,
					"getbwp_wed_from": getbwp_wed_from, "getbwp_wed_to": getbwp_wed_to ,
					"getbwp_thu_from": getbwp_thu_from, "getbwp_thu_to": getbwp_thu_to ,
					"getbwp_fri_from": getbwp_fri_from, "getbwp_fri_to": getbwp_fri_to ,
					"getbwp_sat_from": getbwp_sat_from, "getbwp_sat_to": getbwp_sat_to ,
					"getbwp_sun_from": getbwp_sun_from, "getbwp_sun_to": getbwp_sun_to ,
					 
					 },
					
					success: function(data){
						
						
						var res = data;		
						
						jQuery("#getbwp-err-message" ).html( res );						
						jQuery("#getbwp-loading-animation-business-hours" ).hide( );		
						
						
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	    var $form   = $('#business-hours');
		jQuery(document).on("change", ".getbwp_select_start", function(e) {	
			
			var $row = $(this).parent(),
				$end_select = $('.getbwp_select_end', $row),
				$start_select = $(this);
	
			if ($start_select.val()) {
				$end_select.show();
				$('span', $row).show();
	
				var start_time = $start_select.val();
	
				$('span > option', $end_select).each(function () {
					$(this).unwrap();
				});
	
				// Hides end time options with value less than in the start time
				$('option', $end_select).each(function () {
					if ($(this).val() <= start_time) {
						
						$(this).wrap("<span>").parent().hide();
					}
				});
				
			
				if (start_time >= $end_select.val()) {
					$('option:visible:first', $end_select).attr('selected', true);
				}
			} else { // OFF
			
				$end_select.hide();
				$('span', $row).hide();
			}
			
		}).each(function () {
			var $row = $(this).parent(),
				$end_select = $('.getbwp_select_end', $row);
	
			$(this).data('default_value', $(this).val());
			$end_select.data('default_value', $end_select.val());
	
			// Hides end select for "OFF" days
			if (!$(this).val()) {
				$end_select.hide();
				$('span', $row).hide();
			}
		}).trigger('change');

	
	
	//this adds the user and loads the user's details	
	jQuery(document).on("click", "#getbwp-edit-staff-service-btn", function(e) {
			
			e.preventDefault();
			
			
			var staff_id=   jQuery("#staff_id").val();
				
			jQuery("#getbwp-err-message" ).html( '' );		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_add_staff_confirm", "staff_name": staff_name, "staff_email": staff_email , "staff_nick": staff_nick },
					
					success: function(data){
						
						
						var res = data;						
															
					     
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	/* 	Delete Service */
    jQuery(document).on("click", ".getbwp-service-delete", function(e) {
    
	e.preventDefault();
		
		var doIt = false;		
		doIt=confirm(getbwp_admin_v98.msg_service_delete);		  
		  if(doIt){

			  var service_id =  jQuery(this).attr("service-id");	
			  var cate_id =  jQuery(this).attr("data-cate-id");
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_delete_service", 
						"service_id": service_id  },
						
						success: function(data){
							
							getbwp_load_services(cate_id);							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
		
	
	/* 	Delete category */
    jQuery(document).on("click", ".getbwp-category-delete", function(e) {
		e.preventDefault();
		
		var doIt = false;
		
		doIt=confirm(getbwp_admin_v98.msg_cate_delete);
		  
		  if(doIt)
		  {
			  
			  var cate_id =  jQuery(this).attr("category-id");	
			 
			  jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_delete_category", 
						"cate_id": cate_id  },
						
						success: function(data){
							
							getbwp_load_categories();							
							
							
						}
					});
			
		  }
		  else{
			
		  }		
		
				
		return false;
	});
		
	function isInteger(x) {
        return x % 1 === 0;
    }
	
	
	jQuery(document).on("click", "#getbwp-add-staff-btn", function(e) {
			
			e.preventDefault();	
			
			jQuery("#getbwp-spinner").show();		
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_new_staff" },
					
					success: function(data){								
					
						jQuery("#getbwp-staff-editor-box" ).html( data );							
						jQuery("#getbwp-staff-editor-box" ).dialog( "open" );
						jQuery("#getbwp-spinner").hide();
							
						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	
	jQuery(document).on("click", ".getbwp-break-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var break_id =  jQuery(this).attr("break-id");
			var day_id =  jQuery(this).attr("day-id");
			var staff_id =  jQuery("#staff_id" ).val();	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_delete_break",
					"break_id": break_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						getbwp_reload_staff_breaks (staff_id , day_id)							
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		
		jQuery(document).on("click", ".getbwp-daysoff-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var dayoff_id =  jQuery(this).attr("dayoff-id");
			var staff_id =  jQuery("#staff_id" ).val();	
			
			jQuery("#getbwp-spinner").show();
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_delete_dayoff",
					"dayoff_id": dayoff_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						getbwp_reload_days_off (staff_id);
						jQuery("#getbwp-spinner").hide();						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
		
		jQuery(document).on("click", ".getbwp-specialschedule-delete-btn", function(e) {
			
			e.preventDefault();		
			
			var schedule_id =  jQuery(this).attr("dayoff-id");
			var staff_id =  jQuery("#staff_id" ).val();	
			
			jQuery("#getbwp-spinner").show();
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_delete_special_schedule",
					"schedule_id": schedule_id,
					"staff_id": staff_id },
					
					success: function(data){
						
						getbwp_reload_special_schedule (staff_id);
						jQuery("#getbwp-spinner").hide();						
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	
	jQuery(document).on("click", "#getbwp-btn-add-staff-day-off-confirm", function(e) {
			
			e.preventDefault();		
			
			var staff_id =  jQuery("#staff_id" ).val();			
			var day_off_from =  jQuery("#getbwp-start-date" ).val();
			var day_off_to =  jQuery("#getbwp-end-date" ).val();
			
			jQuery("#getbwp-dayoff-message-add").html( message_wait_availability );	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_dayoff_add_confirm",
					"day_off_from": day_off_from,
					"day_off_to": day_off_to,
					"staff_id": staff_id },
					
					success: function(data){
						
						getbwp_reload_days_off (staff_id);
						jQuery("#getbwp-dayoff-message-add").html(data);
													
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
		
	jQuery(document).on("click", "#getbwp-btn-add-staff-special-schedule-confirm", function(e) {
			
			e.preventDefault();		
			
			var staff_id =  jQuery("#staff_id" ).val();	
			
			var day_available =  jQuery("#getbwp-special-schedule-date" ).val();					
			var time_from =  jQuery("#getbwp-special-schedule-from" ).val();
			var time_to =  jQuery("#getbwp-special-schedule-to" ).val();
			
			jQuery("#getbwp-speschedule-message-add").html( message_wait_availability );			
			jQuery("#getbwp-spinner").show();	
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_special_schedule_add_confirm",
					"time_from": time_from,
					"time_to": time_to,
					"day_available": day_available,
					"staff_id": staff_id },
					
					success: function(data){
						
						getbwp_reload_special_schedule (staff_id);
						jQuery("#getbwp-speschedule-message-add").html(data);						
						jQuery("#getbwp-spinner").hide();
													
						
						}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	jQuery(document).on("click", "#getbwp-add-service-btn", function(e) {
			
			e.preventDefault();
			
			var service_id =  jQuery(this).attr("service-id");
			var cate_id =  jQuery("#cate_id" ).val();
			
			jQuery('#getbwp-service-editor-box').dialog('option', 'title', getbwp_admin_v98.msg_service_add);			
			jQuery("#getbwp-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_service",  "service_id": service_id ,  "cate_id": cate_id },
					
					success: function(data){		
					
					
						jQuery("#getbwp-service-editor-box" ).html( data );							
						jQuery("#getbwp-service-editor-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();
						
						jQuery("#getbwp-spinner").hide();	
						
						
						}
				});
			
			
			 // Cancel the default action
			 return false;
    		e.preventDefault();
			 
				
        });
	

	
	jQuery(document).on("click", ".getbwp-admin-edit-service", function(e) {
			
			e.preventDefault();
			
			var service_id =  jQuery(this).attr("service-id");
			var cate_id =  jQuery("#cate_id" ).val();			
			jQuery('#getbwp-service-editor-box').dialog('option', 'title', getbwp_admin_v98.msg_service_edit);
			
			jQuery("#getbwp-spinner").show();
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_service",  
					"service_id": service_id , 
					 "cate_id": cate_id },
					
					success: function(data){					
					
						jQuery("#getbwp-service-editor-box" ).html( data );							
						jQuery("#getbwp-service-editor-box" ).dialog( "open" );
						jQuery('.color-picker').wpColorPicker();					
						jQuery("#getbwp-spinner").hide();	
						
						}
				});			

    		e.preventDefault();		 
				
        });
		
		jQuery(document).on("click", ".getbwp-staff-load", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery(this).attr("staff-id");			
			getbwp_load_staff_member(staff_id);	
				
    		
    		e.preventDefault();
			 
				
        });
		
				
		jQuery(document).on("click", ".getbwp-service-cate", function(e) {
			
			
			var ischecked = $(this).is(":checked");			
			var service_id = $(this).val();
			
			if(ischecked)
			{
				 $("#getbwp-price-"+service_id).prop("disabled",false);	
				 $("#getbwp-qty-"+service_id).prop("disabled",false);	
			
			}else{
				
				$("#getbwp-price-"+service_id).prop("disabled",true);	
				$("#getbwp-qty-"+service_id).prop("disabled",true);	
			}
			
		});
		
		
		jQuery(document).on("click", "#getbwp-admin-edit-staff-service-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var service_list = getbwp_get_checked_services();
			
			jQuery("#getbwp-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_staff_services",  "service_list": service_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#getbwp-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		jQuery(document).on("click", "#getbwp-admin-edit-staff-location-save", function(e) {
			
			e.preventDefault();
			
			var staff_id =  jQuery('#staff_id').val();
			var location_list = getbwp_get_checked_locations();
			
			jQuery("#getbwp-loading-animation-services" ).html( message_wait_availability );	
				
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_staff_locations",  "location_list": location_list,  "staff_id": staff_id },
					
					success: function(data){
						
						jQuery("#getbwp-loading-animation-services" ).html('');			
					
					
						
						}
				});
			
			
    		e.preventDefault();
			 
				
        });
		
		
		
		
		function getbwp_get_checked_services ()	
		{
			
			var checkbox_value = "";
			jQuery(".getbwp-cate-service-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					//get price and quantity
					var getbwp_price = jQuery("#getbwp-price-"+$(this).val()).val();
					var getbwp_qty = jQuery("#getbwp-qty-"+$(this).val()).val();
					checkbox_value += $(this).val() + "-" + getbwp_price + "-" + getbwp_qty + "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		function getbwp_get_checked_locations ()	
		{
			
			var checkbox_value = "";
			jQuery(".getbwp-location-checked").each(function () {
				
				var ischecked = $(this).is(":checked");
			   
				if (ischecked) 
				{
					
					checkbox_value += $(this).val()+ "|";
				}
				
				
			});
			
			return checkbox_value;		
		}
		
		
		
		/* 	FIELDS CUSTOMIZER -  restore default */
	jQuery('#getbwp-restore-fields-btn').on('click',function(e)
	{
		
		e.preventDefault();
		
		doIt=confirm(custom_fields_reset_confirmation);
		  
		  if(doIt)
		  {
			
			var uultra_custom_form = jQuery('#uultra__custom_registration_form').val();
			  
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "custom_fields_reset", 
						"p_confirm": "yes"  , 		"getbwp_custom_form": uultra_custom_form },
						
						success: function(data){
							
							jQuery("#fields-mg-reset-conf").slideDown();			
						
							 window.location.reload();						
							
							
							}
					});
			
		  }
			
					
		return false;
	});
	
	/* edit pricing */	
	jQuery( "#getbwp-service-variable-pricing-box" ).dialog({
			autoOpen: false,			
			width: '400px', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {
			"Save & Exit": function() {				
				
				var pricing_list = getbwp_get_flexible_pricing_values();				
				var service_id =  jQuery('#getbwp_pricing_service_id').val();
				
				
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_group_pricing_table", "pricing_list": pricing_list , "service_id": service_id },
					
					success: function(data){
						
						jQuery("#getbwp-service-variable-pricing-box" ).dialog( "close" );
												
																
						
					}
				});
				
			},
			
			"Close": function() {
				
				
				jQuery( this ).dialog( "close" );
			},
			
			
			},
			close: function() {
			
			
			}
	});
	
	function getbwp_get_flexible_pricing_values ()	
		{
			
			var checkbox_value = "";
			jQuery(".getbwp-servicepricing-textbox").each(function () {					
				
				var person_pricing =  $(this).val();
			   
				if (person_pricing!='') 
				{
					checkbox_value += person_pricing + "|";
				}
				
				
			});
			
			
			return checkbox_value;		
		}
	
	jQuery(document).on("click", ".getbwp-admin-edit-pricing", function(e) {
			
			
			
			var service_id =  jQuery(this).attr("service-id");
			
			jQuery("#getbwp-spinner").show();
			
	
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_service_pricing", "service_id": service_id },
					
					success: function(data){
						
						jQuery("#getbwp-service-variable-pricing-box" ).html( data );
						jQuery("#getbwp-service-variable-pricing-box" ).dialog( "open" );
						jQuery("#getbwp-spinner").hide();
												
																
						
					}
				});
			
			
			 // Cancel the default action
    		e.preventDefault();
			 
				
        });
	
	
	
	
	/* 	WIDGETS CUSTOMIZER -  Close Open Widget */
	jQuery('#getbwp-staff-details').on('click','.getbwp-widgets-icon-close-open, .getbwp-staff-details-header',function(e)
	{
		
		e.preventDefault();
		var widget_id =  jQuery(this).attr("widget-id");		
		var iconheight = 20;
        
        
		
		
		if(jQuery("#getbwp-widget-adm-cont-id-"+widget_id).is(":visible")) 
	  	{
			
			jQuery("#getbwp-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px 0px');
			
			
			
		}else{
			
			jQuery("#getbwp-widgets-icon-close-open-id-"+widget_id).css('background-position', '0px -'+iconheight+'px');			
	 	 }
		
		
		jQuery("#getbwp-widget-adm-cont-id-"+widget_id).slideToggle();	
					
		return false;
	});
	
	/* 	FIELDS CUSTOMIZER -  ClosedEdit Field Form */
	jQuery('.uultra-btn-close-edition-field').on('click',function(e)
	{
		
		e.preventDefault();
		var block_id =  jQuery(this).attr("data-edition");		
		jQuery("#uu-edit-fields-bock-"+block_id).slideUp();				
		return false;
	});
	
	
	function reload_full_callendar_bup()
	{
		
		
		
		
	}
	
			
	

	
});





function getbwp_reload_staff_breaks (staff_id , day_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'getbwp_get_current_staff_breaks',
							'staff_id': staff_id,
							'day_id': day_id
									
							}, function (response){									
																
							jQuery("#getbwp-break-adm-cont-id-"+day_id).html(response);
							
							//jQuery("#getbwp-spinner").hide();
							
		 });
}

function getbwp_reload_special_schedule (staff_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'getbwp_get_staff_special_schedule_list',
							'staff_id': staff_id
							
									
							}, function (response){									
																
							jQuery("#getbwp-staff-special-schedule-list").html(response);
							
							//jQuery("#getbwp-spinner").hide();
							
		 });
}

function getbwp_reload_days_off (staff_id)	
{
	
	jQuery.post(ajaxurl, {
							action: 'getbwp_get_staff_daysoff',
							'staff_id': staff_id
							
									
							}, function (response){									
																
							jQuery("#getbwp-staff-daysoff-list").html(response);
							
							//jQuery("#getbwp-spinner").hide();
							
		 });
}


function getbwp_load_categories ()	
	{
		jQuery("#getbwp-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'display_categories'
									
							}, function (response){									
																
							jQuery("#getbwp-categories-list").html(response);
							sortable_categories_list();							
							jQuery("#getbwp-spinner").hide();
							
		 });
}

function getbwp_load_services (category_id)	
{
		jQuery("#getbwp-spinner").show();
		
		jQuery.post(ajaxurl, {
							action: 'display_admin_services',
							'cate_id': category_id
									
							}, function (response){									
																
							jQuery("#getbwp-services-list").html(response);							
							jQuery("#getbwp-spinner").hide();
							
		 });
}


function getbwp_load_staff_member (staff_id)	
	{
		jQuery("#getbwp-spinner").show();
		  jQuery.post(ajaxurl, {
							action: 'getbwp_get_staff_details_ajax', 'staff_id': staff_id
									
							}, function (response){									
																
							jQuery("#getbwp-staff-details" ).html( response );							
							getbwp_rebuild_dom_date_picker();	
							jQuery('.color-picker').wpColorPicker();												
							jQuery("#getbwp-spinner").hide();
							
		 });
}


function getbwp_rebuild_dom_date_picker ()	
{
	var uultra_date_format =  jQuery('#uultra_date_format').val();			
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy'}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: getbwp_admin_v98.bb_date_picker_format, 
								closeText: GBPDatePicker.closeText,
								currentText: GBPDatePicker.currentText,
								monthNames: GBPDatePicker.monthNames,
								monthNamesShort: GBPDatePicker.monthNamesShort,
								dayNames: GBPDatePicker.dayNames,
								dayNamesShort: GBPDatePicker.dayNamesShort,
								dayNamesMin: GBPDatePicker.dayNamesMin,
								firstDay: GBPDatePicker.firstDay,
								isRTL: GBPDatePicker.isRTL,
								 minDate: 0
							 });
							 
														
							jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');	
	
}

function get_disabled_modules_list ()	
{
	
	var checkbox_value = "";
    jQuery(".uultra-my-modules-checked").each(function () {
		
        var ischecked = $(this).is(":checked");
       
	    if (ischecked) 
		{
            checkbox_value += $(this).val() + "|";
        }
		
		
    });
	
	return checkbox_value;		
}

function sortable_user_menu()
{
	 var itemList = jQuery('#uultra-user-menu-option-list');
	 
	 itemList.sortable({
		  cursor: 'move',
          update: function(event, ui) {
           // $('#loading-animation').show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'uultra_sort_user_menu_ajax', // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   uultra_reload_user_menu_customizer();
				  				   
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    // alert('There was an error saving the updates');
                  //  $('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
}

function getbwp_reload_custom_fields_set ()	
{
	
	jQuery("#getbwp-spinner").show();
	
	 var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
		
		jQuery.post(ajaxurl, {
							action: 'getbwp_reload_custom_fields_set', 'getbwp_custom_form': uultra_custom_form
									
							}, function (response){									
																
							jQuery("#uu-fields-sortable").html(response);							
							sortable_fields_list();
							
							jQuery("#getbwp-spinner").hide();
							
																
														
		 });
		
}


function sortable_categories_list ()
{
	var itemList = jQuery('#category-list-sortable');		
   
    itemList.sortable({
		cursor: 'move',
        update: function(event, ui) {
        jQuery("#getbwp-spinner").show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'getbwp_sort_categories_list', // Tell WordPress how to handle this ajax request					
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {				
				  
                   return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    $('#getbwp-spinner').hide(); // Hide the loading animation					
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
	
}

function sortable_fields_list ()
{
	var itemList = jQuery('#uu-fields-sortable');	 
	var uultra_custom_form =  jQuery('#uultra__custom_registration_form').val();
   
    itemList.sortable({
		cursor: 'move',
        update: function(event, ui) {
        jQuery("#getbwp-spinner").show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'sort_fileds_list', // Tell WordPress how to handle this ajax request
					getbwp_custom_form: uultra_custom_form, // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                   // $('#loading-animation').hide(); // Hide the loading animation
				   getbwp_reload_custom_fields_set();
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    $('#getbwp-spinner').hide(); // Hide the loading animation
					//alert(e);
                    // alert('There was an error saving the updates');
                    
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    }); 
	
	
}



function getbwp_load_appointment_payments(appointment_id)	
{					
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_payments_list",  "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#getbwp-payments-cont-res").html(res);					    
						

						}
				});	
	
}

function getbwp_load_appointment_notes(appointment_id)	
{					
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_notes_list",  "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#getbwp-notes-cont-res").html(res);					    
						

						}
				});	
	
}

function getbwp_load_staff_under_category(appointment_id)	
{
	
	var b_category=   jQuery("#getbwp-category").val();
							
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "get_cate_dw_admin_ajax", "b_category": b_category, "appointment_id": appointment_id},
					
					success: function(data){						
						
						var res = data;						
						jQuery("#getbwp-staff-booking-list").html(res);					    
						

						}
				});	
	
}

function getbwp_load_staff_adm(staff_id )	
{

	setTimeout("getbwp_load_staff_list_adm()", 1000);
	setTimeout("getbwp_load_staff_details(" + staff_id +")", 1000);
	
}

function getbwp_load_staff_list_adm()	
{
	jQuery("#getbwp-spinner").show();
	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_staff_list_admin_ajax"},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#getbwp-staff-list").html(res);
						jQuery("#getbwp-spinner").hide();					    
						
												

						}
				});	
	
}

function getbwp_load_staff_details(staff_id)	
{
	jQuery("#getbwp-spinner").show();	
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_staff_details_admin", "staff_id": staff_id},
					
					success: function(data){					
						
						var res = data;						
						jQuery("#getbwp-staff-details").html(res);					
						jQuery( "#tabs-bupro" ).tabs({collapsible: false	});						
						jQuery("#getbwp-spinner").hide();	
						
						getbwp_rebuild_dom_date_picker();	
						jQuery('.color-picker').wpColorPicker();									    
						

						}
				});	
	
}



function getbwp_edit_appointment_inline(appointment_id, conf_message, show_conf_message)	
{
	
	jQuery("#getbwp-spinner").show();
	
	jQuery.ajax({
				  type: 'POST',
				  url: ajaxurl,
				  data: {"action": "getbwp_admin_edit_appointment", 
				         "appointment_id": appointment_id},
						
					success: function(data){					
														
							jQuery("#getbwp-appointment-edit-box" ).html( data );
							jQuery("#getbwp-appointment-edit-box" ).dialog( "open" );
							
							var uultra_date_format =  jQuery('#uultra_date_format').val();			
							if(uultra_date_format==''){uultra_date_format='dd/mm/yy'}	
						
							jQuery( ".bupro-datepicker" ).datepicker({ 
								showOtherMonths: true, 
								dateFormat: getbwp_admin_v98.bb_date_picker_format, 
								closeText: GBPDatePicker.closeText,
								currentText: GBPDatePicker.currentText,
								monthNames: GBPDatePicker.monthNames,
								monthNamesShort: GBPDatePicker.monthNamesShort,
								dayNames: GBPDatePicker.dayNames,
								dayNamesShort: GBPDatePicker.dayNamesShort,
								dayNamesMin: GBPDatePicker.dayNamesMin,
								firstDay: GBPDatePicker.firstDay,
								isRTL: GBPDatePicker.isRTL,
								 minDate: 0
							 });
							
							jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');						
							jQuery("#getbwp-spinner").hide();						
							
							//load staff							
							getbwp_load_staff_under_category(appointment_id);							
							setTimeout("getbwp_load_appointment_payments(" + appointment_id +")", 1000);
							setTimeout("getbwp_load_appointment_notes(" + appointment_id +")", 1000);
							
							
							if(show_conf_message=='yes')
							{
							jQuery("#getbwp-new-app-conf-message" ).html( conf_message );
							jQuery("#getbwp-new-app-conf-message" ).dialog( "open" );
							
							}
							
													
							
							
							
					}
	});
	
	
}

function hidde_noti (div_d)
{
		jQuery("#"+div_d).slideUp();		
		
}
