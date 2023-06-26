<div id="left" style="clear:both;float:left;height:500px;width:200px;overflow:hidden;overflow-x:scroll;">
    <table class="datagrid2 dg2-left" width="200px">
        <tr>
            <th width="200" height="38px">Service</th>
            <th width="1px" style="display: none;"></th>
        </tr>

        {foreach from=$mostLeftColumn item=service name=i}
            <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
                <td style="font-weight:bold;text-align:left;">{$service.service|upper}</td>
                <td style="display: none;"></th>
            </tr>
        {/foreach}
    </table>
</div>

<div id="right" style="float:left;height:500px;width:756px;overflow:scroll;">
    <table class="datagrid2 dg2-right" width="100%">
        <tr>
            {foreach from=$columnName item=column name=i}
                <th height="38px">{$column|upper}</th>
            {/foreach}
        </tr>

        {foreach from=$rightColumn item=user name=i}
            <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
                {foreach from=$user item=column}
                    <td style="text-align:center;padding:2px 2px 0 2px; {$column.color}">
                        {$column.total}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
</div>

