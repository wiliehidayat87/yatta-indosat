<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:41:34
         compiled from "application/views/cs/history.tpl" */ ?>
<?php /*%%SmartyHeaderCode:179278920850bc57ae66ef02-34976636%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '354319ce36d79942de7d00eab6d0d09bc0a51d2c' => 
    array (
      0 => 'application/views/cs/history.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179278920850bc57ae66ef02-34976636',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'adn' => 0,
    'adn_item' => 0,
    'operator' => 0,
    'operator_item' => 0,
    'service' => 0,
    'service_item' => 0,
    'subject' => 0,
    'subject_item' => 0,
    'startyear' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc57ae72887',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc57ae72887')) {function content_50bc57ae72887($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="pagetitle">CS Tools</div>
<div class="middletop">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">MO/Mt History</div>
            <div class="search-area">

            </div>
            <div class="clear"></div>
        </div>
        <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form id="history" method='post'>
                <table cellspacing="2">
                    <tr>
                        <td width="40%" style="text-align: right;">ADN :</td>
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
                        <td width="40%" style="text-align: right;">MSISDN :</td>
                        <td>
                            <input type='text' name='msisdn' id='msisdn'>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: right;">Message Data :</td>
                        <td>
                            <input type='text' name='msgdata' id='msgdata'>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: right;">Operator:</td>
                        <td>
                            <select id="operator" name="operator">
                                <option value="">-- operator --</option>
                                <?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value->id;?>
"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value->long_name;?>
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
                        <td width="40%" style="text-align: right;">Subject:</td>
                        <td>
                            <select id="subject" name="subject">
                                <option value="">-- subject --</option>
                                <?php  $_smarty_tpl->tpl_vars['subject_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subject_item']->_loop = false;
 $_smarty_tpl->tpl_vars['subject_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subject']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subject_item']->key => $_smarty_tpl->tpl_vars['subject_item']->value){
$_smarty_tpl->tpl_vars['subject_item']->_loop = true;
 $_smarty_tpl->tpl_vars['subject_key']->value = $_smarty_tpl->tpl_vars['subject_item']->key;
?>
                                    <option value="<?php echo $_smarty_tpl->tpl_vars['subject_item']->value->SUBJECT;?>
"><?php echo $_smarty_tpl->tpl_vars['subject_item']->value->SUBJECT;?>
</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>   
                    <tr>
                        <td width="40%" style="text-align: right;">Date:</td>
                        <td>
                            <select id="month" name="month">
                                <option value="">-- Month --</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <?php $_smarty_tpl->tpl_vars['startyear'] = new Smarty_variable(smarty_modifier_date_format(time(),"%Y"), null, 0);?>



                            <select id="year" name="year">
                                <option value="">-- Year --</option>
                                <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["year"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['name'] = "year";
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop'] = is_array($_loop=-6) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'] = (int)$_smarty_tpl->tpl_vars['startyear']->value;
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'] = 1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["year"]['total']);
?>

                                    <option><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['year']['index']+$_smarty_tpl->tpl_vars['startyear']->value;?>
</option>
                                <?php endfor; endif; ?>
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
    <div class="boxheader reporttable">
        <table id="historyTable" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Adn</th>
                    <th>Msisdn</th>
                    <th>Operator</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th nowrap>Last Status</th>
                    <th nowrap>Msg Status</th>
                    <th nowrap>Close Reason</th>
                    <th>Price</th>
                    <th>Subject</th>
                    <th>Date</th>
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