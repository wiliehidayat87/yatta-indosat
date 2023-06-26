<div id="left" style="clear:both;float:left;height:500px;width:450px;overflow:hidden;overflow-x:scroll;">
    <table class="datagrid2 dg2-left" width="450px">
        <tr>
            <th width="225" height="38px">Close Reason</th>
            <th height="38px">Description</th>
        </tr>

        {foreach from=$mostLeftColumn item=operator name=i}
            <tr>
                <td colspan="2" style="font-weight:bold;padding:2px 2px 0 2px;background:#eee;text-align:center;">{$operator.name|upper}</td>
                <td style="display: none;"></td>
            </tr>

            {foreach from=$operator.child item=closereason name=j}
                <tr>
                    <td class="mostLeftColumn" style="padding:2px 2px 0 2px;text-align:left;"><span id="{$closereason.id}" class="collapsed getService pointer">{$closereason.displayClosereason}</span></td>
                    <td class="mostLeftColumn" style="padding:2px 2px 0 2px;text-align:left;">{$closereason.description}</td>
                </tr>
            {/foreach}
        {/foreach}
    </table>
</div>

<div id="right" style="float:left;height:500px;width:500px;overflow:auto;">
    <table class="datagrid2 dg2-right" width="100%">
        <tr>
            {foreach from=$columnName item=column name=i}
                <th height="38px">{$column|upper}</th>
            {/foreach}
        </tr>

        {foreach from=$closeReason item=operator}
            <tr class="highlightRow">
                <td colspan="{$columnLength}" style="padding:2px 2px 0 2px;background:#eee;">
                    &nbsp;
                </td>
            </tr>

            {foreach from=$operator.child item=type name=i}
                <tr>
                    {foreach from=$type item=column}
                        <td style="padding:2px 2px 0 2px; {$column.color}">
                            {$column.total}
                        </td>
                    {/foreach}
                </tr>
            {/foreach}
        {/foreach}
    </table>
</div>

