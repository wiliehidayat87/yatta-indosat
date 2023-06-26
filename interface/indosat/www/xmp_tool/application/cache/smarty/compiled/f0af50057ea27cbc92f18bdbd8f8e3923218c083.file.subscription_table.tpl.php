<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 17:51:46
         compiled from "application/views/cs/subscription_table.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183187480050bc844231ede6-18179040%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0af50057ea27cbc92f18bdbd8f8e3923218c083' => 
    array (
      0 => 'application/views/cs/subscription_table.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183187480050bc844231ede6-18179040',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'searchData' => 0,
    'numbering' => 0,
    'sd_item' => 0,
    'statusConv' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc844238f5a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc844238f5a')) {function content_50bc844238f5a($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['searchData']->value!=''&&count($_smarty_tpl->tpl_vars['searchData']->value)>0){?>
    <?php $_smarty_tpl->tpl_vars['nodata'] = new Smarty_variable(0, null, 0);?>
    
<div id="chartTable" align=center>
	<form name='table'>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th>No</th>
			<th>MSISDN</th>
			<th>SERVICE</th>
			<th>ADN</th>
			<th>OPERATOR</th>
			<th>CHANNEL SUBSCRIBE</th>
			<th>CHANNEL UNSUBSCRIBE</th>
			<th>SUBSCRIBE FROM</th>
			<th>SUBSCRIBE UNTIL</th>
			<th>ACTIVE</th>
			<th class="last">ACTION</th>
		</tr>
	
		<?php  $_smarty_tpl->tpl_vars['sd_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sd_item']->_loop = false;
 $_smarty_tpl->tpl_vars['sd_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['searchData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sd_item']->key => $_smarty_tpl->tpl_vars['sd_item']->value){
$_smarty_tpl->tpl_vars['sd_item']->_loop = true;
 $_smarty_tpl->tpl_vars['sd_key']->value = $_smarty_tpl->tpl_vars['sd_item']->key;
?>
		<?php $_smarty_tpl->tpl_vars['numbering'] = new Smarty_variable($_smarty_tpl->tpl_vars['numbering']->value+1, null, 0);?>
			<?php if ($_smarty_tpl->tpl_vars['numbering']->value%2==0){?>
				<tr>
			<?php }else{ ?>
                <tr class="odd">
			<?php }?>
			
			<td valign='top'>
				<input type="checkbox" name="choices" class='choices-<?php echo $_smarty_tpl->tpl_vars['numbering']->value;?>
' id=choices value='<?php echo $_smarty_tpl->tpl_vars['sd_item']->value->id;?>
'/>
			</td>
			
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['numbering']->value;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->msisdn;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->service;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->adn;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->operator;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->channel_subscribe;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->channel_unsubscribe;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->subscribed_from;?>
</td>
			<td valign='top'><?php echo $_smarty_tpl->tpl_vars['sd_item']->value->subscribed_until;?>
</td>
			<td valign='top'>
				<span style="color:red;"><?php echo $_smarty_tpl->tpl_vars['statusConv']->value[$_smarty_tpl->tpl_vars['sd_item']->value->active];?>
</span>
			</td>
			
			<td class="last" align='center'>
				<?php if ($_smarty_tpl->tpl_vars['sd_item']->value->active==1){?>
					<input style='font-size:10px;' type="button" name="inactive" id="inactive" value="Inactive" onClick="inactiveBut(<?php echo $_smarty_tpl->tpl_vars['sd_item']->value->id;?>
)">
				<?php }?>
			</td>
		</tr>				
		<?php } ?>
	</table>		
	</form>
	<?php }else{ ?>  
		<?php $_smarty_tpl->tpl_vars['nodata'] = new Smarty_variable(1, null, 0);?>
	<?php }?>
</div>
<?php }} ?>