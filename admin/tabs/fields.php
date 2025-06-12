<?php 
$fields = get_option('getbwp_profile_fields');
ksort($fields);

global $getbookingwp, $getbwp_form,  $getbwpcomplement;

if(isset($getbwpcomplement))
{

	$forms = $getbwp_form->get_all();

}


$last_ele = end($fields);
$new_position = $last_ele['position']+1;

$meta_custom_value = "";
$qtip_classes = 'qtip-light ';
?>
<?php if(!isset($getbwpcomplement)){?>

<div class="getbwp-validation-sect ">
	  
	  <h3><?php _e('Create Custom Fields','get-bookings-wp'); ?></h3>
	  <p><?php _e("Creating custom fields for your booking form is available only on pro versions",'get-bookings-wp'); ?>.</p>  
	  <p> <a href="https://getbookingswp.com/pricing" target="_blank"><?php _e('CLICK HERE','get-bookings-wp'); ?></a> <?php _e(" to upgrade your plugin.",'get-bookings-wp'); ?></p>      
	  
 </div>

<?php }?>

<h1>
	<?php _e('Fields Customizer','get-bookings-wp'); ?>
</h1>
<p>
	<?php _e('Create and customize fields that displays on booking forms.','get-bookings-wp'); ?>
</p>


<p >
<div class='getbwp-ultra-success getbwp-notification' id="fields-mg-reset-conf"><?php _e('Fields have been restored','get-bookings-wp'); ?></div>

</p>



<?php if(isset($getbwpcomplement))
{?>

<div class="getbwp-ultra-sect" >




<select name="uultra__custom_registration_form " class="getbwp-btn-add" id="uultra__custom_registration_form">

				<option value="" selected="selected">

					<?php _e('Default Form','get-bookings-wp'); ?>

				</option>

                

                <?php foreach ( $forms as $key => $form )

				{?>

				<option value="<?php echo esc_attr($key)?>">

					<?php echo esc_attr($form['name']); ?>

				</option>

                

                <?php }?>

		</select>

        

        <input type="text" id="getbwp_custom_registration_form_name" class="getbwp-btn-add" name="uultra_custom_registration_form_name" />

        <a href="#getbwp-duplicate-form-btn" class="button button-secondary getbwp-btn-add"  id="getbwp-duplicate-form-btn"><i

	class="uultra-icon-plus"></i>&nbsp;&nbsp;<?php _e('Duplicate Current Form','get-bookings-wp'); ?>

</a>



<a href="#getbwp-add-field-btn" class="button button-secondary getbwp-btn-add"  id="getbwp-add-field-btn"></i><i class="fa fa-plus fa-lg"></i> <?php _e('Add New Field','get-bookings-wp'); ?>
</a>


</div>


<?php }?>

<div class="getbwp-ultra-sect getbwp-ultra-rounded" id="getbwp-add-new-custom-field-frm" >

<table class="form-table getbwp-add-form">

	

	<tr valign="top">
		<th scope="row"><label for="uultra_type"><?php _e('Type','get-bookings-wp'); ?> </label>
		</th>
		<td><select name="uultra_type" id="uultra_type">
				<option value="usermeta">
					<?php _e('Booking Form Field','get-bookings-wp'); ?>
				</option>
				<option value="separator">
					<?php _e('Separator','get-bookings-wp'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('You can create a separator or a usermeta (profile field)','get-bookings-wp'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_field"><?php _e('Editor / Input Type','get-bookings-wp'); ?>
		</label></th>
		<td><select name="uultra_field" id="uultra_field">
				<?php  foreach($getbookingwp->allowed_inputs as $input=>$label) { ?>
				<option value="<?php echo esc_attr($input); ?>">
					<?php echo esc_attr($label); ?>
				</option>
				<?php } ?>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('When user edit profile, this field can be an input (text, textarea, image upload, etc.)','get-bookings-wp'); ?>"></i>
		</td>
	</tr>

	<tr valign="top" >
		<th scope="row"><label for="uultra_meta_custom"><?php _e('New Custom Meta Key','get-bookings-wp'); ?>
		</label></th>
		<td><input name="uultra_meta_custom" type="text" id="uultra_meta_custom"
			value="<?php echo esc_attr($meta_custom_value); ?>" class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Enter a custom meta key for this profile field if do not want to use a predefined meta field above. It is recommended to only use alphanumeric characters and underscores, for example my_custom_meta is a proper meta key.','get-bookings-wp'); ?>"></i>
		</td>
	</tr>
    
   
	<tr valign="top">
		<th scope="row"><label for="uultra_name"><?php _e('Label','get-bookings-wp'); ?> </label>
		</th>
		<td><input name="uultra_name" type="text" id="uultra_name"
			value="<?php if (isset($_POST['uultra_name']) && isset($this->errors) && count($this->errors)>0) echo esc_attr($_POST['uultra_name']); ?>"
			class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','get-bookings-wp'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_tooltip"><?php _e('Tooltip Text','get-bookings-wp'); ?>
		</label></th>
		<td><input name="uultra_tooltip" type="text" id="uultra_tooltip"
			value="<?php if (isset($_POST['uultra_tooltip']) && isset($this->errors) && count($this->errors)>0) echo esc_attr($_POST['uultra_tooltip']); ?>"
			class="regular-text" /> <i
			class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('A tooltip text can be useful for social buttons on profile header.','get-bookings-wp'); ?>"></i>
		</td>
	</tr>
    
    
     <tr valign="top">
                <th scope="row"><label for="uultra_help_text"><?php _e('Help Text','get-bookings-wp'); ?>
                </label></th>
                <td>
                    <textarea class="uultra-help-text" id="uultra_help_text" name="uultra_help_text" title="<?php _e('A help text can be useful for provide information about the field.','get-bookings-wp'); ?>" ><?php if (isset($_POST['uultra_help_text']) && isset($this->errors) && count($this->errors)>0) echo esc_attr($_POST['uultra_help_text']); ?></textarea>
                    <i class="uultra-icon-question-sign uultra-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','get-bookings-wp'); ?>"></i>
                </td>
            </tr>

	
  

	<tr valign="top">
		<th scope="row"><label for="uultra_can_edit"><?php _e('User can edit','get-bookings-wp'); ?>
		</label></th>
		<td><select name="uultra_can_edit" id="uultra_can_edit">
				<option value="1">
					<?php _e('Yes','get-bookings-wp'); ?>
				</option>
				<option value="0">
					<?php _e('No','get-bookings-wp'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Users can edit this profile field or not.','get-bookings-wp'); ?>"></i>
		</td>
	</tr>

	
	


	<tr valign="top">
		<th scope="row"><label for="uultra_private"><?php _e('This field is required','get-bookings-wp'); ?>
		</label></th>
		<td><select name="uultra_required" id="uultra_required">
				<option value="0">
					<?php _e('No','get-bookings-wp'); ?>
				</option>
				<option value="1">
					<?php _e('Yes','get-bookings-wp'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Selecting yes will force user to provide a value for this field at registration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','get-bookings-wp'); ?>"></i>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="uultra_show_in_register"><?php _e('Show on Booking form','get-bookings-wp'); ?>
		</label></th>
		<td><select name="uultra_show_in_register" id="uultra_show_in_register">
				<option value="0">
					<?php _e('No','get-bookings-wp'); ?>
				</option>
				<option value="1">
					<?php _e('Yes','get-bookings-wp'); ?>
				</option>
		</select> <i class="uultra-icon-question-sign uultra-tooltip2"
			title="<?php _e('Show this field on the booking form? If you choose no, this field will be shown on edit profile only and not on the registration form. Most users prefer fewer fields when registering, so use this option with care.','get-bookings-wp'); ?>"></i>
		</td>
        
        
	</tr>   
    
     
    
            
   

	<tr valign="top">
		<th scope="row"></th>
		<td>
          <div class="getbwp-ultra-success getbwp-notification" id="getbwp-sucess-add-field"><?php _e('Success ','get-bookings-wp'); ?></div>
        <input type="submit" name="getbwp-add" 	value="<?php _e('Submit New Field','get-bookings-wp'); ?>"
			class="button button-primary" id="getbwp-btn-add-field-submit" /> 
            <input type="button"class="button button-secondary " id="getbwp-close-add-field-btn"	value="<?php _e('Cancel','get-bookings-wp'); ?>" />
		</td>
	</tr>

</table>


</div>


<!-- show customizer -->
<ul class="getbwp-ultra-sect getbwp-ultra-rounded" id="uu-fields-sortable" >
		
  </ul>
  
           <script type="text/javascript">  
		
		      var custom_fields_del_confirmation ="<?php _e('Are you totally sure that you want to delete this field?','get-bookings-wp'); ?>";
			  
			  var custom_fields_reset_confirmation ="<?php _e('Are you totally sure that you want to restore the default fields?','get-bookings-wp'); ?>";
			   
			  var custom_fields_duplicate_form_confirmation ="<?php _e('Please input a name','get-bookings-wp'); ?>";
		 
		 getbwp_reload_custom_fields_set();
		 </script>
         
         <div id="getbwp-spinner" class="getbwp-spinner" style="display:">
            <span> <img src="<?php echo esc_url(getbookingpro_url.'admin/images/loaderB16.gif')?>" width="16" height="16" /></span>&nbsp; <?php  _e('Please wait ...','get-bookings-wp')?>
	</div>
         
        