<div id="pageheading">
    <h2>Content Download Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <span id="buttonBox" style="font-weight: bold;">
        <a class="btnAdd" id="custom"><span>Customize Content Download Report</span></a>
    </span>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="10">
            <tr>
                <td style="text-align: right;">Operator:</td>
                <td>
                    <select id="operator">
                        <option value="">All</option>
                        {if $operator != false}
                            {foreach from=$operator item=row}
                                <option value="{$row.operator_code}">{$row.operator}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
			<tr>
                <td style="text-align: right;">Content Owner:</td>
                <td>
                    <select id="contentOwner">
                        <option value="">All</option>
                        {if $contentOwner != false}
                            {foreach from=$contentOwner item=row}
                                <option value="{$row.id}">{$row.name}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Content Type:</td>
                <td>
                    <select id="contentType">
                        <option value="">All</option>
                        {foreach from=$contentType item=name key=key}
                            <option value="{$key}">{$name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;">Year:</td>
                <td>
                    {html_select_date start_year="2005" end_year="2020" display_days=false display_months=false reverse_years=true}
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;"></td>
                <td>
                	<div><input type="radio" id="daily" name="mode" value="daily" checked="checked"><label for="daily"> Daily on</label> {html_select_date display_years=false display_days=false}</div>
					<div><input type="radio" id="monthly" name="mode" value="monthly"><label for="monthly"> Monthly</label></div>
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
                <h3 class="module-header">Top 5 Content Code</h3>
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
                <h3 class="module-header">Top 10 Content Owner</h3>
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
                <h3 class="module-header">Top Top 5 Content Code with Significant Changes</h3>
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

<div id="contentDownloadTable"></div>

