<?php /* Smarty version Smarty 3.1.4, created on 2012-12-07 11:31:12
         compiled from "application/views/masterdata/charging.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140107416450c171109b0570-72104511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '74a25330ac9fd7d744d18cf30a35b8134f59bf6b' => 
    array (
      0 => 'application/views/masterdata/charging.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140107416450c171109b0570-72104511',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'operator' => 0,
    'adn' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50c17110a4779',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50c17110a4779')) {function content_50c17110a4779($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Master Data Charging</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Charging</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="charging-form" id="charging-form"> 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr valign="top">
							<td width="50%">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="30%">
											<label for="txt-operator">Operator *</label>
										</td>
										<td width="70%">
											<?php echo $_smarty_tpl->tpl_vars['operator']->value;?>

											&nbsp;<span id="inf-operator"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-adn">ADN *</label>
										</td>
										<td>
											<?php echo $_smarty_tpl->tpl_vars['adn']->value;?>

											&nbsp;<span id="inf-adn"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-charging-id">Charging ID *</label>
										</td>
										<td>
											<input type="text" name="txt-charging-id" id="txt-charging-id" class="inputtext-1" value="" maxlength="128" />
											&nbsp;<span id="inf-charging-id"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-gross">Gross *</label>
										</td>
										<td>
											<input type="text" name="txt-gross" id="txt-gross" class="inputtext-1" value="" maxlength="12" />
											&nbsp;<span id="inf-gross"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-netto">Netto *</label>
										</td>
										<td>
											<input type="text" name="txt-netto" id="txt-netto" class="inputtext-1" value="" maxlength="12" />
											&nbsp;<span id="inf-netto"></span>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="30%">
											<label for="txt-username">Username *</label>
										</td>
										<td width="70%">
											<input type="text" name="txt-username" id="txt-username" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-username"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-password">Password *</label>
										</td>
										<td>
											<input type="password" name="txt-password" id="txt-password" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-password"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-sender-type">Sender Type *</label>
										</td>
										<td>
											<input type="text" name="txt-sender-type" id="txt-sender-type" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-sender-type"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-message-type">Message Type *</label>
										</td>
										<td>
											<input type="text" name="txt-message-type" id="txt-message-type" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-message-type"></span>
										</td>
									</tr>
								</table>
							</td>
						<tr>
					</table><br>
					<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
					<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
				</form>
			</div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="charging-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="10%">Operator</th>
                        <th width="10%">ADN</th>
                        <th width="15%">Charging ID</th>
                        <th width="10%">Gross</th>
                        <th width="10%">Netto</th>
                        <th width="15%">Sender Type</th>
                        <th width="15%">Message Type</th>
                        <th width="15%">Action</th>
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