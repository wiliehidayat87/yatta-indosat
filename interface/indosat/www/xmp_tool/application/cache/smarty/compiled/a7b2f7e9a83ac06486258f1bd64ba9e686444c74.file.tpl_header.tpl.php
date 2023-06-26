<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 11:50:25
         compiled from "application/views/common/tpl_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:128204254350bc2f91948119-24246312%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a7b2f7e9a83ac06486258f1bd64ba9e686444c74' => 
    array (
      0 => 'application/views/common/tpl_header.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '128204254350bc2f91948119-24246312',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pageTitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc2f9195842',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc2f9195842')) {function content_50bc2f9195842($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</title>
<?php echo $_smarty_tpl->getSubTemplate ('common/tpl_head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</head>
    <body>
        <div class="wrapper"> 
            <div class="header">                
                <?php echo $_smarty_tpl->getSubTemplate ('common/tpl_navigation.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

            </div>
            <div class="middle">                <?php }} ?>