<?php /* Smarty version Smarty 3.1.4, created on 2012-12-10 14:31:17
         compiled from "application/views/acl/method_group.tpl" */ ?>
<?php /*%%SmartyHeaderCode:35022347550c58fc50fe300-98978439%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7711b63dd3b5fe2a135ac3cf4779b95bb1c8926f' => 
    array (
      0 => 'application/views/acl/method_group.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35022347550c58fc50fe300-98978439',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'controller_list' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50c58fc5147f2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c58fc5147f2')) {function content_50c58fc5147f2($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage method</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle" id="btnOpenPanel">Scan Method</div>
				<div class="search-area">
					<form name="search-form" id="search-form">
						<input type="text" name="search-field" id="search-field" class="search-field" />
						<input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
					</form>
				</div>
                <div class="clear"></div>
			</div>            
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="scan-method-group-form" id="scan-method-group-form">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr valign="top">
							<td width="10%">
								<label for="">Controller Link</label>
							</td>
							<td>
								<?php echo $_smarty_tpl->tpl_vars['controller_list']->value;?>

							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type="submit" name="scan" id="scan" class="button" value="Scan" />
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
            <table id="method-group-list-table"  width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="20%">Group Name</th>
                        <th width="20%">Controller Link</th>
                        <th width="30%">Method</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="last">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
		<div class="pagination">
			<ul>
				<div class="clear"></div>
				<li></li>
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