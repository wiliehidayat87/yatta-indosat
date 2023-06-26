{if $searchData!='' && count($searchData) > 0}
    {$nodata=0}

<div id="chartTable" align=center>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th>No</th>
			<th>ADN</th>
			<th>MSISDN</th>
			<th>OPERATOR</th>
			<th>SERVICE</th>
			<th>MSG DATA</th>
			<th width=65px;>MSG STATUS</th>
			<th width=70px;>LAST STATUS</th>
			<th>PRICE</th>
			<th>SUBJECT</th>
			<th width=90px; class="last">DATE</th>
		</tr>
				
		{foreach from=$searchData item=sd_item key=sd_key}
		{$numbering=$numbering+1}
			{if $numbering mod 2==0}
				<tr>
			{else}
                <tr class="odd">
			{/if}
				
			<td valign='top'>{$numbering}</td>
			<td valign='top'>{$sd_item->ADN}</td>
			<td valign='top'>{$sd_item->MSISDN}</td>
			<td valign='top'>{$sd_item->long_name}</td>
			<td valign='top'>{$sd_item->SERVICE}</td>
			<td valign='top'>{$sd_item->MSGDATA}</td>
			<td valign='top'>{$sd_item->MSGSTATUS}</td>
			<td valign='top'>{$sd_item->MSGLASTSTATUS}</td>
			<td valign='top'>{$sd_item->PRICE}</td>
			<td valign='top'>{$sd_item->SUBJECT}</td>
			<td valign='top' class="last">{$sd_item->MSGTIMESTAMP}</td>
		</tr>			
		{/foreach}
	
	</table>	
	
		{else}  
			{$nodata=1}
		{/if}
<!--	
	<div style="text-align:center;">
	
    {if $page > 1} 
        <span class="pagination pointer" title="1"><strong>First</strong></span> &nbsp; <span class="pagination pointer" title="{$previousPage}"><strong>Previous</strong></span>
    {/if}

	{if $nodata !=1}
    &nbsp; ( {$page} of {$totalPage} ) &nbsp;
	{/if}
	
	{if $nodata !=0}
    &nbsp; (No Data to display) &nbsp;
	{/if}

    {if $page < $totalPage && $page != $totalPage}
        <span class="pagination pointer" title="{$nextPage}"><strong>Next</strong></span> &nbsp; <span class="pagination pointer" title="{$totalPage}"><strong>Last</strong></span>
    {/if}
 -->
</div>
  
</div>
