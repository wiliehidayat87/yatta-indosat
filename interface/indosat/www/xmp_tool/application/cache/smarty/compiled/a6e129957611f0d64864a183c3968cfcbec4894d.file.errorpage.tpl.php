<?php /* Smarty version Smarty 3.1.4, created on 2012-12-23 23:37:19
         compiled from "application/views/errorpage/errorpage.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1159677350d7333f62e308-71394545%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a6e129957611f0d64864a183c3968cfcbec4894d' => 
    array (
      0 => 'application/views/errorpage/errorpage.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1159677350d7333f62e308-71394545',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50d7333f648ff',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50d7333f648ff')) {function content_50d7333f648ff($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="pagetitle"></div>

    <div id="error" style="position:relative;width:300px;height:100px;margin:0 auto;font-size:32px;top:100px;align:center;">
        <?php echo $_smarty_tpl->tpl_vars['message']->value;?>

    </div>
    
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>



<?php }} ?>