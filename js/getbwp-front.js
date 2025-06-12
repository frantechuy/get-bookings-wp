if(typeof $ == 'undefined'){
	var $ = jQuery;
}
var ajaxurl = GETBWPFRONTV.ajaxUrl;

(function($) {
    jQuery(document).ready(function () { 
	
	   "use strict"; 
	   if(getbwp_pro_front.country_detection != 'no'  ){		   
	   
		jQuery("#reg_telephone").intlTelInput({

			  geoIpLookup: function(callback) {
				 jQuery.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				   var countryCode = (resp && resp.country) ? resp.country : "";
				  callback(countryCode);
				});
			   },
			  // hiddenInput: "full_number",
				initialCountry: "auto",
			  utilsScript: "../js/int-phone-code/js/utils.js"
			});
		
	   }else{
		   
		   }
	   
	   // Adding jQuery Datepicker
		jQuery(function() {
			
			
	});

	$( ".nuv-fron-date-picker-d" ).datepicker({ 
		showOtherMonths: true, 
		dateFormat: getbwp_pro_front.bb_date_picker_format, 
		closeText: GETBWPDatePicker.closeText,
		currentText: GETBWPDatePicker.currentText,
		monthNames: GETBWPDatePicker.monthNames,
		monthNamesShort: GETBWPDatePicker.monthNamesShort,
		dayNames: GETBWPDatePicker.dayNames,
		dayNamesShort: GETBWPDatePicker.dayNamesShort,
		dayNamesMin: GETBWPDatePicker.dayNamesMin,
		firstDay: GETBWPDatePicker.firstDay,
		isRTL: GETBWPDatePicker.isRTL,
		 minDate: 0,
		 onSelect: function(dateText, inst) { 
			var current_date = $('#nuv-fron-date-picker-d').val();	
			if(current_date==''){
				current_date = $('#nuv-fron-date-picker-c').val();	
			}
			$('#nuv-fron-date-picker').val(current_date)	;	
			var b_date = current_date;													
			$( "#getbwp-filter-date" ).trigger( "click" );
		}

		
	}

	);
	$("#ui-datepicker-div-left").wrap('<div class="ui-datepicker-wrapper" />');
	
	jQuery(document).on("click", ".getbwp_payment_options", function(e) {
		
		var payment_method =  jQuery(this).attr("value");
		if(payment_method=='stripe')
		{
			$(".getbwp-profile-field-cc").slideDown();	
			$("#getbwp-strip-cc-form").slideDown();	
			
			$("#card-button").show();
			$("#getbwp-btn-book-app-confirm").hide();	
			
		}else{
			
			$(".getbwp-profile-field-cc").slideUp();
			$("#card-button").hide();
			$("#getbwp-btn-book-app-confirm").show();
						
		}			
				
     });
	   
	 
	//this loads step 2	
	jQuery(document).on("click", ".getbwp-btn-delete-cart-item", function(e) {
			
		var cart_item=   jQuery(this).attr("item-cart-id");	
								
    	jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_delete_cart_item", "cart_item": cart_item},
					
					success: function(data){					
																	
					
						getbwp_reload_cart();	
										    
						

						}
				});			
			
			 
    		e.preventDefault();		 
				
    });
		
	jQuery(document).on("click", "#getbwp-btn-clean-cart", function(e) {
						
		var cart_item=   jQuery(this).attr("item-cart-id");	
    	jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_clear_cart", "cart_item": cart_item},
					
					success: function(data){					
																	
						getbwp_reload_cart();	
										    
						}
		});			
			
			 
    	e.preventDefault();		 
				
    });
		
	//checkout page with form
	jQuery(document).on("click", "#getbwp-btn-checkout-cart", function(e) {
			
			var template_id=   jQuery("#template_id").val();
	 		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_display_cart_checkout", "template_id": template_id},
					
					success: function(data){					
																	
					
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							getbwp_update_booking_steps(3);						
						}
											
						jQuery("#getbwp-steps-cont-res").html(res.content);
										    
						getbwp_phone_format();

						}
				});			
			
			 
    		e.preventDefault();		 
				
        });

	$(document).on("click", "#getwp-res-front-to-min-rates", function(e) {
			
		$( "#getbwp-filter-date" ).trigger( "click" );			 
    	e.preventDefault();	 
				
    });
		
		
		
	//this loads step 2	
	jQuery(document).on("click", ".getbwp-btn-next-step1", function(e) {
						
			var b_category=   jQuery(this).attr("data-cate-id");
			var b_date=   jQuery("#nuv-fron-date-picker").val();

			if(b_date==''){
				b_date=   jQuery("#getbwp_date").val();
			}
			
			var b_staff=   jQuery(this).attr("data-staff-id");;
			var b_location=   jQuery("#getbwp-filter-id").val();
			var template_id=   jQuery("#template_id").val();
			var booking_form_type=   jQuery("#getbwp_booking_form_type").val();	
			var hidde_staff_photo=   jQuery("#hidde_staff_photo").val();
			var book_from_staff_profile=   jQuery("#book_from_staff_profile").val();
			var completeCalled = false;	

			var scroll_div ='getbwp-front-cont';
			var p_top = 0;
			if($("#template").val()=='appointment_side_bar'){
				//scroll_div ='getbwp-time-sl-div-1';
				p_top = 300;					
			}

			getbwp_d_f(true);
			jQuery('body, html').animate({scrollTop: jQuery("#"+scroll_div).offset().top  + p_top  }, 1000,

			function() {

				if(!completeCalled){

					completeCalled = true;

					jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {"action": "getbwp_book_step_2", 
						"b_category": b_category, 
						"b_date": b_date , 
						"b_staff": b_staff, 
						"b_location": b_location, 
						"template_id": template_id,
						"template": $("#template").val(),
						"hidde_staff_photo": hidde_staff_photo, 
						"book_from_staff_profile": book_from_staff_profile },
						
						success: function(data){						
							
							var res =jQuery.parseJSON(data);						
							if(res.response=='OK')
							{
								getbwp_update_booking_steps(3);						
							}
												
							jQuery("#getbwp-steps-cont-res").html(res.content);	
							getbwp_d_f(false);
							
							jQuery( ".getbwp-datepicker" ).datepicker({ 
								
								showOtherMonths: true, 
								dateFormat: getbwp_pro_front.bb_date_picker_format, 
								closeText: GETBWPDatePicker.closeText,
								currentText: GETBWPDatePicker.currentText,
								monthNames: GETBWPDatePicker.monthNames,
								monthNamesShort: GETBWPDatePicker.monthNamesShort,
								dayNames: GETBWPDatePicker.dayNames,
								dayNamesShort: GETBWPDatePicker.dayNamesShort,
								dayNamesMin: GETBWPDatePicker.dayNamesMin,
								firstDay: GETBWPDatePicker.firstDay,
								isRTL: GETBWPDatePicker.isRTL,
								 minDate: 0,
								 onSelect: function(dateText, inst) { 
									var current_date = $('#nuv-fron-date-picker').val();							
									var b_date = current_date;													
									$( "#getbwp-filter-date" ).trigger( "click" );
								}
							}

							);

							$( ".nuv-fron-date-picker-d" ).datepicker({ 
								defaultDate: b_date,
								showOtherMonths: true, 
								dateFormat: getbwp_pro_front.bb_date_picker_format, 
								closeText: GETBWPDatePicker.closeText,
								currentText: GETBWPDatePicker.currentText,
								monthNames: GETBWPDatePicker.monthNames,
								monthNamesShort: GETBWPDatePicker.monthNamesShort,
								dayNames: GETBWPDatePicker.dayNames,
								dayNamesShort: GETBWPDatePicker.dayNamesShort,
								dayNamesMin: GETBWPDatePicker.dayNamesMin,
								firstDay: GETBWPDatePicker.firstDay,
								isRTL: GETBWPDatePicker.isRTL,
								 minDate: 0,
								 onSelect: function(dateText, inst) { 
									var current_date = $('#nuv-fron-date-picker-c').val();	
									$('#nuv-fron-date-picker').val(current_date)	;	
									var b_date = current_date;													
									$( "#getbwp-filter-date" ).trigger( "click" );
								}
						
								
								}
							);



						
							jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
							
						}
					});		
					
				}
			}
		);

    	e.preventDefault();		 
				
        });

	//this loads step 2	with btn
	jQuery(document).on("click", "#getbwp-btn-make-search", function(e) {
						
	
		var b_category=   jQuery("#getbwp-category").val();		
		var b_date=   jQuery("#nuv-fron-date-picker").val();

		if(b_date==''){
			b_date=   jQuery("#getbwp_date").val();
		}
		

		var b_staff=   jQuery("#getbwp-staff").val();
		var b_location=   jQuery("#getbwp-filter-id").val();
		var template_id=   jQuery("#template_id").val();
	
		var template=   jQuery("#booking_template").val();	
		var hidde_staff_photo=   jQuery("#hidde_staff_photo").val();
		var book_from_staff_profile=   jQuery("#book_from_staff_profile").val();

		var week_days=  nuva_get_checked_items('book_checked_week_day');

		var scroll_div ='getbwp-front-cont';

		var p_top = 0;
		if(template=='appointment_side_bar'){
			//scroll_div ='getbwp-time-sl-div-1';	
			
			p_top = 300;
		}

		var completeCalled = false;	
		getbwp_d_f(true);
		jQuery('body, html').animate({scrollTop: jQuery("#"+scroll_div).offset().top   + p_top }, 1000,

		function() {

			if(!completeCalled){

				completeCalled = true;

				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_book_step_2", 
					"b_category": b_category, 
					"b_date": b_date , 
					"b_staff": b_staff, 
					"b_location": b_location, 
					"template_id": template_id,
					"template": template,
					"hidde_staff_photo": hidde_staff_photo, 
					"book_from_staff_profile": book_from_staff_profile,
					"week_days": week_days
				
					},
					
					success: function(data){						
						
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK')
						{
							getbwp_update_booking_steps(3);						
						}
											
						jQuery("#getbwp-steps-cont-res").html(res.content);	
						getbwp_d_f(false);


						
						jQuery( ".getbwp-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0,
							 onSelect: function(dateText, inst) { 
								var current_date = $('#nuv-fron-date-picker').val();							
								var b_date = current_date;													
								$( "#getbwp-filter-date" ).trigger( "click" );
							}
						}

						);

						$( ".nuv-fron-date-picker-d" ).datepicker({ 
							defaultDate: b_date,
							showOtherMonths: true, 
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0,
							 onSelect: function(dateText, inst) { 
								var current_date = $('#nuv-fron-date-picker-c').val();	
								$('#nuv-fron-date-picker').val(current_date)	;	
								var b_date = current_date;													
								$( "#getbwp-filter-date" ).trigger( "click" );
							}
					
							
							}
						);
					
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
						
					}
				});		
				
			}
		}
	);

	e.preventDefault();		 
			
	});
		
		jQuery(document).on("click", "#getbwp-btn-show-cart", function(e) {
			
			getbwp_reload_cart(); 	 
    		e.preventDefault();		 
				
        });
		
		jQuery(document).on("change", "#getbwp-category, #getbwp-filter-id", function(e) {
			var b_category=   jQuery("#getbwp-category").val();
			var filter_id=   jQuery("#getbwp-filter-id").val();
			var template_id=   jQuery("#template_id").val();	
			
			$('#getbwp-staff').prop('disabled', 'disabled');			
			$('#getbwp-staff option:first-child').attr("selected", "selected");
			$('#getbwp-staff option:first-child').text(getbwp_pro_front.message_wait_staff_box);				
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_load_dw_of_staff", 
					"b_category": b_category, 
					"filter_id": filter_id , 
					"template_id": template_id},
					
					success: function(data){
						
						
						var res = data;								
						jQuery("#getbwp-steps-cont-res-selectors-staff").html(res);
						jQuery("#getbwp-steps-cont-res-selectors-staff").slideDown();
						jQuery("#getbwp-steps-cont-res-selectors-weekdays").show();		

						$('#getbwp-staff').prop('disabled', false);	
						getbwp_update_booking_steps(2);	
						
			

						}
				});			
			
			
    		e.preventDefault();		 
				
        });

	/*Load Staff*/
	jQuery(document).on("click", ".getbwp-stores-front-serv-staff", function(e) {

		e.preventDefault();
		var rand_id =  jQuery(this).attr("data-nuve-rand-id");
		var rand_key =  jQuery(this).attr("data-nuve-rand-key");
		var location_id =  jQuery(this).attr("data-location");		

		getbwp_d_f(true);
		var completeCalled = false;	
		jQuery('body, html').animate({scrollTop: jQuery("#getbwp-front-cont").offset().top    }, 1000,

		function() {
			if(!completeCalled){
				completeCalled = true;
				jQuery.ajax({
								type: 'POST',
								url: ajaxurl,
								data: {"action": "getbwp_load_list_staff_serv",
									"b_category": rand_id,
									"rand_key": rand_key,
									"location_id": location_id
									},

								success: function(data){

									jQuery("#getbwp-steps-cont-res").html(data);
									getbwp_d_f(false);
									getbwp_update_booking_steps(2);
									
									var date = new Date();
									date.setDate(date.getDate());

									$('#nuv-fron-date-picker , #nuv-fron-date-picker-2').datepicker({
										todayBtn: true,
										numberOfMonths:2,
										//language: "pt-BR",
										autoclose: true,
										startDate: date,

										todayHighlight: true

									}

							).on('changeDate', function(e) {

								$('#nuv-fron-date-picker').val(e.format([0]));

							});


							}
					});

				}
			}
		);

	});

	jQuery(document).on("click", "#getbwp-back-to-staff-serv", function(e) {

		e.preventDefault();
		var rand_id =  jQuery(this).attr("cate-id");
		var rand_key =  jQuery(this).attr("data-nuve-rand-key");
		var location_id =  jQuery(this).attr("data-location");
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "getbwp_load_list_staff_serv",
				"b_category": rand_id,
				"rand_key": rand_key,
				"location_id": location_id},
			success: function(data){
				jQuery("#getbwp-steps-cont-res").html(data);
				getbwp_update_booking_steps(2);			
			}
		});
	});

	jQuery(document).on("click", "#getbwp-back-to-servlist", function(e) {

		e.preventDefault();
		getbwp_d_f(true);
		getbwp_reload_serv_of_locations();

	});

	jQuery(document).on("change", "#getbwp-purchased-qty", function(e) {		
						
			var b_qty=   jQuery("#getbwp-purchased-qty").val();
			var service_id=   jQuery("#service_id").val();
			var staff_id=   jQuery("#staff_id").val();			
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_update_purchase_total", 
					"b_qty": b_qty, "service_id": service_id , "staff_id": staff_id},					
					success: function(data){		
						var res = data;
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK'){
							jQuery("#getbwp-total-booking-amount").html(res.amount_with_symbol);
							jQuery("#getbwp_service_cost").val(res.amount);
						}
					}
				});		
			
    		e.preventDefault();		 
        });				

		//this is for the li element on reduced layout
		jQuery(document).on("click", ".getbwp-btn-book-app-li", function(e) {
			
			e.preventDefault();	
			
			var date_to_book =  jQuery(this).attr("getbwp-data-date");
			var service_and_staff_id =  jQuery(this).attr("getbwp-data-service-staff");
			var time_slot =  jQuery(this).attr("getbwp-data-timeslot");
			var form_id =  jQuery("#getbwp-custom-form-id").val();
			var location_id =  jQuery("#getbwp-filter-id").val();
			var field_legends =  jQuery("#field_legends").val();
			var placeholders =  jQuery("#placeholders").val();
			var template_id =  jQuery("#template_id").val();
			var template =  jQuery("#template").val();
			var show_cart =  jQuery("#getbwp_cart_id").val();
			var woocommerce_active =  jQuery("#getbwp-woocommerce").val();
			var max_capacity =   jQuery(this).attr("getbwp-max-capacity"); 
			var max_available =   jQuery(this).attr("getbwp-max-available"); 			
			jQuery("#getbwp-err-message" ).html( '' );		
			
			var completeCalled = false;
			getbwp_d_f(true);			
			jQuery('body, html').animate({scrollTop: jQuery("#getbwp-front-cont").offset().top   }, 1000,
			
				function() {					
					if(!completeCalled){						
						completeCalled = true;					
						jQuery.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {"action": "getbwp_book_step_3", 
							"date_to_book": date_to_book, 
							"service_and_staff_id": service_and_staff_id  , 
							"time_slot": time_slot , 
							"form_id": form_id , 
							"location_id": location_id  , 
							"field_legends": field_legends  , 
							"placeholders": placeholders,
							"woocommerce_active": woocommerce_active,
							"template_id": template_id ,
							"template": template ,
							"max_capacity": max_capacity,
							"max_available": max_available },
							
							success: function(data){
								
								var res = data;
								var res =jQuery.parseJSON(data);
														
								if(res.response=='OK'){
									getbwp_update_booking_steps(4);						
								}							
								
								if(show_cart==1){									
									getbwp_reload_cart();
								
								}else{
									
									jQuery("#getbwp-steps-cont-res").html(res.content);
									$("#getbwp-registration-form").validationEngine({promptPosition: 'inline'});
									
								}

								getbwp_d_f(false);								
								//reoload phone format								
								getbwp_phone_format();

								if(getbwp_pro_front.stripe_is_active == 1 ){

									let elements = stripe.elements();
									let card = elements.create("card");							
									card.mount("#card-field");
									
									let cardButton = document.getElementById('card-button');
									let form = document.getElementById('payment-form');
									let errors = document.getElementById('card-errors'); //
									
									cardButton.addEventListener('click', function(evt){
										evt.preventDefault();
										
										stripe.createPaymentMethod('card', card).then(function(result) {
											if (result.error) {
												errors.textContent = result.error.message;
												return;
											}
											errors.textContent = "";
											
											var frm_validation  = $("#getbwp-registration-form").validationEngine('validate');	

											if(!frm_validation){
												return;
											}

											$( "#card-button" ).prop( "disabled", true );	
											
											const data = new FormData();
											data.append( 'action', 'getbwp_stripe_create_payment' );											
											data.append( 'payment_method_id',result.paymentMethod.id );
											data.append( 'firstname', $('#reg_display_name').val() );
											data.append( 'lastname',  $('#last_name').val()	);
											data.append( 'reg_user_email', $('#reg_user_email').val());
											const params = new URLSearchParams(data);

									
											fetch(ajaxurl, {
												method: 'POST',
												credentials: 'same-origin',
												headers: {
													'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
												},

												body:params
											})
											.then(function(responseBody) {

												return responseBody.json()
											})
											.then(handleServerResponse);

									     	});											 
											 
								});

							} //end if stripe active							
		
							}
						});	
					}	
				}
			);
						
    		e.preventDefault();		 
        });
		
		jQuery(document).on("click", "#getbwp-btn-book-app-confirm", function(e) {
			
			e.preventDefault();
			
			$("#getbwp-registration-form").validationEngine({promptPosition: 'inline'});				
			var frm_validation  = $("#getbwp-registration-form").validationEngine('validate');	
			
		
			//check if user is a staff member trying to purchase an own service			
			 if(getbwp_pro_front.country_detection != 'no'  ){
				 
				 var intlNumber = $("#reg_telephone").intlTelInput("getNumber");					
				jQuery("#full_number").val(intlNumber);
				
				var countryData = $("#reg_telephone").intlTelInput("getSelectedCountryData");
				
				jQuery("#full_number_prefix").val(countryData.dialCode);
				jQuery("#full_number_iso").val(countryData.iso2);
				 
				 				 
			}
			
			if(frm_validation)
			{
							
				var myRadioPayment = $('input[name=getbwp_payment_method]');
				var payment_method_selected = myRadioPayment.filter(':checked').val();				
				var payment_method =  jQuery("#getbwp_payment_method_stripe_hidden").val();
				
				if(payment_method=='stripe' && payment_method_selected=='stripe')
				{
					var wait_message = '<div class="getbwp_wait">' + getbwp_pro_front.wait_submit + '</div>';				
					jQuery('#getbwp-stripe-payment-errors').html(wait_message);					
					//getbwp_stripe_process_card();
				
				} else if (payment_method=='stripe' && payment_method_selected=='authorize') {
					
					
				
				}else{
					
					
					jQuery("#getbwp-message-submit-booking-conf").html(getbwp_pro_front.message_wait_availability);					
					$('#getbwp-btn-book-app-confirm').prop('disabled', 'disabled');					
					$("#getbwp-registration-form").submit();
				
				}
				
				
			}else{
				
			}
			
									
    		e.preventDefault();		 
				
        });
		
 
       
    }); //END READY
})(jQuery);

function getbwp_update_booking_steps(current_step){
	var show_cart =  jQuery("#getbwp_cart_id").val();	
	getbwp_update_booking_steps_remove_all();				
	jQuery( "#getbwp-step-rounded-" + current_step).removeClass( "inactive" ).addClass( "active" );
}

function getbwp_update_booking_steps_remove_all(){		
	jQuery( "#getbwp-step-rounded-1, #getbwp-step-rounded-2, #getbwp-step-rounded-3, #getbwp-step-rounded-4" ).removeClass( "active" ).addClass( "inactive" );				
}


function getbwp_load_step_4(order_key, payment_method){
		
		jQuery("#getbwp-steps-cont-res").html(getbwp_pro_front.message_wait_availability);			
			
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_book_step_4", 
					"order_key": order_key,
					"payment_method": payment_method},
					
					success: function(data){			
						var res = data;								
						jQuery("#getbwp-steps-cont-res").html(res);
						getbwp_update_booking_steps(4);			    

						}
				});	
	
}

function getbwp_load_staff_by_service(b_category){	
	
	var filter_id=   jQuery("#getbwp-filter-id").val();
	var template_id=   jQuery("#template_id").val();			
	$('#getbwp-staff').prop('disabled', 'disabled');		
	$('#getbwp-staff option:first-child').attr("selected", "selected");
	$('#getbwp-staff option:first-child').text(getbwp_pro_front.message_wait_staff_box);									
						
    jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_load_dw_of_staff", 
					"b_category": b_category, 
					"filter_id": filter_id , 
					"template_id": template_id},
					
					success: function(data){					
						
						var res = data;								
						jQuery("#getbwp-staff-booking-list").html(res);
						$('#getbwp-staff').prop('disabled', false);					    
						

						}
	});			
			
}


function getbwp_reload_serv_of_locations (){

	var category_ids =  jQuery("#getbwp-category-ids").val();	
	var available_legend =  jQuery("#getbwp-available-legend").val();	
	var available_text =  jQuery("#getbwp-available-text").val();	;
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {"action": "getbwp_get_categories_front_list",
				"category_ids": category_ids},

		success: function(data){

			jQuery("#getbwp-steps-cont-res").html(data);
			getbwp_d_f(false);		
			getbwp_update_booking_steps(1);

		} 
	});	
}

function getbwp_reload_serv_of_locations_dropdown (){

	var category_ids =  jQuery("#getbwp-category-ids").val();	
	var available_legend =  jQuery("#getbwp-available-legend").val();	
	var available_text =  jQuery("#getbwp-available-text").val();	;
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {"action": "getbwp_get_categories_front_list_dropdown",
				"category_ids": category_ids},

		success: function(data){

			jQuery("#getbwp-steps-cont-res-selectors").html(data);
			getbwp_d_f(false);		
			getbwp_update_booking_steps(1);

		} 
	});	
}

function nuva_get_checked_items (class_check_box)
{

	var checkbox_value = "";
	jQuery("."+class_check_box).each(function () {

		var ischecked = $(this).is(":checked");

		if (ischecked)
		{
			checkbox_value += $(this).val() + "-"  ;
		}

	});

	return checkbox_value;
}


function getbwp_phone_format(){
	

if(getbwp_pro_front.country_detection != 'no'  ){
	
	 jQuery("#reg_telephone").intlTelInput({
			  // allowDropdown: false,
			  // autoHideDialCode: false,
			  // autoPlaceholder: "off",
			  // dropdownContainer: "body",
			  // excludeCountries: ["us"],
			  // formatOnDisplay: false,
			  geoIpLookup: function(callback) {
				 jQuery.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				   var countryCode = (resp && resp.country) ? resp.country : "";
				  callback(countryCode);
				});
			   },
			   hiddenInput: "full_number",
				initialCountry: "auto",
			  // nationalMode: false,
			  // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			   placeholderNumberType: "MOBILE",
			  // preferredCountries: ['cn', 'jp'],
			  // separateDialCode: true,
			//  utilsScript: "../js/int-phone-code/js/utils.js"
			   utilsScript: getbwp_pro_front.country_util_url
			  
			  
			});
	}

}

function reload_availability(b_category, b_staff, b_location, template_id, b_date){

	var completeCalled = false;

	var scroll_div ='getbwp-front-cont';
	var p_top = 0;
	if($("#template").val()=='appointment_side_bar'){
		//scroll_div ='getbwp-time-sl-div-1';	
		p_top = 300;				
	}

	jQuery('body, html').animate({scrollTop: jQuery("#"+scroll_div).offset().top + p_top }, 1000,

		function() {
			if(!completeCalled){

				completeCalled = true;

				var hidde_staff_photo=   jQuery("#hidde_staff_photo").val();
				var book_from_staff_profile=   jQuery("#book_from_staff_profile").val();
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_book_step_2", 
					"b_category": b_category, 
					"b_date": b_date , 
					"b_staff": b_staff, 
					"b_location": b_location, 
					"template_id": template_id ,
					"template": $("#template").val(),
					"hidde_staff_photo": hidde_staff_photo, 
					"book_from_staff_profile": book_from_staff_profile },
					
					success: function(data){						
						
						var res =jQuery.parseJSON(data);						
						if(res.response=='OK'){
							getbwp_update_booking_steps(3);						
						}
											
						jQuery("#getbwp-steps-cont-res").html(res.content);	
						
						jQuery( ".getbwp-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0

						}

						).on('changeDate', function(e) {

							$('#nuv-fron-date-picker').val(e.format([0]));
							var current_date = $('#nuv-fron-date-picker').val();
							var b_date = current_date;
							reload_availability(b_category, b_staff, b_location, template_id, b_date);

						 });

						 $( ".nuv-fron-date-picker-d" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0,
							 onSelect: function(dateText, inst) { 
								var current_date = $('#nuv-fron-date-picker-c').val();	
								$('#nuv-fron-date-picker').val(current_date)	;	
								var b_date = current_date;													
								$( "#getbwp-filter-date" ).trigger( "click" );
							}
					
							
							}
						);

						 
					
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
						
						
						
						
						}
				});	
				
				
			}	 //end if
		}
	);




}

function getbwp_auto_display_staff(b_category){	
	
	jQuery("#getbwp-err-message" ).html( '' );			
	jQuery("#getbwp-steps-cont-res").html(getbwp_pro_front.message_wait_availability);				
				
	jQuery.ajax({
		type: 'POST',
		url: ajaxurl,
		data: {"action": "getbwp_load_list_staff_serv",
			"b_category": b_category
			},

		success: function(data){
			jQuery("#getbwp-steps-cont-res").html(data);
			
			
		}
	});			
	 
}

function getbwp_auto_display_slots(b_category, b_staff){	

			var b_date=   jQuery("#getbwp-start-date").val();
			var b_location=   jQuery("#getbwp-filter-id").val();
			var template_id=   jQuery("#template_id").val();
			var booking_form_type=   jQuery("#getbwp_booking_form_type").val();
			var hidde_staff_photo=   jQuery("#hidde_staff_photo").val();
			var book_from_staff_profile=   jQuery("#book_from_staff_profile").val();
			
			jQuery("#getbwp-err-message" ).html( '' );			
			jQuery("#getbwp-steps-cont-res").html(getbwp_pro_front.message_wait_availability);				
						
    		jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_book_step_2", 
					"b_category": b_category, 
					"b_date": b_date ,
					 "b_staff": b_staff, 
					 "b_location": b_location, 
					 "template_id": template_id,
					 "template": $("#template").val(),
					 "hidde_staff_photo": hidde_staff_photo, 
					 "book_from_staff_profile": book_from_staff_profile },
					
					success: function(data){						
						var res =jQuery.parseJSON(data);			
						jQuery("#getbwp-steps-cont-res").html(res.content);	
						//getbwp_update_date_picker(b_category, b_staff, b_location, template_id, b_date);

											
						jQuery( ".getbwp-datepicker" ).datepicker({ 
							showOtherMonths: true, 
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0,
							 onSelect: function(dateText, inst) { 

								
								var current_date = $('#nuv-fron-date-picker').val();							
								var b_date = current_date;													
								$( "#getbwp-filter-date" ).trigger( "click" );
							}
						}

						);

						$( ".nuv-fron-date-picker-d" ).datepicker({ 
							showOtherMonths: true, 
							defaultDate: b_date,
							dateFormat: getbwp_pro_front.bb_date_picker_format, 
							closeText: GETBWPDatePicker.closeText,
							currentText: GETBWPDatePicker.currentText,
							monthNames: GETBWPDatePicker.monthNames,
							monthNamesShort: GETBWPDatePicker.monthNamesShort,
							dayNames: GETBWPDatePicker.dayNames,
							dayNamesShort: GETBWPDatePicker.dayNamesShort,
							dayNamesMin: GETBWPDatePicker.dayNamesMin,
							firstDay: GETBWPDatePicker.firstDay,
							isRTL: GETBWPDatePicker.isRTL,
							 minDate: 0,
							 onSelect: function(dateText, inst) { 
								var current_date = $('#nuv-fron-date-picker-c').val();	
								$('#nuv-fron-date-picker').val(current_date)	;	
								var b_date = current_date;													
								$( "#getbwp-filter-date" ).trigger( "click" );
							}
					
							
							}
						);
					
						jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');				
						
		

					}
				});				
			 
}

function getbwp_update_date_picker(){

	jQuery( ".getbwp-datepicker" ).datepicker({ 
		showOtherMonths: true, 
		dateFormat: getbwp_pro_front.bb_date_picker_format, 
		closeText: GETBWPDatePicker.closeText,
		currentText: GETBWPDatePicker.currentText,
		monthNames: GETBWPDatePicker.monthNames,
		monthNamesShort: GETBWPDatePicker.monthNamesShort,
		dayNames: GETBWPDatePicker.dayNames,
		dayNamesShort: GETBWPDatePicker.dayNamesShort,
		dayNamesMin: GETBWPDatePicker.dayNamesMin,
		firstDay: GETBWPDatePicker.firstDay,
		isRTL: GETBWPDatePicker.isRTL,
		 minDate: 0

	}

	).on('changeDate', function(e) {

		$('#nuv-fron-date-picker').val(e.format([0]));
		var current_date = $('#nuv-fron-date-picker').val();
		var b_date = current_date;
		reload_availability(b_category, b_staff, b_location, template_id, b_date);

	 });

	 

	jQuery("#ui-datepicker-div").wrap('<div class="ui-datepicker-wrapper" />');
}

function getbwp_d_f(is_v){	

	if(is_v){

		jQuery("#getbwp-slider-background-filters").show();

	}else{

		jQuery("#getbwp-slider-background-filters").hide();


	}
	
	
}

function getbwp_reload_cart(){	
	
	var template_id=   jQuery("#template_id").val();
		
			jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "getbwp_get_shopping_cart","reload_cart": "reload_cart","template_id": template_id},
					
					success: function(data){					
						
						var res = data;								
						jQuery("#getbwp-steps-cont-res").html(res);
                        getbwp_update_booking_steps(33);						    
						

						}
						
						,
				  error: function(errorThrown){
					  alert(errorThrown);
				  } 
				});	
}

