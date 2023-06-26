<div id="pageheading">
    <h2>Close Reason Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <div class="clearfix" style="font-weight: bold;">
        <a class="btnAdd" id="add"><span>Customize Close Reason Report</span></a>
    </div>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="2">
            <tr>
                <td width="40%" style="text-align: right;">Short Code:</td>
                <td>
                    <select id="shortCode">
                        <option value="">-- SHORT CODE --</option>
                        {if $shortCode != null}
                            {foreach from=$shortCode item=row}
                                <option value="{$row}" {if $defaultShortCode == $row}selected=selected{/if}>{$row}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Operator:</td>
                <td>
                    <select id="operatorId">
                        <option value="">All Operators</option>
                        {if $operator != null}
                            {foreach from=$operator item=row}
                                <option value="{$row.operator_code}">{$row.operator}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Service:</td>
                <td>
                    <select id="serviceId">
                        <option value="">All Service</option>
                        {if $service != null}
                            {foreach from=$service item=row}
                                <option value="{$row}">{$row}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Display:</td>
                <td>
                    <select id="display">
                        <option value="1">Value (%)</option>
                        <option value="2">Value</option>
                        <option value="3">%</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;">Period:</td>
                <td>
                    <input type="radio" id="periodDate" name="period" value="periodDate" checked/> <label for="periodDate">Month</label>: &nbsp; {html_select_date start_year="2005" end_year="2020" display_days=false reverse_years=true}<br />
                    <input type="radio" id="periodLast" name="period" value="periodLast" /> <label for="periodLast">Last</label>: 
                    <select id="numberOfLastDays">
                        <option value="30">30</option>
                        <option value="25">25</option>
                        <option value="20">20</option>
                        <option value="15">15</option>
                        <option value="10">10</option>
                        <option value="5">5</option>
                    </select> days
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Limit:</td>
                <td>
                    <select id="limit">
                        <option value="0">Everything</option>
                        <option value="3">Top 3</option>
                        <option value="5">Top 5</option>
                        <option value="10">Top 10</option>
                        <option value="25">Top 25</option>
                        <option value="50">Top 50</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;">Sorting:</td>
                <td>
                    <input type="radio" id="limitTotal" name="sorting" value="total" checked/> <label for="limitTotal">Total</label>
                    <input type="radio" id="limitYesterday" name="sorting" value="yesterday" /> <label for="limitYesterday">Yesterday Status</label>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
            </tr>
        </table>
    </div>
</div>

<div class="warning"></div>
<fieldset style="border:none; border-top:1px solid #000; margin:0px; padding: 0px;">
<legend id="chartControl" style="cursor:pointer;font-weight: bold;margin-right:4px;">Show Chart</legend>
<div id="chartTable" style="display:none;">
    <div class="onecolumns">
        <div class="col first">
            <div class="modulebox">
                <h3 class="module-header">Top 5 Close Reason</h3>
                <div class="module-content">
                    <div id="chart_box_1"></div>
                </div>
                <!--module-content -->
            </div>
            <!--modulebox -->
        </div>
        <!--column first -->
    </div>
    
    <div class="twocolumns">
        <div class="col first">
            <div class="modulebox">
                <h3 class="module-header">Top 5 Close Reason</h3>
                <div class="module-content" style="height: 180px;">
                    <div id="chart_box_2"></div>
                </div>
                <!--module-content -->
            </div>
            <!--modulebox -->
        </div>
        <!--col second -->
    
        <div class="col last">
            <div class="modulebox">
                <h3 class="module-header">Top Top 5 Close Reason with Significant Changes</h3>
                <div class="module-content">
                    <div id="chart_box_3"></div>
                </div>
                <!--module-content -->
            </div>
            <!--modulebox -->
        </div>
        <!--column last -->
    </div>    
</div>
</fieldset>

<div id="closeReasonTable">
</div>

