<?php /* Smarty version Smarty 3.1.4, created on 2012-12-04 14:28:31
         compiled from "application/views/masterdata/operator.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136700993250bda61f875c07-36495537%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5eb6a9c8af2a6d9267d5632b03c6fda2dc598cec' => 
    array (
      0 => 'application/views/masterdata/operator.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '136700993250bda61f875c07-36495537',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bda61f8bc3b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bda61f8bc3b')) {function content_50bda61f8bc3b($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Master Data Operator</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Operator</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="operator-form" id="operator-form"> 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-operator-name">Operator Name * </label>
							</td>
							<td>
								<input type="text" id="txt-operator-name" name="txt-operator-name" class="inputtext-1 w-200" maxlength="30" />
								&nbsp;<span id="inf-operator-name"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-operator-long-name">Operator Long Name * </label>
							</td>
							<td>
								<input type="text" id="txt-operator-long-name" name="txt-operator-long-name" class="inputtext-1 w-290" maxlength="255" />
								&nbsp;<span id="inf-operator-long-name"></span>
							</td>
						</tr>
					</table><br>
					<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
					<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
				</form>
			</div>
        </div>
    </div>
    <div class="midlemidle">
        <div class="boxheader reporttable">
            <table id="operator-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="38%">Operator Name</th>
                        <th width="50%">Operator Long Name</th>
                        <th width="12%">Action</th>
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
			</select>
		</div>
	</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>