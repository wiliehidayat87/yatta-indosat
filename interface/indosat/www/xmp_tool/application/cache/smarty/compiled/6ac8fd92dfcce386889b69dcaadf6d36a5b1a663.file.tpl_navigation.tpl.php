<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 11:50:25
         compiled from "application/views/common/tpl_navigation.tpl" */ ?>
<?php /*%%SmartyHeaderCode:156926215450bc2f919d2394-39720406%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6ac8fd92dfcce386889b69dcaadf6d36a5b1a663' => 
    array (
      0 => 'application/views/common/tpl_navigation.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '156926215450bc2f919d2394-39720406',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'themeUrl' => 0,
    'navigation' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc2f919e0f4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc2f919e0f4')) {function content_50bc2f919e0f4($_smarty_tpl) {?><div class="leftlogo"><img src="<?php echo $_smarty_tpl->tpl_vars['themeUrl']->value;?>
/img/xmp-logo.png" /></div>
<div class="rightlogo"><img src="<?php echo $_smarty_tpl->tpl_vars['themeUrl']->value;?>
/img/linkit-logo.png" /></div>
<div id="smoothmenu1" class="ddsmoothmenu"">
    <ul>
        <?php echo $_smarty_tpl->tpl_vars['navigation']->value;?>
     
    </ul>						
</div>
<?php }} ?>