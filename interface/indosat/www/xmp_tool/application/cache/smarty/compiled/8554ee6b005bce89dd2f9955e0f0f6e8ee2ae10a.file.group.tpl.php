<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 13:17:22
         compiled from "application/views/acl/group.tpl" */ ?>
<?php /*%%SmartyHeaderCode:210337482750bc43f271da96-92937477%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8554ee6b005bce89dd2f9955e0f0f6e8ee2ae10a' => 
    array (
      0 => 'application/views/acl/group.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '210337482750bc43f271da96-92937477',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'check_menu' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc43f2774eb',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc43f2774eb')) {function content_50bc43f2774eb($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Manage group</div>
    <div class="middletop">
		<div class="roundedbox bluebox">
            <div class="boxheader">
				<div class="boxtoggle" id="btnOpenPanel">Add Group</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
                <div class="clear"></div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
                <form name="group-form" id="group-form"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr valign="top">
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <label for="txt-group-name">Group Name *</label>
                                        </td>
                                        <td>
                                            <input type="text" name="txt-group-name" id="txt-group-name" class="inputtext-1" value="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="txt-group-description">Description</label>
                                        </td>
                                        <td>
                                            <textarea name="txt-group-description" id="txt-group-description" class="inputtext-1"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr valign="top">
                                        <td>
                                            <label for="">Privileges</label>
                                        </td>
                                        <td align="left">
                                            <?php echo $_smarty_tpl->tpl_vars['check_menu']->value;?>

                                        </td>
                                    </tr>
                               </table>
                            </td>
                        <tr>
                    </table>
                    <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
                    <input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
                </form>
            </div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="group-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="25%">Group Name</th>
                        <th width="40%">Group Description</th>
                        <th width="15%">Status</th>
                        <th width="20%" class="last">Action</th>
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