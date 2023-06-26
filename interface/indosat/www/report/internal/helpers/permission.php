<?php
function check_permission($perm='')
{
	die('xx');
	return true;
	
	$sso_client	= sso_client::getInstance();
	$return = false;
	if ($sso_client->get_permission('SSO-MANAGER_ADMIN'))
	{
		
		return true;
	}
	
	return $sso_client->get_permission($perm);
	//return true;
}

function check_super_admin()
{
	$sso_client	= sso_client::getInstance();
	$return = false;
	return ($sso_client->get_permission('SSO-MANAGER_ADMIN'));
}

function get_application()
{
	
	if (@$_SESSION['permission'])
	foreach($_SESSION['permission'] as $permission)
	{
		//echo $permission->permission_name;
		if (strpos($permission->permission_name , "SSO-MANAGER_APPLICATION_") !== false)
		{
		 //	echo "ada";
			$ret[] = str_replace("SSO-MANAGER_APPLICATION_", "", $permission->permission_name);
		}
	}
	return $ret;
}
	function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
	{
		if ( ! is_array($selected))
		{
			$selected = array($selected);
		}

		// If no selected state was submitted we will attempt to set it automatically
		if (count($selected) === 0)
		{
			// If the form name appears in the $_POST array we have a winner!
			if (isset($_POST[$name]))
			{
				$selected = array($_POST[$name]);
			}
		}

		if ($extra != '') $extra = ' '.$extra;

		$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

		$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
		if ($options)
		foreach ($options as $key => $val)
		{
			$key = (string) $key;

			if (is_array($val))
			{
				$form .= '<optgroup label="'.$key.'">'."\n";

				foreach ($val as $optgroup_key => $optgroup_val)
				{
					$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

					$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
				}

				$form .= '</optgroup>'."\n";
			}
			else
			{
				$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

				$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
			}
		}

		$form .= '</select>';

		return $form;
	}