<div id="pageheading">
    <h2>Subject Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <span id="buttonBox" style="font-weight: bold; float: left;">
        <a class="btnAdd" id="custom"><span>Customize Subject Report</span></a>
    </span>

	<div class="clearfix">
    	<span class="searchfield">
        	<input type="text" class="textfield search" value="search"/>
        </span>
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
                        <option value='all'>All</option>
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
                        <option value="all">All</option>
                        {if $operator != false}
                            {foreach from=$operator item=row}
                                <option value="{$row.operator_code}">{$row.operator}</option>
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

<!--<div id="chart_revenue"></div>-->
<div id="subjectTable"></div>

