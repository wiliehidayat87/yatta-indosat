{if $subject != false}
<div id="left" style="clear: both; float: left; width:100px; height: 500px; overflow:hidden;overflow-x:auto;">
	<table class="datagrid2 dg2-left" width="100px">
  		<tr>
    		<th>Service</th>
    		<th width="1px" style="display: none;"></th>
  		</tr>
	    {if $subject != false}
		    {foreach from=$subject item=row name=o}
	          <tr {if ($smarty.foreach.o.iteration%2) == 0}class="alt"{/if}>
	          	  {if $row.service == 'total'}
	          	      <td style="text-align: center;height: {math equation="x*y" x=25 y=$row.subject|@count}px"><b>{$row.service|upper}</b></td>
			  	  	  <td style="display: none;"></td>
	          	  {else}
	          	      <td title="{$row.info}" style="text-align:left;height: {math equation="x*y" x=25 y=$row.subject|@count}px">{$row.service}</td>
			  	  	  <td style="display: none;"></td>
	          	  {/if}
			  </tr>
			{/foreach}
		{/if}
	</table>
</div>

<div id="right" style="float:left;width:856px;height:500px;overflow:auto;margin:0px;padding:0px;">
	<table class="datagrid2 dg2-right">
	  <tr>
	  	<th class="subject" style="width: 80px;">Subject</th>
	    <th class="grandTotal" style="width: 40px;">Total</th>
	    {section name=foo loop=$days+1 step=-1}
	    	{if $smarty.section.foo.index != 0}
  				<th class="{$smarty.section.foo.index}" style="width: 40px;">{$smarty.section.foo.index}</th>
  			{/if}
		{/section}
	  </tr>
	  {if $subject != false}
	      {foreach from=$subject item=row name=i}
	      	{foreach from=$row.subject item=item}
	          <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
			  <td class="subject alt2" style="font-weight: bold;text-align: left;">{$item.name|upper}</td>
			  <td class="grandTotal">{$item.total|number_format:0:',':'.'}</td>

			  {section name=foo loop=$days+1 step=-1}
	    		  {if $smarty.section.foo.index != 0}
					  <td class="{$smarty.section.foo.index}{if ($smarty.section.foo.iteration%2) != 0} alt2{/if}" style="padding:2px;{$item.daily[$smarty.section.foo.index].color}">{if isset($item.daily[$smarty.section.foo.index])}{$item.daily[$smarty.section.foo.index].total|number_format:0:',':'.'}{else}0{/if}</td>
				  {/if}
			  {/section}
			  </tr>
			{/foreach}
	      {/foreach}
	  {/if}
	</table>
</div>

<script type="text/javascript">tableSync();</script>
{/if}
