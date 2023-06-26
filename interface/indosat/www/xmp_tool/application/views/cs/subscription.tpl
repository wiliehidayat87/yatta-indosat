{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Subscription</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
			<div class="boxheader">
                <div class="boxtoggle">User Subscription</div>
                <div class="search-area">
                    
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form id="subscription" method='post'>
					<table cellspacing="2">
						<tr>
							<td width="40%" style="text-align: right;">MSISDN :</td>
							<td>
								<input type='text' name='msisdn' id='msisdn'>
							</td>
						</tr>
						<tr>
							<td width="40%" style="text-align: right;">Operator:</td>
							<td>
								<select id="operator" name="operator" id="operator">
									<option value="">-- operator --</option>
									{foreach from=$operator item=operator_item key=operator_key}
										<option value="{$operator_item->name}">{$operator_item->long_name}</option>
									{/foreach}			
								</select>
							</td>
						</tr> 
						<tr>
							<td width="40%" style="text-align: right;">ADN:</td>
							<td>
								<select id="adn" name="adn">
									<option value="">-- ADN --</option>
									{foreach from=$adn item=adn_item key=adn_key}
										<option value="{$adn_item->adn}">{$adn_item->adn}</option>
									{/foreach}	
								</select>
							</td>
						</tr>  
						<tr>
							<td width="40%" style="text-align: right;">Service:</td>
							<td>
								<select id="service" name="service">
									<option value="">-- service --</option>
									{foreach from=$service item=service_item key=service_key} 
										<option value="{$service_item->name}">{$service_item->name}</option>
									{/foreach}	
								</select>
							</td>
						</tr>   
						<tr>
							<td>&nbsp;</td>
							<td><button type="button" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
    </div>
    <div class="midlemidle">
		<div id="btnCheck" align=left style='float:left; width:380px;'>
			<input style='font-size:10px;' type="button" name="CheckAll" value="Check All" >
			<input style='font-size:10px;' type="button" name="UnCheckAll" value="Uncheck All"">	
			<input style='font-size:10px;' type="button" name="inactiveChecked" id="inactiveChecked" value="Inactive Checked">		
		</div>
		<div class="clear"></div>
		 <div class="boxheader reporttable">
			<div id='userSubscriptionTable'></div>
			</div>
        <div class="pagination">
			<ul>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="viewlimit"><!--
            View limit : 
			<select name="pageLimit" id="pageLimit">
				{foreach from=$pageLimit item=item key=key}
					<option value="{$item}">{$item}</option>
				{/foreach}
			</select>-->
		</div>
	</div>
	

{include file="common/tpl_footer.tpl"}
