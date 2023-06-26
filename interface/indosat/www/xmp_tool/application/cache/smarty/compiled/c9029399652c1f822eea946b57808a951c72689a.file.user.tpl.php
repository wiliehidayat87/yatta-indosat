<?php /* Smarty version Smarty 3.1.4, created on 2012-12-10 14:29:16
         compiled from "application/views/acl/user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:38807571950c58f4c54cb09-80634074%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c9029399652c1f822eea946b57808a951c72689a' => 
    array (
      0 => 'application/views/acl/user.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '38807571950c58f4c54cb09-80634074',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'group' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50c58f4c5ba5d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c58f4c5ba5d')) {function content_50c58f4c5ba5d($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage User</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add User</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
                <form name="user-form" id="user-form"> 
					<table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-username">Username *</label>
							</td>
							<td>
								<input type="text" name="txt-username" id="txt-username" class="inputtext-1" value="" />
                                &nbsp;<span id="inf-username"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-password">Password *</label>
							</td>
							<td>
								<input type="password" name="txt-password" id="txt-password" class="inputtext-1">
                                &nbsp;<span id="inf-password"></span>
							</td>
						</tr>
                        <tr>
                         	<td>
								<label for="txt-confirmpass">Conf. Password *</label>
							</td>
							<td>
								<input type="password" name="txt-confirmpass"id="txt-confirmpass" class="inputtext-1">
                                &nbsp;<span id="inf-confirmpass"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label>Group</label>
							</td>
							<td>
								<?php echo $_smarty_tpl->tpl_vars['group']->value;?>

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
            <table id="user-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="20%">Username</th>
                        <th width="15%">User Group</th>
                        <th width="15%" class="last">Action</th>
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