<?php /* Smarty version Smarty 3.1.4, created on 2012-11-30 17:30:27
         compiled from "application/views/user/login_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:172353714350b88ac32f88b4-40651821%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c1f5fb630f451dc6ebea10a8040fc993e6c6b61c' => 
    array (
      0 => 'application/views/user/login_view.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '172353714350b88ac32f88b4-40651821',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'errormessage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50b88ac333d04',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50b88ac333d04')) {function content_50b88ac333d04($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

		<title>Login XMP - Service Creator</title>

        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/login.css" type="text/css" />
	</head>
    
	<body>
        <div id="container">
            <div id="panel-login">
                <div class="wrapper">
                    <div class="formwrapper">
                        <form action="" method="post">
                            <?php if ($_smarty_tpl->tpl_vars['errormessage']->value!=''){?>
                            <div class="errormsg"><?php echo $_smarty_tpl->tpl_vars['errormessage']->value;?>
</div>
                            <?php }else{ ?>
                            <br>
                            <?php }?>
                            <p>
                                <label id="icon-username">Username</label>
                                <input type="text" class="textfield" name="username" />
                            </p>
                            <p>
                                <label id="icon-password">Password</label>
                                <input type="password" class="textfield" name="password" />
                            </p>
                            <p>
                               <input type="submit" value="Login" name="login" id="btn-login" />
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div id="footer"> Copyright &copy; 2011 LinkIT 360&deg; All Rights Reserved. </div>
        </div>
    </body>
</html>
<?php }} ?>