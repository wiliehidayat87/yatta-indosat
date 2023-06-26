<div id="pageheading">
    <h2>Subscriber Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <span id="buttonBox" style="font-weight: bold;">
        <a class="btnAdd" id="custom"><span>Customize Subscriber Report</span></a>
    </span>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="10">
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
                        {if $shortcode != false}
                            {foreach from=$shortcode item=row}
                                <option value="{$row}" {if $defaultShortCode == $row}selected=selected{/if}>{$row}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Operator:</td>
                <td>
                    <select id="operator">
                        <option value="">All</option>
                        {if $operator != false}
                            {foreach from=$operator item=row}
                                <option value="{$row.operator_code}">{$row.operator}_{$row.shortCode}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Service:</td>
                <td>
                    <select id="service">
                        <option value="">All</option>
                        {if $service != false}
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
                <h3 class="module-header">Top 5 SubTotal</h3>
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
                <h3 class="module-header">Reg/Unreg All Service</h3>
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
                <h3 class="module-header">Top 5 SubTotal</h3>
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

<div id="subscriberTable"></div>

