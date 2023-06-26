<div id="left" style="clear:both;float:left;height:500px;width:200px;overflow:hidden;overflow-x:scroll;">
    <table class="datagrid2 dg2-left" width="200px">
        <tr>
            <th height="38px">NAME</th>
            <th width="1px" style="display: none;"></th>
        </tr>

        {foreach from=$mostLeftColumn item=row name=i}
            <tr id="{$row.id}-{$row.name}">
                {if $row.name == 'MO' || $row.name == 'MT' || $row.name == 'DELIVERED' || $row.name == 'GROSS'}
                    <td class="mostLeftColumn" style="padding:2px 2px 0 2px;text-align:left;"><span class="{if $smarty.foreach.i.iteration > 5}{if $row.name != 'GROSS'}collapsed {/if}{/if}getCharging {if ($row.name == 'MO' || $row.name == 'MT' || $row.name == 'DELIVERED') && ($smarty.foreach.i.iteration > 5)}pointer{/if}" id="{$row.id}" title="{$row.name|upper}">&nbsp;&nbsp;&nbsp;{$row.name|upper} {if ($row.name == 'MT' || $row.name == 'DELIVERED') && ($smarty.foreach.i.iteration > 5)}(Toggle Charging){/if}</span></td>
                {else}
                    <td class="mostLeftColumn" style="padding:2px 2px 0 2px;background:#eee; text-align:center;">{$row.name|upper}</td>
                {/if}
                <td style="display: none;"></td>
            </tr>
        {/foreach}
    </table>
</div>

<div id="right" style="float: left; height: 500px; width:756px; overflow: auto;">
    <table class="datagrid2 dg2-right" width="100%">
        <tr>
            {foreach from=$columnName item=column name=i}
                <th height="38px">{$column|upper}</th>
            {/foreach}
        </tr>

        {foreach from=$revenuePerOperator item=operator}
            <tr class="highlightRow">
                <td colspan="{$columnLength}" style="padding:2px 2px 0 2px;background:#eee;">
                    &nbsp;
                </td>
            </tr>

            {foreach from=$operator.child item=type name=i}
                <tr>
                    {foreach from=$type item=column}
                        <td style="padding:2px 2px 0 2px;{$column.color}">
                            {$column.total}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        {/foreach}
    </table>
</div>

