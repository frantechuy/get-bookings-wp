jQuery(document).ready(function($) {
	
	
	jQuery(document).on("click", ".ubp-appo-change-status", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();	
			
			
			var appointment_id = jQuery(this).attr("appointment-id");			
			var appointment_status =  jQuery(this).attr("appointment-status");
			var getbwp_type =  jQuery(this).attr("getbwp-type");	
			var getbwp_status = jQuery(this).attr("getbwp-status");		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_appointment_status",
					"appointment_id": appointment_id,
					"appointment_status": appointment_status},
					
					success: function(data){					
												
						//reload appointment list						
						
						jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "getbwp_get_appointments_quick",
								"status": getbwp_status,
								"type": getbwp_type},
								
								success: function(data){					
															
									jQuery("#getbwp-appointment-list" ).html( data );
									//$fullCalendar.fullCalendar( 'refetchEvents' );													
															
									
								}
							});
						
												
						
						}
				});
			
			
			 // Cancel the default action
			
    		e.preventDefault();
			 
				
    });
	
	
	
	
	
	jQuery(document).on("click", ".getbwp-appointment-edit-module", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();				
			
			var appointment_id = jQuery(this).attr("appointment-id");		
			getbwp_edit_appointment_inline(appointment_id,null,'no');		
			
    		e.preventDefault();
			 
				
    });
	
	jQuery(document).on("click", ".getbwp-appointment-delete-module", function(e) {
			
			e.preventDefault();	
			
			
			if (confirm(getbwp_admin_v98.are_you_sure)) {
				
				jQuery("#getbwp-spinner").show();				
				
				var appointment_id = jQuery(this).attr("appointment-id");	
					
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_delete_appointment",
						"appointment_id": appointment_id},
						
						success: function(data){	
						
						window.location.reload();				
													
												
													
							
							}
					});	
				
				
				}	
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".ubp-payment-change-status", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();				
			
			var payment_id = jQuery(this).attr("payment-id");			
			var order_status =  jQuery(this).attr("order-status");
			var getbwp_type =  jQuery(this).attr("getbwp-type");	
			var getbwp_status = jQuery(this).attr("getbwp-status");		
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_payment_status_inline",
					"payment_id": payment_id,
					"order_status": order_status},
					
					success: function(data){					
												
						jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "getbwp_get_appointments_quick",
								"status": getbwp_status,
								"type": getbwp_type},
								
								success: function(data){				
															
									jQuery("#getbwp-appointment-list" ).html( data );
									//$fullCalendar.fullCalendar( 'refetchEvents' );								
									
								}
							});
						
												
						
						}
				});
			
			
    		e.preventDefault();
			 
				
    });
	
	
	jQuery(document).on("click", ".getbwp-adm-see-appoint-list-quick", function(e) {
			
			e.preventDefault();	
			jQuery("#getbwp-spinner").show();	
			
			
			var getbwp_status = jQuery(this).attr("getbwp-status");			
			var getbwp_type =  jQuery(this).attr("getbwp-type");	
			
			if(getbwp_type=='bystatus' && getbwp_status==0){jQuery('#getbwp-appointment-list').dialog('option', 'title', getbwp_admin_v98.msg_quick_list_pending_appointments);}
			
			if(getbwp_type=='bystatus' && getbwp_status==2){jQuery('#getbwp-appointment-list').dialog('option', 'title', getbwp_admin_v98.msg_quick_list_cancelled_appointments);}
			
			if(getbwp_type=='bystatus' && getbwp_status==3){jQuery('#getbwp-appointment-list').dialog('option', 'title', getbwp_admin_v98.msg_quick_list_noshow_appointments);}
			
			if(getbwp_type=='byunpaid'){jQuery('#getbwp-appointment-list').dialog('option', 'title', getbwp_admin_v98.msg_quick_list_unpaid_appointments);}
			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_appointments_quick",
					"status": getbwp_status,
					"type": getbwp_type},
					
					success: function(data){					
												
						jQuery("#getbwp-appointment-list" ).html( data );	
						jQuery("#getbwp-appointment-list" ).dialog( "open" );	
						jQuery("#getbwp-spinner").hide();	
						
												
						
						}
				});
			
			
			
    		e.preventDefault();
			 
				
    });
	
		
	/* check appointments */	
	jQuery( "#getbwp-appointment-list" ).dialog({
			autoOpen: false,			
			width: '400', //   			
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
	
	jQuery(document).on("click", "#getbwp-adm-confirm-reschedule-btn", function(e) {
			
			e.stopPropagation();	
			
			var date_to_book =  jQuery("#getbwp_booking_date").val();
			var notify_client =  jQuery("#getbwp_notify_client_reschedule").val();
			var service_and_staff_id =  jQuery("#getbwp_service_staff").val();
			var time_slot =  jQuery("#getbwp_time_slot").val();
			var booking_id =  jQuery("#getbwp_appointment_id").val();		
			
			if(time_slot==''){alert(err_message_time_slot); return;}			
			if(jQuery("#getbwp-category").val()==''){alert(err_message_service); return;}
			if(jQuery("#getbwp-start-date").val()==''){alert(err_message_start_date); return;}
			
			jQuery("#getbwp-steps-cont-res").html(message_wait_availability);
					
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_appointment_confirm_reschedule", 
						   "getbwp_booking_date": date_to_book,
						   "getbwp_service_staff": service_and_staff_id,
						   "getbwp_time_slot": time_slot,
						   "booking_id": booking_id,
						   "notify_client": notify_client},
					
					success: function(data){						
						
						var res = data;							
						jQuery("#getbwp-steps-cont-res-edit").html(res);						
						//$fullCalendar.fullCalendar( 'refetchEvents' );
						
						jQuery("#getbwp-confirmation-cont" ).html( gen_message_rescheduled_conf );
						jQuery("#getbwp-confirmation-cont" ).dialog( "open" );
						
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });
	
	jQuery(document).on("click", "#getbwp-adm-update-appoint-status-btn", function(e) {
			
			e.preventDefault();		
				
			var appointment_id =  jQuery(this).attr("appointment-id");
			jQuery("#getbwp-spinner").show();						
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_appointment_status_options", 
						   "appointment_id": appointment_id
						  },
					
					success: function(data){						
						
												
						jQuery("#getbwp-appointment-change-status" ).html( data );
						jQuery("#getbwp-appointment-change-status" ).dialog( "open" );						
						jQuery("#getbwp-spinner").hide();
						
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });
	
	
	
	
	jQuery(document).on("click", ".getbwp-adm-change-appoint-status-opt", function(e) {
			
			e.preventDefault();		
				
			var appointment_id =  jQuery(this).attr("appointment-id");
			var appointment_status =  jQuery(this).attr("appointment-status");
			
			jQuery("#getbwp-spinner").show();						
				
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_appo_status_ed", 
						   "appointment_id": appointment_id,
						   "appointment_status": appointment_status
						  },
					
					success: function(data){			
							
					    jQuery("#getbwp-app-status" ).html( data );
						jQuery("#getbwp-spinner").hide();	
						//$fullCalendar.fullCalendar( 'refetchEvents' );	
						jQuery("#getbwp-appointment-change-status" ).dialog( "close" );											
										
										
											

						}
				});				
			
				
			
    		e.stopPropagation(); 
				
    });	
	/* open new appointment */	
	jQuery( "#getbwp-appointment-new-box" ).dialog({
			autoOpen: false,			
			width: '780', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {	
			
				jQuery("#getbwp-appointment-new-box" ).html( '' );								
				jQuery( this ).dialog( "close" );
			},
			
			"Create": function() {				
				
				var getbwp_time_slot=   jQuery("#getbwp_time_slot").val();
				var getbwp_booking_date=   jQuery("#getbwp_booking_date").val();
				var getbwp_client_id=   jQuery("#getbwp_client_id").val();
				var getbwp_service_staff=   jQuery("#getbwp_service_staff").val();
				var getbwp_notify_client=   jQuery("#getbwp_notify_client").val();
				
				
				if(jQuery("#getbwp-category").val()==''){alert(err_message_service); return;}
				if(jQuery("#getbwp-start-date").val()==''){alert(err_message_start_date); return;}
				if(getbwp_client_id==''){alert(err_message_client); return;}	
				if(getbwp_time_slot==''){alert(err_message_time_slot); return;}					
				
				jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_admin_new_appointment_confirm", 
						       "getbwp_time_slot": getbwp_time_slot,
							   "getbwp_booking_date": getbwp_booking_date,
							   "getbwp_client_id": getbwp_client_id,
							   "getbwp_service_staff": getbwp_service_staff,
							   "getbwp_notify_client": getbwp_notify_client },
						
						success: function(data){
							
							//$fullCalendar.fullCalendar( 'refetchEvents' );
							
							jQuery("#getbwp-appointment-new-box" ).html( '' );										
							jQuery("#getbwp-appointment-new-box" ).dialog( "close" );
							
							//edit 
							
							var res =jQuery.parseJSON(data);				
							
							getbwp_edit_appointment_inline(res.booking_id, res.content, 'yes');
							
					
							}
					});
					
					
				
			
			}
			
			
			},
			close: function() {
				
				jQuery("#getbwp-appointment-new-box" ).html( '' );	
			
			
			}
	});
	
	
	/* appointment status */	
	jQuery( "#getbwp-appointment-change-status" ).dialog({
			autoOpen: false,			
			width: '400', // overcomes width:'auto' and maxWidth bug
   			
			responsive: true,
			fluid: true, //new option
			modal: true,
			buttons: {			
			
			"Cancel": function() {	
			
				jQuery("#getbwp-appointment-change-status" ).html( '' );								
				jQuery( this ).dialog( "close" );
			}			
			
			},
			close: function() {
				
				jQuery("#getbwp-appointment-new-box" ).html( '' );	
			
			
			}
	});

	

});