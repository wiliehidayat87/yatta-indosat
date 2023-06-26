<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 13:11:35
         compiled from "application/views/cs/subscription.tpl" */ ?>
<?php /*%%SmartyHeaderCode:136682435950bc4297765550-11460699%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '673f0706ed5ef76e482d89270a314fa0becb122b' => 
    array (
      0 => 'application/views/cs/subscription.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '136682435950bc4297765550-11460699',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'operator' => 0,
    'operator_item' => 0,
    'adn' => 0,
    'adn_item' => 0,
    'service' => 0,
    'service_item' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc42977da73',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc42977da73')) {function content_50bc42977da73($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


    <div class="pagetitle">Subscription</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
			<div class="boxheader">
                <div class="boxtoggle">User Subscription</div>
                <div class="search-area">
                    
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form id="subscription" method='post'>
					<table cellspacing="2">
						<tr>
							<td width="40%" style="text-align: right;">MSISDN :</td>
							<td>
								<input type='text' name='msisdn' id='msisdn'>
							</td>
						</tr>
						<tr>
							<td width="40%" style="text-align: right;">Operator:</td>
							<td>
								<select id="operator" name="operator" id="operator">
									<option value="">-- operator --</option>
									<?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value->name;?>
"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value->long_name;?>
</option>
									<?php } ?>			
								</select>
							</td>
						</tr> 
						<tr>
							<td width="40%" style="text-align: right;">ADN:</td>
							<td>
								<select id="adn" name="adn">
									<option value="">-- ADN --</option>
									<?php  $_smarty_tpl->tpl_vars['adn_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adn_item']->_loop = false;
 $_smarty_tpl->tpl_vars['adn_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adn']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adn_item']->key => $_smarty_tpl->tpl_vars['adn_item']->value){
$_smarty_tpl->tpl_vars['adn_item']->_loop = true;
 $_smarty_tpl->tpl_vars['adn_key']->value = $_smarty_tpl->tpl_vars['adn_item']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['adn_item']->value->adn;?>
"><?php echo $_smarty_tpl->tpl_vars['adn_item']->value->adn;?>
</option>
									<?php } ?>	
								</select>
							</td>
						</tr>  
						<tr>
							<td width="40%" style="text-align: right;">Service:</td>
							<td>
								<select id="service" name="service">
									<option value="">-- service --</option>
									<?php  $_smarty_tpl->tpl_vars['service_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['service_item']->_loop = false;
 $_smarty_tpl->tpl_vars['service_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['service']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['service_item']->key => $_smarty_tpl->tpl_vars['service_item']->value){
$_smarty_tpl->tpl_vars['service_item']->_loop = true;
 $_smarty_tpl->tpl_vars['service_key']->value = $_smarty_tpl->tpl_vars['service_item']->key;
?> 
										<option value="<?php echo $_smarty_tpl->tpl_vars['service_item']->value->name;?>
"><?php echo $_smarty_tpl->tpl_vars['service_item']->value->name;?>
</option>
									<?php } ?>	
								</select>
							</td>
						</tr>   
						<tr>
							<td>&nbsp;</td>
							<td><button type="button" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
    </div>
    <div class="midlemidle">
		<div id="btnCheck" align=left style='float:left; width:380px;'>
			<input style='font-size:10px;' type="button" name="CheckAll" value="Check All" >
			<input style='font-size:10px;' type="button" name="UnCheckAll" value="Uncheck All"">	
			<input style='font-size:10px;' type="button" name="inactiveChecked" id="inactiveChecked" value="Inactive Checked">		
		</div>
		<div class="clear"></div>
		 <div class="boxheader reporttable">
			<div id='userSubscriptionTable'></div>
			</div>
        <div class="pagination">
			<ul>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="viewlimit"><!--
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
			</select>-->
		</div>
	</div>
	

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>