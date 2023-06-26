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
                <td style="text-align: right;">ADN:</td>
                <td>
                    <input type="text" id="adn" name="adn" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Service:</td>
                <td>
                    <select id="service">
                        <option value="">-- SERVICE --</option>
                        {if $service != null}
                            {foreach from=$service item=row}
                                <option value="{$row.name}">{$row.name}</option>
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
                                <option value="{$row.name}">{$row.long_name}</option>
                            {/foreach}
                        {/if}
                    </select>
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;">Date:</td>
                <td>
                    <input type="text" id="date" name="date" value="" />
                </td>
            </tr>
            <tr>
                <td style="text-align: right;">Channel:</td>
                <td>
                    <input type="text" id="channel" name="channel" value="" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
            </tr>
        </table>
    </div>
</div>

<div id="userTable"></div>

