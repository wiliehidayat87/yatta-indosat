<?php /* Smarty version Smarty 3.1.4, created on 2012-12-06 09:57:10
         compiled from "application/views/admin/xmp_install.tpl" */ ?>
<?php /*%%SmartyHeaderCode:179101581050c00986901f77-70589373%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a83fb5e5f29a9058e42cee82c00a7be300fe389' => 
    array (
      0 => 'application/views/admin/xmp_install.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179101581050c00986901f77-70589373',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'addons_installed' => 0,
    'i' => 0,
    'addons_available' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50c0098692dca',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c0098692dca')) {function content_50c0098692dca($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="pagetitle">Telco Deployment Tools</div>
<div class="midlemidle">
    <form method="post">
    <table id="Addon-list-table" class="datagrid2" width="400" border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                <th align="left">Telco Name</th>
                <th align="left">Status</th>
            </tr>
            <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['addonId'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['addons_installed']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['addonId']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
            <tr><td>&nbsp;</td><td><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</td><td>installed</td></tr>
            <?php } ?>
            <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['addonId'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['addons_available']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['addonId']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
            <tr><td><input type="checkbox" name="cb_addon[]" value="<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
"></td><td><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</td><td><font color="green">available</font></td></tr>
            <?php } ?>
        </thead>
    </table>
    <input type="submit" value="install">
    </form>
</div>
 
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>