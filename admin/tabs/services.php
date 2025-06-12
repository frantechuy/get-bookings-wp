<?php
global $getbookingwp;

$first_service_id = $getbookingwp->service->get_first_service_list();		
?>
        
        <div class="getbwp-sect getbwp-welcome-panel">         
           <div class="getbwp-services">
           		<div class="getbwp-categories" id="getbwp-categories-list">
                                 
                </div>
                
                <div class="getbwp-services" id="getbwp-services-list">
                </div>
           </div>
        </div>
        
        <div id="getbwp-service-editor-box"></div>
        <div id="getbwp-service-variable-pricing-box"  title="<?php _e('Set Flexible Pricing','get-bookings-wp')?>"></div>
        <div id="getbwp-service-add-category-box" title="<?php _e('Add Category','get-bookings-wp')?>"></div>
        
        
         <script type="text/javascript">		 
			 var err_message_category_name ="<?php _e('Please input a name.','get-bookings-wp'); ?>";  
		   		 
			 getbwp_load_categories();
			 getbwp_load_services(<?php echo esc_attr($first_service_id);?>);
		 </script>
<div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>