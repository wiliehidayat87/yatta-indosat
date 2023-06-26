<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:45:21
         compiled from "application/views/service/service_add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21030518250bc5891cfc908-61079117%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '65a297c5a2d2ce4bf97d7dee0eec54e1d1c25df1' => 
    array (
      0 => 'application/views/service/service_add.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21030518250bc5891cfc908-61079117',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'error_item' => 0,
    'base_url' => 0,
    'adn' => 0,
    'adn_item' => 0,
    'operator' => 0,
    'operator_item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc5891d4fcf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc5891d4fcf')) {function content_50bc5891d4fcf($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage Creator</div>
<?php if (!empty($_smarty_tpl->tpl_vars['error']->value)){?>
<div id="error" >
<h3>Error</h4>
<?php  $_smarty_tpl->tpl_vars['error_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['error_item']->_loop = false;
 $_smarty_tpl->tpl_vars['error_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['error']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['error_item']->key => $_smarty_tpl->tpl_vars['error_item']->value){
$_smarty_tpl->tpl_vars['error_item']->_loop = true;
 $_smarty_tpl->tpl_vars['error_key']->value = $_smarty_tpl->tpl_vars['error_item']->key;
?>
    <p><?php echo $_smarty_tpl->tpl_vars['error_item']->value;?>
</p>
<?php } ?>
</div>
<?php }?>
<form action ="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/submit" method="post" >
    <div class="middletop">
            <div style="padding:10px; color:#000; background-color:#fff">
                 <table width="100%" border="0" cellspacing="1" cellpadding="3">
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">Service Name * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-pattern" name="service_name" size="30" maxlength="50" />
                                &nbsp;<span id="inf-pattern"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">ADN (ShortCode) * </label>
                            </td>
                            <td>
                                 <select name="adn" id="adn" >
					<?php  $_smarty_tpl->tpl_vars['adn_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adn_item']->_loop = false;
 $_smarty_tpl->tpl_vars['adn_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adn']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adn_item']->key => $_smarty_tpl->tpl_vars['adn_item']->value){
$_smarty_tpl->tpl_vars['adn_item']->_loop = true;
 $_smarty_tpl->tpl_vars['adn_key']->value = $_smarty_tpl->tpl_vars['adn_item']->key;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['adn_item']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['adn_item']->value['name'];?>
</option>
					<?php } ?>
				</select>
                            </td>
                        </tr>

                        <tr>
                            <td width="20%" valign="top">
                                <label for="txt-pattern">Operator * </label>
                            </td>
                            <td>
					<?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
						<input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
" name="operator[]"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
</input><br />
                                        <?php } ?>
				</select>
                            </td>
                        </tr>
                        <tr>
                        <td colspan="2"> <input type="submit" name="addservice" value="addservice" /></td>
                        </tr>
                  </table>

	</div>
    </div>

	</div>
</form>
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>