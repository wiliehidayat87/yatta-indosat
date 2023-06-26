<?php /* Smarty version Smarty 3.1.4, created on 2012-12-04 14:28:02
         compiled from "application/views/masterdata/module.tpl" */ ?>
<?php /*%%SmartyHeaderCode:34352168550bda602373f02-07976978%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19fc20b2e5c7cec90706f28ac5abb13717846394' => 
    array (
      0 => 'application/views/masterdata/module.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '34352168550bda602373f02-07976978',
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
  'unifunc' => 'content_50bda6023c4ba',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bda6023c4ba')) {function content_50bda6023c4ba($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Master Module</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Module</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="module-form" id="module-form"> 
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-name">Module Name * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-name" name="txt-name" size="30" maxlength="50" />
                                &nbsp;<span id="inf-name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txt-description">Description</label>
                            </td>
                            <td>
                                <textarea rows="2" cols="20" id="txt-description" name="txt-description" ></textarea>
                                &nbsp;<span id="inf-description"></span>
                            </td>
						</tr>
						<tr>
                            <td width="20%">
                                <label for="txt-handler">Handler * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-handler" name="txt-handler" size="30" maxlength="50" />
                                &nbsp;<span id="inf-handler"></span>
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
            <table id="module-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th width="25%">Module Name</th>
                        <th width="30%">Description</th>
                        <th width="25">Handler</th>
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
			</select>
		</div>
	</div>
	
<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>