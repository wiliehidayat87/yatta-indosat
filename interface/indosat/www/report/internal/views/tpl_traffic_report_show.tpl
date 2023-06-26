<div id="pageheading">
    <h2>Traffic Report</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <div class="clearfix" style="font-weight: bold;">
        <a class="btnAdd" id="add"><span>Customize Traffic Report</span></a>
    </div>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="2">
            <tr>
                <td width="40%" style="text-align: right;">Start Date:</td>
                <td>
                    <input type="text" id="startDate" name="startDate" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">End Date:</td>
                <td>
                    <input type="text" id="endDate" name="endDate" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Short Code:</td>
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
                <td style="text-align: right;">MSISDN:</td>
                <td>
                    <input type="text" id="msisdn" name="msisdn" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Type:</td>
                <td>
                    <select id="type">
                        <option value="mo">MO</option>
                        <option value="mt">MT</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Subject:</td>
                <td>
                    <input type="text" id="subject" name="subject" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Request:</td>
                <td>
                    <input type="text" id="request" name="request" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Status:</td>
                <td>
                    <select id="status">
                        <option value="all">All</option>
                        <option value="error">Error Reply</option>
                        <option value="received">Received</option>
                        <option value="rejected">Rejected</option>
                        <option value="delivered">Delivered</option>
                        <option value="unknown">Unknown</option>
                        <option value="inProgress">In Progress</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Limit:</td>
                <td>
                    <select id="limit">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox" id="archive" name="archive" value="true" /><label for="archive">&nbsp;Use ARCHIVE database</label></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
            </tr>
        </table>
    </div>
</div>

<div id="trafficTable"></div>

