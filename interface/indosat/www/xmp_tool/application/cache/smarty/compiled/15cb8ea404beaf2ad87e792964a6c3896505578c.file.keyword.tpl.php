<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:45:56
         compiled from "application/views/service/keyword.tpl" */ ?>
<?php /*%%SmartyHeaderCode:121319978250bc58b42c8e90-32908063%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '15cb8ea404beaf2ad87e792964a6c3896505578c' => 
    array (
      0 => 'application/views/service/keyword.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '121319978250bc58b42c8e90-32908063',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'service_id' => 0,
    'operator' => 0,
    'base_url' => 0,
    'pattern' => 0,
    'operator_item' => 0,
    'service_name' => 0,
    'adn' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc58b43503e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc58b43503e')) {function content_50bc58b43503e($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>

<script type="text/javascript">var gbl_service_id = "<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
";</script>
<script type="text/javascript">var gbl_operator = "<?php echo $_smarty_tpl->tpl_vars['operator']->value;?>
";</script>
    <div class="pagetitle">Manage Creator</div>
<div id="dialog-form" title="Edit Ini File">
    <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/editIniFile" method="post" id="form_edit_inifile"></form>
</div>
<form action ="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/submit_keyword" method="post" >
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
" name="service_id" />
    <div class="middletop">
            <div style="padding:10px; color:#000; background-color:#fff">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">Keyword * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-pattern" name="keyword" size="30" maxlength="50" <?php if ($_smarty_tpl->tpl_vars['pattern']->value){?>value="<?php echo $_smarty_tpl->tpl_vars['pattern']->value;?>
" readonly="readonly"<?php }?>/>
                                &nbsp;<span id="inf-pattern"></span>
                            </td>
                        </tr>
                  </table>
	</div>
    </div>
    <div class="midlemidle">
        <div id="tabs">
            <ul id="tabs">
                <?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
                    <li id="tabs"><a href="#tabs-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" class="operator testing-1" operator="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
</a></li>
                <?php } ?>
            </ul>

<?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
    <div id="tabs-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
">
<div><input type="checkbox" name="active[<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
]" value="1" checked="true" /> active</div>
<div>
<br />
Handler Type <select name="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
[handler-type]" id="handler-type-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" onChange="javascript:selectHandler('<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['service_name']->value;?>
')" >
<option value="">Select Handler Type</option>
<option value="creator">Service Creator</option>
<option value="custom">Service Custom</option>
</select>
</div>

<div id="creator-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" style="display:none">
<br />
<div>
<div style="float:left; width: 50%">
Module <select name="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
[module]" id="select-module-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" onChange="javascript:getModule('<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['adn']->value;?>
')">
<option value="">Select Module</option>
<option value="registration">Registration</option>
<option value="unregistration">UnRegistration</option>
<option value="text">Text</option>
<option value="textdelay">Text Delay</option>
<option value="waplink">Wap Link</option>
<option value="wappush">Wap Push</option>
</select>
</div>
<div style="float:right; width: 49%; text-align:right">
<input type="button" name="addmodule" value="Add Module" onClick="javascript:addModule('<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['adn']->value;?>
')" />
</div>
</div>
<br /><br />
<div id="module-content-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" style="padding-top: 15px"></div>
<div id="content-operator-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
"></div>
</div>

<div id="custom-<?php echo $_smarty_tpl->tpl_vars['operator_item']->value;?>
" style="display:none">
</div>

	</div>
<?php } ?>

	</div><br />
<input type="submit" value="add_keyword" name="Add Keyword" />
    </div>
</form>
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>