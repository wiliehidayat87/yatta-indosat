<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 11:50:25
         compiled from "application/views/traffic/mo_traffic_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:98778715550bc2f91837413-21976703%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d66fe5ff61f64d61ab723a16165657f145b17c9' => 
    array (
      0 => 'application/views/traffic/mo_traffic_view.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98778715550bc2f91837413-21976703',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'todayMOTotal' => 0,
    'todayMOYesterday' => 0,
    'todayMOLastSevenDays' => 0,
    'base_url' => 0,
    'total_mo_months' => 0,
    'totalMOThisMonth' => 0,
    'totalMOLastMonths' => 0,
    'totalMOLastSixMonths' => 0,
    'dataRangeInfo' => 0,
    'adnInfo' => 0,
    'operatorInfo' => 0,
    'typeInfo' => 0,
    'serviceInfo' => 0,
    'msisdnInfo' => 0,
    'smsInfo' => 0,
    'adn_list' => 0,
    'adn_item' => 0,
    'type_list' => 0,
    'type_item' => 0,
    'operator_list' => 0,
    'operator_item' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc2f91940cd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc2f91940cd')) {function content_50bc2f91940cd($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<div class="pagetitle mo-traffic">MO Traffic</div>
    <div class="middletop">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="roundedbox bluebox" style="margin-right:15px;">
                        <div class="boxheader"><h2>Today MO</h2></div>
                        <div class="boxcontent">
                            <div class="bignumber"><?php echo $_smarty_tpl->tpl_vars['todayMOTotal']->value;?>
</div>
                            <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left">Period</td>
                                    <td align="right">Total MO</td>
                                </tr>
                                <tr>
                                    <td align="left">Yesterday</td>
                                    <td align="right"><?php echo $_smarty_tpl->tpl_vars['todayMOYesterday']->value;?>
</td>
                                </tr>
                                <tr class="last">
                                    <td align="left">Last 7 days</td>
                                    <td align="right"><?php echo $_smarty_tpl->tpl_vars['todayMOLastSevenDays']->value;?>
</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="roundedbox bluebox" style="margin-right:15px;">
                        <div class="boxheader"><h2>MO Trends</h2></div>
                        <div class="boxoption">Option</div>
                        <div class="boxcontent" style="background-color: #FFF">
                            <div id="mo-chart">Please install flash plugin to see chart</div>
                            <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/swfobject.js"></script>
                            <Script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/traffic/mo_chart.js"></script>
                        </div>
                        <div class="boxoption-content">
                            <form>
                                <select name="chart-timespan" id="chart-timespan">
                                    <option value="3" selected="selected">Last 3 Months</option>
                                    <option value="6">Last 6 Months</option>
                                    <option value="12">Last 12 Months</option>
                                </select>
                                <br/>
                                <input type="submit" id="chart-button" value="Change" style="float: right" />
                            </form>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="roundedbox bluebox last">
                        <div class="boxheader"><h2>Total MO <?php echo $_smarty_tpl->tpl_vars['total_mo_months']->value;?>
</h2></div>
                        <div class="boxcontent">
                            <div class="bignumber"><?php echo $_smarty_tpl->tpl_vars['totalMOThisMonth']->value;?>
</div>
                            <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left">Period</td>
                                    <td align="right">Total MO</td>
                                </tr>
                                <tr>
                                    <td align="left">Last months</td>
                                    <td align="right"><?php echo $_smarty_tpl->tpl_vars['totalMOLastMonths']->value;?>
</td>
                                </tr>
                                <tr class="last">
                                    <td align="left">Last 6 months</td>
                                    <td align="right"><?php echo $_smarty_tpl->tpl_vars['totalMOLastSixMonths']->value;?>
</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="midlemidle">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Open Search Panel</div>
                <div class="searchinfo">
                    <?php echo $_smarty_tpl->tpl_vars['dataRangeInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['adnInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['operatorInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['typeInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['serviceInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['msisdnInfo']->value;?>
<?php echo $_smarty_tpl->tpl_vars['smsInfo']->value;?>

                </div>
                <div class="clear"></div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
                <form name="mo-traffic-form" id="mo-traffic-form" > 
                    <div class="daterange">
                        Date Range
                        <table width="325px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td valign="middle">&nbsp; From &nbsp;</td>
                                <td>
                                    <input type="text" name="dateFrom" id="dateFrom" class="Sessiondate"/>
                                </td>
                                <td valign="middle">&nbsp; To &nbsp;</td>
                                <td>
                                    <input type="text" name="dateTo" id="dateTo" class="Sessiondate" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>ADN</td>
                            <td>
                                <select name="adn-list" id="adn-list">
                                    <option value="">All</option>
                                    <?php  $_smarty_tpl->tpl_vars['adn_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['adn_item']->_loop = false;
 $_smarty_tpl->tpl_vars['adn_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adn_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['adn_item']->key => $_smarty_tpl->tpl_vars['adn_item']->value){
$_smarty_tpl->tpl_vars['adn_item']->_loop = true;
 $_smarty_tpl->tpl_vars['adn_key']->value = $_smarty_tpl->tpl_vars['adn_item']->key;
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['adn_item']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['adn_item']->value['name'];?>
</option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>Type</td>
                            <td>
                                <select name="type-list" id="type-list">
                                    <option value="">All</option>
                                    <?php  $_smarty_tpl->tpl_vars['type_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type_item']->_loop = false;
 $_smarty_tpl->tpl_vars['type_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['type_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type_item']->key => $_smarty_tpl->tpl_vars['type_item']->value){
$_smarty_tpl->tpl_vars['type_item']->_loop = true;
 $_smarty_tpl->tpl_vars['type_key']->value = $_smarty_tpl->tpl_vars['type_item']->key;
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['type_item']->value['type'];?>
"><?php echo $_smarty_tpl->tpl_vars['type_item']->value['type'];?>
</option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>MSISDN</td>
                            <td><input type="text" id="msisdnInput" name="msisdnInput" /></td>
                            <td><input name="msisdnCheckbox" id="msisdnCheckbox" type="checkbox" value="1" /> Unique MSISDN</td>
                        </tr>   
                        <tr>
                            <td>Operator</td>
                            <td>
                                <select name="operator-list" id="operator-list">
                                    <option value="">All</option>
                                    <?php  $_smarty_tpl->tpl_vars['operator_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['operator_item']->_loop = false;
 $_smarty_tpl->tpl_vars['operator_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['operator_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['operator_item']->key => $_smarty_tpl->tpl_vars['operator_item']->value){
$_smarty_tpl->tpl_vars['operator_item']->_loop = true;
 $_smarty_tpl->tpl_vars['operator_key']->value = $_smarty_tpl->tpl_vars['operator_item']->key;
?>
                                        <option value="<?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['operator_item']->value['name'];?>
</option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>Services</td>
                            <td><input class="autocomplete" id="serviceName" type="text" name="serviceName" /></td>
                            <td>SMS</td>
                            <td><input type="text" id="smsRequest" name="smsRequest" /></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Limit</td>
                            <td>
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
                            </td>
                            <td><input name="Submit" id="search" type="submit" value="Search" /></td>
                            <td><input name="btnResetPanel" id="btnResetPanel" type="button" value="Reset" /></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="info">
            <div class="lightbluebox sqlquery">
                <div class="icon">
                    <div class="infotitle">SQL Query</div>
                    <div id="sqlQuery"></div>
                </div>
            </div>
            <div class="lightbluebox">
                <div class="infotitle">Count Result</div>
                <div class="count" id="countTotal"></div>
            </div>
            <div class="lightbluebox" style="margin-right:0">
                <div class="infotitle">Execution Time</div>
                <div class="count" id="countTime"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="middlebottom">
        <div class="export">             
            <form method="post" action="export_file">            
            Export as : <input type="submit" name="exportPDF" value="PDF" onclick=""> &nbsp;<input type="submit" name="exportXLS" value="XLS">
            <input type="hidden" id="exportFromDate" name="exportFromDate" value="">
            <input type="hidden" id="exportUntilDate" name="exportUntilDate" value="">
            <input type="hidden" id="exportOperator" name="exportOperator" value="">
            <input type="hidden" id="exportADN" name="exportADN" value="">
            <input type="hidden" id="exportMSISDN" name="exportMSISDN" value="">
            <input type="hidden" id="exportMSISDNCheck" name="exportMSISDNCheck" value="">
            <input type="hidden" id="exportService" name="exportService" value="">
            <input type="hidden" id="exportType" name="exportType" value="">
            <input type="hidden" id="exportSMS" name="exportSMS" value="">
            </form>
        </div>
        <div class="boxheader reporttable">
            <table id="MOTraffic-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th scope="col">MO Date</th>
                        <th scope="col">Operator</th>
                        <th scope="col">ADN</th>
                        <th scope="col">MSIDN</th>
                        <th scope="col">Service</th>
                        <th scope="col">Type</th>
                        <th scope="col" class="last">SMS Request</th>
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
            <select name="pageLimitBottom" id="pageLimitBottom">
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