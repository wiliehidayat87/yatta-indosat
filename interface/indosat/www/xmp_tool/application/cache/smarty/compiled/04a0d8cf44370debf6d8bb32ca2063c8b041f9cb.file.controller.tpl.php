<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 12:18:44
         compiled from "application/views/acl/controller.tpl" */ ?>
<?php /*%%SmartyHeaderCode:57760022950bc36346697b6-45095503%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '04a0d8cf44370debf6d8bb32ca2063c8b041f9cb' => 
    array (
      0 => 'application/views/acl/controller.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '57760022950bc36346697b6-45095503',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'parent_list' => 0,
    'status' => 0,
    'status_key' => 0,
    'status_item' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc36346d5dc',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc36346d5dc')) {function content_50bc36346d5dc($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage Controller</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
			<div class="boxheader">
                <div class="boxtoggle">Add Controller</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="controller-form" id="controller-form"> 
					<table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-menu-name">Menu Name *</label>
							</td>
							<td>
								<input type="text" name="txt-menu-name" id="txt-menu-name" class="inputtext-1" value="" maxlength="30" />
								&nbsp;<span id="inf-menu-name"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-parent">Parent</label>
							</td>
							<td>
								<?php echo $_smarty_tpl->tpl_vars['parent_list']->value;?>

							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-controller-link">Controller Link *</label>
							</td>
							<td>
								<input type="text" name="txt-controller-link" id="txt-controller-link" class="inputtext-1" value="" maxlength="30" />
								&nbsp;<span id="inf-controller-link"></span>
							</td>
						</tr>
						<tr id="sort">
							<td>
								<label for="txt-sort">Sort *</label>
							</td>
							<td>
								<input type="text" name="txt-sort" id="txt-sort" class="inputtext-1" value="" maxlength="11" />
								&nbsp;<span id="inf-sort"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-status">Status (become menu?)*</label>
							</td>
							<td>
								<select name="txt-status" id="txt-status" >
									<?php  $_smarty_tpl->tpl_vars['status_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['status_item']->_loop = false;
 $_smarty_tpl->tpl_vars['status_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['status']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['status_item']->key => $_smarty_tpl->tpl_vars['status_item']->value){
$_smarty_tpl->tpl_vars['status_item']->_loop = true;
 $_smarty_tpl->tpl_vars['status_key']->value = $_smarty_tpl->tpl_vars['status_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['status_key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['status_item']->value;?>
</option>
									<?php } ?>
								</select>
								<i>must relog to activated</i>
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
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
            <table id="controller-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
					<tr>
                        <th>Menu Name</th>
                        <th>Parent</th>
						<th>Controller Link</th>
						<th>Sort</th>
						<th>Status</th>
						<th width="120" class="last">Action</th>
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