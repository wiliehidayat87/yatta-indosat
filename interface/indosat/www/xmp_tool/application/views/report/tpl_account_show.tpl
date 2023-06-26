<div id="pageheading">
    <h2>Dashboard</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <div class="clearfix">
        <a class="btnAdd" id="add"><span>Add Dashboard Widget</span></a>
    </div>

    <div id="loadContainer" style="display:none;">
    <ul class="twocolumns">
        <li class="col first">
        <table cellspacing="10">
            <tr>
                <td width="30%" style="text-align: right;">Name:</td>
                <td><input type="text" id="name" name="name" size="20" /></td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right;">Topic</td>
                <td>
                    <select id="topic" name="topic">
                        <option value="">Select Option</option>
                        <option value="traffic">Traffic</option>
                        <option value="revenue">Revenue</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right;">Group By</td>
                <td>
                    <select id="group" name="group">
                        <option value="">Select Group By</option>
                        <option value="operator">Operator</option>
                        <option value="sdc">Short Code</option>
                        <option value="service"> Service</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right;">Chart Type</td>
                <td>
                    <select id="chart_type" name="chart_type">
                        <option value="">Select Chart Type</option>
<!--                        <option value="number">Number</option>-->
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="stacked">Stacked Chart</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right;">Data</td>
                <td>
                    <select id="data" name="data">
                        <option value="">Select Data</option>
                        <option value="3">Top 3</option>
                        <option value="5">Top 5</option>
                        <option value="10">Top 10</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right;">Date Range</td>
                <td>
                    <input type="text" name="date" id="date" value="{$dateRange}">
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <button type="submit" id="chart-submit">Submit</button>
                    <button type="submit" id="chart-update" rel="" title="">Update</button>
                    <button type="reset" id="chart-cancel">Cancel</button>
                </td>
            </tr>
        </table>
        </li>
        <!--col first -->
        
        <li class="col last">
        </li>
        </ul>
    </div>
</div>

<div>       
    <ul id="chart-table">
        {section name=chart loop=$smarty.const.MAX_DASHBOARD_CHART}             
            <li id="box_{$smarty.section.chart.iteration}" style="display: {if isset($chartData[$smarty.section.chart.iteration])}block{else}none{/if};">
                <div class="modulebox">
                    <h3 class="module-header">
                        {if isset($chartData[$smarty.section.chart.iteration].name) && !empty($chartData[$smarty.section.chart.iteration].name)}{$chartData[$smarty.section.chart.iteration].name}{else}{$chartData[$smarty.section.chart.iteration].title}{/if}
                    </h3>
                    <div class="module-content">
                        <div style="font-size:10px;">Date: <span>{if isset($chartData[$smarty.section.chart.iteration].rangedate)}{$chartData[$smarty.section.chart.iteration].rangedate}{/if}</span> <a href="#" rel="{if isset($chartData[$smarty.section.chart.iteration].id)}{$chartData[$smarty.section.chart.iteration].id}{/if}" boxid="{$smarty.section.chart.iteration}" class="chart-config">config</a> | <a href="#" rel="{if isset($chartData[$smarty.section.chart.iteration].id)}{$chartData[$smarty.section.chart.iteration].id}{/if}" boxid="{$smarty.section.chart.iteration}" class="chart-remove">remove</a></div>
                        {assign var=iteration value=$smarty.section.chart.iteration}
                        <div id="content_chart_box_{$smarty.section.chart.iteration}" {if isset($chart_box[$iteration])}style="display:none;"{/if}>
                            <div id="chart_box_{$smarty.section.chart.iteration}"></div>
                        </div>
                        <div id="content_table_box_{$smarty.section.chart.iteration}" {if !isset($chart_box[$iteration])}style="display:none;"{/if}>{if isset($chart_box[$iteration])}{$chart_box[$iteration]}{/if}</div>
                    </div>
                </div>
                <!--modulebox -->
            </li>
        {/section}
    </ul>
</div>
