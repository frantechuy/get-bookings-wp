<?php
global $getbookingwp, $getbwpcomplement;

$howmany = "20";
$year = "";
$month = "";
$day = "";
$status = "";
$avatar = "";
$edit = "";

if(isset($_GET["avatar"]) && $_GET["avatar"]!=''){
	
	$avatar = sanitize_text_field($_GET["avatar"]);
}

if(isset($_GET["edit"]) && $_GET["edit"]!=''){
	
	$edit = sanitize_text_field($_GET["edit"]);
}

$load_staff_id = $getbookingwp->userpanel->get_first_staff_on_list();


if(isset($_GET["ui"]) && $_GET["ui"]!=''){
	
	$load_staff_id=sanitize_text_field($_GET["ui"]);
}

if(isset($_GET["code"]) && $_GET["code"] !='' && isset($getbwpcomplement->googlecalendar)){
	session_start();
	
	$current_staff_id =sanitize_text_field($_SESSION["current_staff_id"]) ;
		
	if($current_staff_id!=''){				
		//google calendar.	
		$client = $getbwpcomplement->googlecalendar->auth_client_with_code(sanitize_text_field($_GET["code"]), $current_staff_id);	
		$load_staff_id=$current_staff_id;	
	}

}else{	

	$_SESSION["current_staff_id"] = $load_staff_id;

}


?>



     
        <div class="getbwp-sect ">
        
        <div class="getbwp-staff ">
        
        	
            
            
             <?php if($avatar==''){?>	
             
             
                 <div class="getbwp-staff-left " id="getbwp-staff-list">
            	
                          
            	
            	 </div>
                 
                 <div class="getbwp-staff-right " id="getbwp-staff-details">
                 </div>
            
            <?php }else{ //upload avatar?>
            
           <?php  

			$crop_image  ='';

			if(isset($_POST['crop_image'])){

				$crop_image = sanitize_text_field($_POST['crop_image']);


			}
		   
		  
		   if( $crop_image=='crop_image') //displays image cropper
			{
			
			 $image_to_crop = sanitize_text_field($_POST['image_to_crop']);
			 
			
			 ?>
             
             <div class="getbwp-staff-right-avatar " >
           		  <div class="pr_tipb_be">
                              
                            <?php echo  wp_kses($getbookingwp->userpanel->display_avatar_image_to_crop($image_to_crop, $avatar) , $getbookingwp->allowed_html);?>                          
                              
                   </div>
                   
             </div>
            
           
		    <?php }else{  
			
			$user = get_user_by( 'id', $avatar );
			?> 
            
            <div class="getbwp-staff-right-avatar " >
            
           
                   <div class="getbwp-avatar-drag-drop-sector"  id="getbwp-drag-avatar-section">
                   
                   <h3> <?php echo esc_attr($user->display_name)?><?php _e("'s Picture",'get-bookings-wp')?></h3>
                        
                             <?php 

							 echo wp_kses($getbookingwp->userpanel->get_user_pic( $avatar, 100, 'avatar', 'rounded', 'dynamic'), $getbookingwp->allowed_html);
							 
							 ?>

                                                    
                             <div class="uu-upload-avatar-sect">
                              
                                     <?php echo wp_kses($getbookingwp->userpanel->avatar_uploader($avatar), $getbookingwp->allowed_html)?>  
                              
                             </div>
                             
                        </div>  
                    
             </div>
             
             
              <?php }  ?>
            
             <?php }?>
        
        	
        </div>        
        </div>
        
        <div id="getbwp-breaks-new-box" title="<?php _e('Add Breaks','get-bookings-wp')?>"></div>
        
        <div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>
        
         <div id="getbwp-staff-editor-box"></div>
        
  

 <script type="text/javascript">
	
			
			 var message_wait_availability ='<img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e("Please wait ...","get-bookings-wp")?>'; 
			 
			 jQuery("#getbwp-spinner").hide();		 
			  
			  
			  
			  <?php if($avatar==''){?>	
			  
			   getbwp_load_staff_list_adm();
			   
				   <?php if($load_staff_id!=''){?>		  
				  
					setTimeout("getbwp_load_staff_details(<?php echo esc_attr($load_staff_id)?>)", 1000);
				  
				  <?php }?>
			  
			   <?php }?>	
				  
			  
		
	</script>
