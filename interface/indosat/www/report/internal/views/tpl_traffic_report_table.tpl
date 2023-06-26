<div>
    <table class="datagrid2" width="100%">
        <tr>
            {foreach from=$columnName item=column name=i}
                <th height="38px">{$column|upper}</th>
            {/foreach}
        </tr>

        {foreach from=$data item=traffic name=i}
            <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
                {foreach from=$traffic item=column}
                    <td style="text-align:center;padding:2px 2px 0 2px;">
                        {$column}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
</div>

<div style="text-align:center;">
    {if $page > 1}
        <span class="pagination pointer" title="1"><strong>First</strong></span> &nbsp; <span class="pagination pointer" title="{$previousPage}"><strong>Previous</strong></span>
    {/if}

    &nbsp; ({$page} of {$totalPage}) &nbsp;

    {if $page < $totalPage && $page != $totalPage}
        <span class="pagination pointer" title="{$nextPage}"><strong>Next</strong></span> &nbsp; <span class="pagination pointer" title="{$totalPage}"><strong>Last</strong></span>
    {/if}
</div>

