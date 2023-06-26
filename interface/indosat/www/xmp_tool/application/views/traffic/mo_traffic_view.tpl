{include file="common/tpl_header.tpl"}
<div class="pagetitle mo-traffic">MO Traffic</div>
    <div class="middletop">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="roundedbox bluebox" style="margin-right:15px;">
                        <div class="boxheader"><h2>Today MO</h2></div>
                        <div class="boxcontent">
                            <div class="bignumber">{$todayMOTotal}</div>
                            <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left">Period</td>
                                    <td align="right">Total MO</td>
                                </tr>
                                <tr>
                                    <td align="left">Yesterday</td>
                                    <td align="right">{$todayMOYesterday}</td>
                                </tr>
                                <tr class="last">
                                    <td align="left">Last 7 days</td>
                                    <td align="right">{$todayMOLastSevenDays}</td>
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
                            <script type="text/javascript" src="{$base_url}public/js/swfobject.js"></script>
                            <Script type="text/javascript" src="{$base_url}public/js/traffic/mo_chart.js"></script>
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
                        <div class="boxheader"><h2>Total MO {$total_mo_months}</h2></div>
                        <div class="boxcontent">
                            <div class="bignumber">{$totalMOThisMonth}</div>
                            <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left">Period</td>
                                    <td align="right">Total MO</td>
                                </tr>
                                <tr>
                                    <td align="left">Last months</td>
                                    <td align="right">{$totalMOLastMonths}</td>
                                </tr>
                                <tr class="last">
                                    <td align="left">Last 6 months</td>
                                    <td align="right">{$totalMOLastSixMonths}</td>
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
                    {$dataRangeInfo}{$adnInfo}{$operatorInfo}{$typeInfo}{$serviceInfo}{$msisdnInfo}{$smsInfo}
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
                                    {foreach from=$adn_list item=adn_item key=adn_key}
                                        <option value="{$adn_item.name}">{$adn_item.name}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td>Type</td>
                            <td>
                                <select name="type-list" id="type-list">
                                    <option value="">All</option>
                                    {foreach from=$type_list item=type_item key=type_key}
                                        <option value="{$type_item.type}">{$type_item.type}</option>
                                    {/foreach}
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
                                    {foreach from=$operator_list item=operator_item key=operator_key}
                                        <option value="{$operator_item.name}">{$operator_item.name}</option>
                                    {/foreach}
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
                                    {foreach from=$pageLimit item=item key=key}
                                        <option value="{$item}">{$item}</option>
                                    {/foreach}
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
                {foreach from=$pageLimit item=item key=key}
                    <option value="{$item}">{$item}</option>
                {/foreach}
            </select>
        </div>
    </div>
{include file="common/tpl_footer.tpl"}