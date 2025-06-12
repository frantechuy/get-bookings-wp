<?php
global $getbookingwp, $getbwpcomplement;
$preset_categories = '';
$form_type = '';

if($preset_categories!=''){ $category_ids = $preset_categories;}

$hide_staff= '';
$hide_service= '';
$show_location= '';

if($show_location==1){ $four_colums_class = 'getbwp-four-cols-booking';}
$show_cart = '';

if(isset($_GET['service_id']) && $_GET['service_id']!=''){

	$service_id = sanitize_text_field($_GET['service_id']);
}

if(isset($_GET['staff_id']) && $_GET['staff_id']!=''){

	$staff_id = sanitize_text_field($_GET['staff_id']);
}

if(isset($_GET['auto_display_slots']) && $_GET['auto_display_slots']!=''){

	$auto_display_slots = sanitize_text_field($_GET['auto_display_slots']);
}

if(isset($_GET['auto_display_staff']) && $_GET['auto_display_staff']!=''){

	$auto_display_staff = sanitize_text_field($_GET['auto_display_staff']);
}

$payment_url = false;

if(isset($_GET['getbwp_payment_method'])){

	$payment_url = true;
}


?>

<div class="getbwp-front-cont" id="getbwp-front-cont">  


<?php if(isset($display_steps) && $display_steps == 'yes'){?>

	<div class="getbwp-front-steps-bar" id="getbwp-front-steps-bar">

		<ul>
			<li>
				<div class="getbwp-steps-round active" id="getbwp-step-rounded-1"> 1 </div>
				<div class="getbwp-steps-label " id="getbwp-steps-label-1"> <?php echo esc_attr($getbookingwp->get_front_step_label(1))?> </div>
			</li>
			<li>
				<div class="getbwp-steps-round" id="getbwp-step-rounded-2"> 2 </div>
				<div class="getbwp-steps-label " id="getbwp-steps-label-2"> <?php echo esc_attr($getbookingwp->get_front_step_label(2))?> </div>
			</li>
			<li>
				<div class="getbwp-steps-round" id="getbwp-step-rounded-3"> 3 </div>
				<div class="getbwp-steps-label " id="getbwp-steps-label-3"> <?php echo esc_attr($getbookingwp->get_front_step_label(3))?> </div>
			</li>
			<li>
				<div class="getbwp-steps-round" id="getbwp-step-rounded-4"> 4 </div>
				<div class="getbwp-steps-label " id="getbwp-steps-label-4"> <?php echo esc_attr($getbookingwp->get_front_step_label(4))?> </div>
			</li>

		</ul>

	</div>

<?php }?>

<div class="getbwp-slider-background-filters" id="getbwp-slider-background-filters"> 

	<div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> </span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>

</div> 
        
         <div class="getbwp-book-info-text" id="getbwp-steps-cont-res" > 
          <p ><?php _e('Please wait...','get-bookings-wp')?></p>       
         </div>        

</div>


<?php if($hidde_staff_photo!='' && isset($getbwpcomplement)){?>
<input type="hidden"  id="hidde_staff_photo" name="hidde_staff_photo" value="<?php echo esc_attr($hidde_staff_photo)?>" />
 <?php }?>

 <?php if($book_from_staff_profile!='' && isset($getbwpcomplement)){?>
<input type="hidden"  id="book_from_staff_profile" name="book_from_staff_profile" value="<?php echo esc_attr($book_from_staff_profile)?>" />
 <?php }?>
 

<?php if($staff_id!='' && isset($getbwpcomplement)){?>
<input type="hidden"  id="getbwp-staff" name="getbwp-staff" value="<?php echo esc_attr($staff_id)?>" />
 <?php }?>
 
 
 <?php if($service_id!='' && isset($getbwpcomplement)){?>
<input type="hidden"  id="getbwp-category" name="getbwp-category" value="<?php echo esc_attr($service_id)?>" />
 <?php }?>

 <?php if($category_ids!=''){?>
<input type="hidden"  id="getbwp-category-ids" name="getbwp-category-ids" value="<?php echo esc_attr($category_ids)?>" />
 <?php }?>

 <input type="hidden"  id="getbwp-available-legend" name="getbwp-available-legend" value="<?php echo esc_attr($available_legend)?>" />

 <input type="hidden"  id="getbwp-available-text" name="getbwp-available-text" value="<?php echo esc_attr($available_text)?>" />
 
<input type="hidden"  id="getbwp-custom-form-id" name="getbwp-custom-form-id" value="<?php echo esc_attr($form_id)?>" />

<?php if($show_location!=1 && isset($getbwpcomplement)){?>
<input type="hidden"  id="getbwp-filter-id" name="getbwp-filter-id" value="<?php echo esc_attr($location_id)?>" />
<?php }?>

<?php if(isset($getbwpcomplement)){?>
<input type="hidden"  id="getbwp-woocommerce" name="getbwp-woocommerce" value="<?php echo esc_attr($activate_woocommerce)?>" />
<?php }?>

<input type="hidden"  id="display_steps" name="display_steps" value="<?php echo esc_attr($display_steps)?>" />


<input type="hidden"  id="redirect_url" name="redirect_url" value="<?php echo esc_attr($redirect_url)?>" />
<input type="hidden"  id="field_legends" name="field_legends" value="<?php echo esc_attr( $field_legends)?>" />
<input type="hidden"  id="placeholders" name="placeholders" value="<?php echo esc_attr( $placeholders)?>" />
<input type="hidden"  id="template_id" name="template_id" value="<?php echo esc_attr( $template_id)?>" />
<input type="hidden"  id="getbwp_booking_form_type" name="getbwp_booking_form_type" value="<?php echo esc_attr( $form_type)?>" />
<input type="hidden"  id="getbwp_cart_id" name="getbwp_cart_id" value="<?php echo esc_attr( $show_cart)?>" />
<input type="hidden"  id="getbwp_user_timezone" name="getbwp_user_timezone" value="" />


<script type="text/javascript">


<?php if($auto_display_staff=='no' && $auto_display_slots=='no' && !$payment_url){ ?>
			  
	getbwp_reload_serv_of_locations();			  
					  
<?php }?>
				
		 

<?php 
//when the services list is hidden and a service is pre-set
if($service_id!='' && $auto_display_staff=='yes' && !$payment_url && isset($getbwpcomplement)){?> 
 	
	 getbwp_auto_display_staff( "<?php echo esc_attr($service_id)?>" );
 
<?php }?>	
						 
 <?php if(isset($_GET['getbwp_order_key']) && $_GET['getbwp_order_key']!='' && $payment_url){ //load step 4, order completed
	
	
	?>
			  
	  getbwp_load_step_4("<?php echo esc_attr($_GET['getbwp_order_key'])?>", "<?php echo esc_attr($_GET['getbwp_payment_method'])?>");			  
			  
 <?php }?>
			  
<?php 
//when a service, staff and auto display is preset
if($service_id!='' && $staff_id!='' && $auto_display_slots=='yes' && !$payment_url){?>
 
 	getbwp_auto_display_slots( "<?php echo esc_attr($service_id)?>",  "<?php echo esc_attr($staff_id)?>" );
 
<?php }?>	


   <?php if( $getbookingwp->get_option('allow_timezone') =='1' && isset($getbwpcomplement)){  ?>	
   
  	  var currentTime = new Date(),
      hours = currentTime.getHours(),
      minutes = currentTime.getMinutes(),
	    offset_t = currentTime.getTimezoneOffset();
	   
	  var visitortimezone = -currentTime.getTimezoneOffset()/60;
	  
	  jQuery("#getbwp_user_timezone").val(visitortimezone);
   
   <?php }?>	  
		
	</script>
 