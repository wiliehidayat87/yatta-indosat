<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:47:45
         compiled from "application/views/service/service_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:125730288050bc59215c7b49-78966287%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45f51af3000fa26d54ecbc53c758841aa3da2e21' => 
    array (
      0 => 'application/views/service/service_edit.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '125730288050bc59215c7b49-78966287',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'error_item' => 0,
    'base_url' => 0,
    'service_id' => 0,
    'all_adn' => 0,
    'all_adn_item' => 0,
    'adn' => 0,
    'service_name' => 0,
    'operator' => 0,
    'operat' => 0,
    'keywords' => 0,
    'keyword' => 0,
    'operator_pattern' => 0,
    'operator_key' => 0,
    'operator_item' => 0,
    'operator_item2' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc592167a1d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc592167a1d')) {function content_50bc592167a1d($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Edit Service Keyword</div>

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

<fieldset>
<legend>Service Info</legend>
<form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/service/add_service/change_name" method="post">
<input type="hidden" name="service_id" value="<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
" />
<table border="0" cellpadding="3" cellspacing="1">
<tr>
<td>ADN</td><td>: 
<select name="adn">
<?php  $_smarty_tpl->tpl_vars['all_adn_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['all_adn_item']->_loop = false;
 $_smarty_tpl->tpl_vars['all_adn_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['all_adn']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['all_adn_item']->key => $_smarty_tpl->tpl_vars['all_adn_item']->value){
$_smarty_tpl->tpl_vars['all_adn_item']->_loop = true;
 $_smarty_tpl->tpl_vars['all_adn_key']->value = $_smarty_tpl->tpl_vars['all_adn_item']->key;
?>
    <option value="<?php echo $_smarty_tpl->tpl_vars['all_adn_item']->value['name'];?>
" <?php if (($_smarty_tpl->tpl_vars['adn']->value==$_smarty_tpl->tpl_vars['all_adn_item']->value['name'])){?> selected="selected"<?php }?> ><?php echo $_smarty_tpl->tpl_vars['all_adn_item']->value['name'];?>
</option>
<?php } ?>
</select>
</td>
</tr>
<td>Service Name</td><td> : <input type="text" name="service_name" value="<?php echo $_smarty_tpl->tpl_vars['service_name']->value;?>
" /></td>
</tr>
<tr><td colspan="2"><input type="submit" value="Edit Service" name="edit_service" /></td></tr>
</table>
</form>
</fieldset>
<br />

<?php  $_smarty_tpl->tpl_vars['operat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operat']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operat']->key => $_smarty_tpl->tpl_vars['operat']->value){
$_smarty_tpl->tpl_vars['operat']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operat']->key;
?>
<input type="hidden" name="operator[]" value="<?php echo $_smarty_tpl->tpl_vars['operat']->value;?>
" />
<?php } ?>
<input type="hidden" name="adn" value="<?php echo $_smarty_tpl->tpl_vars['adn']->value;?>
" id="adn" />
<input type="hidden" name="service_name" value="<?php echo $_smarty_tpl->tpl_vars['service_name']->value;?>
" id="service_name" />
<input type="hidden" name="service_id" value="<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
" id="service_id" />
    <button id="add-new-keyword" attr="adn=<?php echo $_smarty_tpl->tpl_vars['adn']->value;?>
&service_id=<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
">Add New Keyword</button>
<br />
<div id="dialog-form" title="Operator">
	<form id="form_list_operator" action ="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/edit_addkeyword" method="post">
	</form>
</div>
    <div class="middletop">
            	<div class="boxheader reporttable">
            <table id="adn-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th>Keyword</th>
                        <th width="130px" class="last">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  $_smarty_tpl->tpl_vars['keyword'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['keyword']->_loop = false;
 $_smarty_tpl->tpl_vars['keyword_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['keywords']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['keyword']->key => $_smarty_tpl->tpl_vars['keyword']->value){
$_smarty_tpl->tpl_vars['keyword']->_loop = true;
 $_smarty_tpl->tpl_vars['keyword_key']->value = $_smarty_tpl->tpl_vars['keyword']->key;
?>
                    <tr>
                        <td attr="<?php echo $_smarty_tpl->tpl_vars['keyword']->value['id'];?>
" class="keyword_list"><?php echo $_smarty_tpl->tpl_vars['keyword']->value['pattern'];?>
</td>
                        <td><a href="javascript:void(0)" id="<?php echo urlencode($_smarty_tpl->tpl_vars['keyword']->value['pattern']);?>
" class="add-operator">Add Operator</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/delKeywordByPattern/<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
/<?php echo urlencode($_smarty_tpl->tpl_vars['keyword']->value['pattern']);?>
" onclick="return confirm('Are you sure to delete?');">Delete</a></td>
                    </tr>
                    <?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator_pattern']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
                    <?php if ($_smarty_tpl->tpl_vars['operator_key']->value==$_smarty_tpl->tpl_vars['keyword']->value['pattern']){?>
                    <tr class="<?php echo $_smarty_tpl->tpl_vars['keyword']->value['id'];?>
" attr="hide">
                        <td id="detail_header">Operator</td>
                        <td id="detail_header">Action</td>
                    </tr>
                    <?php  $_smarty_tpl->tpl_vars['operator_item2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item2']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator_item']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item2']->key => $_smarty_tpl->tpl_vars['operator_item2']->value){
$_smarty_tpl->tpl_vars['operator_item2']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key2']->value = $_smarty_tpl->tpl_vars['operator_item2']->key;
?>
                    <tr class="<?php echo $_smarty_tpl->tpl_vars['keyword']->value['id'];?>
" attr="hide">
                        <td id="detail_body"><?php echo $_smarty_tpl->tpl_vars['operator_item2']->value['operator_name'];?>
</td>
                        <td id="detail_body"><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/edit_keyword/<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['operator_item2']->value['operator_name'];?>
/<?php echo $_smarty_tpl->tpl_vars['operator_item2']->value['id'];?>
">Edit</a> | <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service/deleteKeyword/<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['operator_item2']->value['id'];?>
" onclick="return confirm('Are you sure to delete ?');">Delete</a></td>
                    </tr>
                    <?php } ?>
                    <?php }?>
                    <?php } ?>
                    <?php } ?>
                    </tbody>
            </table>
        </div>
	</div>
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>