{if $subject != false}
<i>Note: S: Sent, D: Delivered, F: Failed, U: Unknown, G: Gross</i><br/>

<div id="left" style="clear:both;float:left;height:500px;overflow:hidden;overflow-x:scroll;width:200px;">
	<table class="datagrid2 dg2-left" width="200px">
  		<tr>
    		<th style="height: 50px;">Subject</th>
    		<th width="1px" style="display: none;"></th>
  		</tr>
	    {if $subject != false}
		    {foreach from=$subject item=row name=o}
	          <tr {if ($smarty.foreach.o.iteration%2) == 0}class="alt"{/if}>
	          	  {if $row.service == 'total'}
	          	      <td style="text-align: center;"><b>{$row.service|upper}</b></td>
			  	  	  <td style="display: none;"></td>
	          	  {else}
	          	      <td style="text-align: left;cursor:pointer;" class="pointerBD"><span class="collapsed">{$row.service}</span></td>
			  	  	  <td style="display: none;"></td>
	          	  {/if}
			  </tr>
			{/foreach}
		{/if}
	</table>
</div>

<div id="right" style="float:left;height:500px;overflow:auto;width:756px;">
	<table class="datagrid2 dg2-right">
	  <tr>
	    <th colspan="5" class="grandTotal">Total</th>
	    <th colspan="5" class="average">Average</th>
	    <th colspan="5" class="monthEnd">MonthEnd</th>
	    {section name=foo loop=$days+1 step=-1}
	    	{if $smarty.section.foo.index != 0}
  				<th colspan="5" class="{$smarty.section.foo.index}">{$smarty.section.foo.index}</th>
  			{/if}
		{/section}
	  </tr>
	  <tr>
	    <th class="grandTotal" style="width: 40px;">S</th>
	    <th class="grandTotal" style="width: 40px;">D</th>
	    <th class="grandTotal" style="width: 40px;">F</th>
	    <th class="grandTotal" style="width: 40px;">U</th>
	    <th class="grandTotal" style="width: 40px;">G</th>

	    <th class="average" style="width: 40px;">S</th>
	    <th class="average" style="width: 40px;">D</th>
	    <th class="average" style="width: 40px;">F</th>
	    <th class="average" style="width: 40px;">U</th>
	    <th class="average" style="width: 40px;">G</th>

	    <th class="monthEnd" style="width: 40px;">S</th>
	    <th class="monthEnd" style="width: 40px;">D</th>
	    <th class="monthEnd" style="width: 40px;">F</th>
	    <th class="monthEnd" style="width: 40px;">U</th>
	    <th class="monthEnd" style="width: 40px;">G</th>

	    {section name=foo loop=$days+1 step=-1}
	    	{if $smarty.section.foo.index != 0}
	  			<th class="{$smarty.section.foo.index}" style="width: 40px;">S</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">D</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">F</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">U</th>
			    <th class="{$smarty.section.foo.index}" style="width: 40px;">G</th>
		    {/if}
		{/section}
	  </tr>
	  {if $subject != false}
	      {foreach from=$subject item=row name=i}
	          <tr {if ($smarty.foreach.i.iteration%2) == 0}class="alt"{/if}>
			    <td class="grandTotal alt2">{$row.totalSent|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalDelivered|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalFailed|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalUnknown|number_format:0:',':'.'}</td>
			    <td class="grandTotal alt2">{$row.totalRevenue|number_format:0:',':'.'}</td>

			    <td class="average">{$row.averageSent|number_format:0:',':'.'}</td>
			    <td class="average">{$row.averageDelivered|number_format:0:',':'.'}</td>
			    <td class="average">{$row.averageFailed|number_format:0:',':'.'}</td>
			    <td class="average">{$row.averageUnknown|number_format:0:',':'.'}</td>
			    <td class="average">{$row.averageRevenue|number_format:0:',':'.'}</td>

			    <td class="monthEnd alt2">{$row.monthEndSent|number_format:0:',':'.'}</td>
			    <td class="monthEnd alt2">{$row.monthEndDelivered|number_format:0:',':'.'}</td>
			    <td class="monthEnd alt2">{$row.monthEndFailed|number_format:0:',':'.'}</td>
			    <td class="monthEnd alt2">{$row.monthEndUnknown|number_format:0:',':'.'}</td>
			    <td class="monthEnd alt2">{$row.monthEndRevenue|number_format:0:',':'.'}</td>

			    {section name=foo loop=$days+1 step=-1}
	    			{if $smarty.section.foo.index != 0}
                        <td class="{$smarty.section.foo.index}" style="{$row.daily[$smarty.section.foo.index].color}">{if isset($row.daily[$smarty.section.foo.index].sent)}{$row.daily[$smarty.section.foo.index].sent|number_format:0:',':'.'}{else}0{/if}</td>
                        <td class="{$smarty.section.foo.index}" style="{$row.daily[$smarty.section.foo.index].color}">{if isset($row.daily[$smarty.section.foo.index].delivered)}{$row.daily[$smarty.section.foo.index].delivered|number_format:0:',':'.'}{else}0{/if}</td>
                        <td class="{$smarty.section.foo.index}" style="{$row.daily[$smarty.section.foo.index].color}">{if isset($row.daily[$smarty.section.foo.index].failed)}{$row.daily[$smarty.section.foo.index].failed|number_format:0:',':'.'}{else}0{/if}</td>
                        <td class="{$smarty.section.foo.index}" style="{$row.daily[$smarty.section.foo.index].color}">{if isset($row.daily[$smarty.section.foo.index].unknown)}{$row.daily[$smarty.section.foo.index].unknown|number_format:0:',':'.'}{else}0{/if}</td>
                        <td class="{$smarty.section.foo.index}" style="{$row.daily[$smarty.section.foo.index].color}">{if isset($row.daily[$smarty.section.foo.index].revenue)}{$row.daily[$smarty.section.foo.index].revenue|number_format:0:',':'.'}{else}0{/if}</td>
					{/if}
			    {/section}
			  </tr>
	      {/foreach}
	  {/if}
	</table>
</div>
<br/>
<i>Note: S: Sent, D: Delivered, F: Failed, U: Unknown, G: Gross</i>
{/if}

<script type="text/javascript">tableSync();</script>
