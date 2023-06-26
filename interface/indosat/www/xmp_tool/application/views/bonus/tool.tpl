{include file="common/tpl_header.tpl"}

<div class="pagetitle">BONUS Tools</div>
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
                	<div>
                		<table width="325px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                            <td>MSISDN</td>
                            <td><input type="text" id="msisdnInput" name="msisdnInput" /></td>
                            </tr>
                		</table>
                	</div>
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
					<table class="searchfilter" width="50%" border="0" cellspacing="0" cellpadding="0">
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
        <div class="boxheader reporttable">
            <table id="MOTraffic-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th scope="col">MSISDN</th>
                        <th scope="col">Date</th>
                        <th scope="col">Content Link</th>
                        <th scope="col" class="last">Time Downloaded</th>
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
