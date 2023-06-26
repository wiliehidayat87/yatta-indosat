<div id="pageheading">
    <h2>User Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <div class="clearfix" style="font-weight: bold;">
        <a class="btnAdd" id="add"><span>Customize User Report</span></a>
    </div>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="2">
            <tr>
                <td width="40%" style="text-align: right;">Period:</td>
                <td>
                    {html_select_date start_year="2005" end_year="2020" display_days=false reverse_years=true}
                </td>
            </tr>
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
                <h3 class="module-header">Top 5 Service with highest Subscriber</h3>
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
                <h3 class="module-header">Top 10 Service with highest Subscriber</h3>
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
                <h3 class="module-header">Top Top 5 Service with highest Subscriber</h3>
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
<div id="userTable"></div>
