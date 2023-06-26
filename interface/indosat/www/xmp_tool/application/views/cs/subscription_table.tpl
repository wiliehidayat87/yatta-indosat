{if $searchData!='' && count($searchData) > 0}
    {$nodata=0}
    
<div id="chartTable" align=center>
	<form name='table'>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th>No</th>
			<th>MSISDN</th>
			<th>SERVICE</th>
			<th>ADN</th>
			<th>OPERATOR</th>
			<th>CHANNEL SUBSCRIBE</th>
			<th>CHANNEL UNSUBSCRIBE</th>
			<th>SUBSCRIBE FROM</th>
			<th>SUBSCRIBE UNTIL</th>
			<th>ACTIVE</th>
			<th class="last">ACTION</th>
		</tr>
	
		{foreach from=$searchData item=sd_item key=sd_key}
		{$numbering=$numbering+1}
			{if $numbering mod 2==0}
				<tr>
			{else}
                <tr class="odd">
			{/if}
			
			<td valign='top'>
				<input type="checkbox" name="choices" class='choices-{$numbering}' id=choices value='{$sd_item->id}'/>
			</td>
			
			<td valign='top'>{$numbering}</td>
			<td valign='top'>{$sd_item->msisdn}</td>
			<td valign='top'>{$sd_item->service}</td>
			<td valign='top'>{$sd_item->adn}</td>
			<td valign='top'>{$sd_item->operator}</td>
			<td valign='top'>{$sd_item->channel_subscribe}</td>
			<td valign='top'>{$sd_item->channel_unsubscribe}</td>
			<td valign='top'>{$sd_item->subscribed_from}</td>
			<td valign='top'>{$sd_item->subscribed_until}</td>
			<td valign='top'>
				<span style="color:red;">{$statusConv[$sd_item->active]}</span>
			</td>
			
			<td class="last" align='center'>
				{if $sd_item->active==1}
					<input style='font-size:10px;' type="button" name="inactive" id="inactive" value="Inactive" onClick="inactiveBut({$sd_item->id})">
				{/if}
			</td>
		</tr>				
		{/foreach}
	</table>		
	</form>
	{else}  
		{$nodata=1}
	{/if}
</div>
