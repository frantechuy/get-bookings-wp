<?php
// General Functions for Plugin

if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
		
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}



if (!function_exists('is_post')) {

    function is_post() {
        if (strtolower(sanitize_text_field($_SERVER['REQUEST_METHOD'])) == 'post')
            return true;
        else
            return false;
    }

}





if (!function_exists('is_in_post')) {

    function is_in_post($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if ( sanitize_text_field($_POST[$key]) ) {
                if ($val == '')
                    return true;
                else if (sanitize_text_field($_POST[$key]) == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if (!function_exists('is_get')) {

    function is_get() {
        if (strtolower(sanitize_text_field($_SERVER['REQUEST_METHOD'])) == 'get')
            return true;
        else
            return false;
    }

}


if (!function_exists('is_in_get')) {

    function is_in_get($key='', $val='') {
        if ($key == '') {
            return false;
        } else {
            if (sanitize_text_field($_GET[$key])) {
                if ($val == '')
                    return true;
                else if (sanitize_text_field($_GET[$key]) == $val)
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }

}

if(!function_exists('not_null'))
{
    function not_null($value)
    {
        if (is_array($value))
        {
            if (sizeof($value) > 0)
                return true;
            else
                return false;
        }
        else
        {
            if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0))
                return true;
            else
                return false;
        }
    } 
}



if(!function_exists('get_value'))
{
    function get_value($key='')
    {
        if($key!='')
        {
            $key = sanitize_text_field($_POST[$key]);

            if(isset($key))
            {
                if(!is_array(sanitize_text_field($_GET[$key])))
                    return trim(sanitize_text_field($_GET[$key]));
                else
                    return sanitize_text_field($_GET[$key]);
            }
    
            else
                return '';
        }
        else
            return '';
    }
}


if (!function_exists('remove_script_tags')) {

    function remove_script_tags($text) {
        $text = str_ireplace("<script>", "", $text);
        $text = str_ireplace("</script>", "", $text);

        return $text;
    }

}


if(!function_exists('post_value'))
{
    function post_value($key='')
    {
        if($key!='')
        {
            $key = sanitize_text_field($_POST[$key]);

            if( $key)
            {
                if(!is_array(sanitize_text_field($_POST[$key])))
                    return trim(sanitize_text_field($_POST[$key]));
                else
                    return sanitize_text_field($_POST[$key]);
            }
            else
                return '';
        }
        else
            return '';
    }
}


if(!function_exists('is_opera'))
{
    function is_opera()
    {
        $user_agent = strtolower(sanitize_text_field($_SERVER['HTTP_USER_AGENT']));
        return preg_match('/opera/i', $user_agent);
    }
}

if(!function_exists('is_safari'))
{
    function is_safari()
    {
        $user_agent = strtolower(sanitize_text_field($_SERVER['HTTP_USER_AGENT']));
        return (preg_match('/safari/i', $user_agent) && !preg_match('/chrome/i', $user_agent));
    }
}

if(!function_exists('is_active'))
{

/* Check if user is active before login  */
	function is_active($user_id) 
	{
		$checkuser = get_user_meta($user_id, 'getbwp_account_status', true);
		if ($checkuser == 'active')
			return true;
		return false;
	}}