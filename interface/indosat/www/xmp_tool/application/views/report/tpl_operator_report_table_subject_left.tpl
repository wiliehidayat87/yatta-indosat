{foreach from=$mostLeftColumn item=row name=i}
    <tr class="{$type}-{$operatorId}">
        <td class="mostLeftColumn" {if ($smarty.foreach.i.iteration%2) == 0}style="background:#efefef;"{/if}>{$row|upper}</td>
        <td style="display: none;"></td>
    </tr>
{/foreach}
