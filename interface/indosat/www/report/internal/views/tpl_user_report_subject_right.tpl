{foreach from=$rightColumn item=row name=i}
    <tr class="{$closeReason}{$operatorId} {if ($smarty.foreach.i.iteration%2) == 0}alt{/if}">
        {foreach from=$row item=column}
            <td>
                {$column}
            </td>
        {/foreach}
    </tr>
{/foreach}
