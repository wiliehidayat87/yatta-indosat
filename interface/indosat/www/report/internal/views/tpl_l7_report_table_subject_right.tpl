{foreach from=$rightColumn item=row name=i}
    <tr class="{$type}-{$operatorId} {if ($smarty.foreach.i.iteration%2) == 0}alt{/if}">
        {foreach from=$row item=column}
            <td style="{$column.color}">
                {$column.total}
            </td>
        {/foreach}
    </tr>
{/foreach}
