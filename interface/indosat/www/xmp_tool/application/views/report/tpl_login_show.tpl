{if ($download != false)}
<div id="left" style="clear: both; float: left; width:200px; height: 500px; overflow:hidden;overflow-x:scroll;">
	<table class="datagrid2 dg2-left" width="200px">
  		<tr>
    		<th colspan="3">Content</th>
    		<th width="1px" style="display: none;"></th>
  		</tr>
  		<tr>
    		<th style="width: 30px;">Type</th>
    		<th style="width: 20px;">Code</th>
    		<th>Title</th>
    		<th width="1px" style="display: none;"></th>
  		</tr>
	    {if $download != false}
		    {foreach from=$download item=row name=o}
	          <tr {if ($smarty.foreach.o.iteration%2) == 0}class="alt"{/if}>
	          	  {if $row.type == 'total'}
	          	      <td colspan="3" style="text-align: center;"><b>{$row.type|upper}</b></td>
			  	  	  <td style="display: none;"></td>
	          	  {else}
	          	  	  {if $row.title|strlen > 15}
	          	  	  	  {assign var=title value=$row.title|substr:0:15|cat:'...'}
	          	  	  {else}
	          	  	      {assign var=title value=$row.title}
	          	  	  {/if}
	          	      <td style="text-align:left;">{$row.type}</td>
	          	      <td style="text-align:left;">{$row.code}</td>
	          	      <td style="text-align:left;" title="{$row.title}">{$title}</td>
			  	  	  <td style="display: none;"></td>
	          	  {/if}
			  </tr>
			{/foreach}
		{/if}
	</table>
</div>

<div id="right" style="float:left;width:756px;height:500px;overflow:auto;margin:0px;padding:0px;">
	<table class="datagrid2 dg2-right">
	  <tr>
	    <th colspan="3" class="grandTotal">Total</th>
	    {section name=foo loop=$columns+1 step=-1}
	    	{if $smarty.section.foo.index != 0}
  				<th colspan="3" class="{$smarty.section.foo.index}">{if $mode == 'daily'}{$smarty.section.foo.index}{else}{'2010-'|cat:$smarty.section.foo.index|strtotime|date_format:"%B"}{/if}</th>
  			{/if}
		{/section}
	  </tr>
	  <tr>
	    <th class="grandTotal" style="width: 40px;">SENT</th>
	    <th class="grandTotal" style="width: 40px;">DLVRD</th>
	    <th class="grandTotal" style="width: 40px;">Gross</th>

	    {section name=foo loop=$columns+1 step=-1}
	    	{if $smarty.section.foo.index != 0}
	  			<th class="{$smarty.section.foo.index}" style="width: 40px;">SENT</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">DLVRD</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">Gross</th>
		    {/if}
		{/section}
	  </tr>
	  {if $download != false}
	      {foreach from=$download item=row name=i}
	          <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
			    <td class="grandTotal alt2">{$row.totalSent|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalDelivered|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalRevenue|number_format:0:',':'.'}</td>

			    {section name=foo loop=$columns+1 step=-1}
	    			{if $smarty.section.foo.index != 0}
	    				<td class="{$smarty.section.foo.index}{if ($smarty.section.foo.iteration%2) == 0} alt2{/if}" style="{$row.$mode[$smarty.section.foo.index].color}">{if isset($row.$mode[$smarty.section.foo.index].sent)}{$row.$mode[$smarty.section.foo.index].sent|number_format:0:',':'.'}{else}0{/if}</td>
						<td class="{$smarty.section.foo.index}{if ($smarty.section.foo.iteration%2) == 0} alt2{/if}" style="{$row.$mode[$smarty.section.foo.index].color}">{if isset($row.$mode[$smarty.section.foo.index].delivered)}{$row.$mode[$smarty.section.foo.index].delivered|number_format:0:',':'.'}{else}0{/if}</td>
						<td class="{$smarty.section.foo.index}{if ($smarty.section.foo.iteration%2) == 0} alt2{/if}" style="{$row.$mode[$smarty.section.foo.index].color}">{if isset($row.$mode[$smarty.section.foo.index].revenue)}{$row.$mode[$smarty.section.foo.index].revenue|number_format:0:',':'.'}{else}0{/if}</td>
					{/if}
			    {/section}
			  </tr>
	      {/foreach}
	  {/if}
	</table>
</div>
<br/>
{/if}

<script type="text/javascript">tableSync();</script>
