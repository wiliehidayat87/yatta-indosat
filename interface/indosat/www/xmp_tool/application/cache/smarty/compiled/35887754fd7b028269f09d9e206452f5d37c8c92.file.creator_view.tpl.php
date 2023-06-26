<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:45:17
         compiled from "application/views/service/creator_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203751528750bc588d1f6f09-71626325%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '35887754fd7b028269f09d9e206452f5d37c8c92' => 
    array (
      0 => 'application/views/service/creator_view.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203751528750bc588d1f6f09-71626325',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'service' => 0,
    'service_item' => 0,
    'operator' => 0,
    'operator_item' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc588d284f9',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc588d284f9')) {function content_50bc588d284f9($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage Creator</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
service/add_service"><div class="boxtoggle">Add Service</div></a>
<div class="filterby" style="width:300px; float:left; padding:6px; margin-left:250px">
Filter By <select name="filter_service" id="filter_service"><option value="0">All Services</option>
<?php  $_smarty_tpl->tpl_vars['service_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['service_item']->_loop = false;
 $_smarty_tpl->tpl_vars['service_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['service']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['service_item']->key => $_smarty_tpl->tpl_vars['service_item']->value){
$_smarty_tpl->tpl_vars['service_item']->_loop = true;
 $_smarty_tpl->tpl_vars['service_key']->value = $_smarty_tpl->tpl_vars['service_item']->key;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['service_item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['service_item']->value['name'];?>
</option>
<?php } ?>
</select>
                                <select name="filter_operator" id="filter_operator" >
<option value="0">All Operators</option>
									<?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
</option>
									<?php } ?>
								</select>
<a id="filterby" onClick="javascript:filterby()" >Filter</a>
</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="creator-form" id="creator-form"> 
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">Keyword * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-pattern" name="txt-pattern" size="30" maxlength="50" />
                                &nbsp;<span id="inf-pattern"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="operatorId">Operator  </label>
                            </td>
                            <td>
                                <select name="operatorId" id="operatorId" >
									<?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
</option>
									<?php } ?>
								</select>
                                &nbsp;<span id="inf-operatorId"></span>
                            </td>
						</tr>
                        <tr>
                            <td>
                                <label for="serviceId">Service  </label>
                            </td>
                            <td>
                                <select name="serviceId" id="serviceId" >
									<?php  $_smarty_tpl->tpl_vars['service_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['service_item']->_loop = false;
 $_smarty_tpl->tpl_vars['service_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['service']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['service_item']->key => $_smarty_tpl->tpl_vars['service_item']->value){
$_smarty_tpl->tpl_vars['service_item']->_loop = true;
 $_smarty_tpl->tpl_vars['service_key']->value = $_smarty_tpl->tpl_vars['service_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['service_item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['service_item']->value['name'];?>
</option>
									<?php } ?>
								</select>
                                &nbsp;<span id="inf-serviceId"></span>
                            </td>
						</tr>
                        <tr>
                            <td>
                                <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;&nbsp;
								<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="service-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr><!--
                        <th>Keyword</th>
                        <th>Handler</th>
                        <th>Operator</th> -->
                        <th>Service</th>
                        <th>Date Created</th>
                        <th width="20">Action</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="viewlimit">
            View limit : 
			<select name="pageLimit" id="pageLimit">
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pageLimit']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
				<?php } ?>
			</select> &nbsp; | &nbsp; Total Data : <span id="total_data"></span>
		</div>
	</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>