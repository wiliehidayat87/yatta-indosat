{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Master Data Charging</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Charging</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="charging-form" id="charging-form"> 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr valign="top">
							<td width="50%">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="30%">
											<label for="txt-operator">Operator *</label>
										</td>
										<td width="70%">
											{$operator}
											&nbsp;<span id="inf-operator"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-adn">ADN *</label>
										</td>
										<td>
											{$adn}
											&nbsp;<span id="inf-adn"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-charging-id">Charging ID *</label>
										</td>
										<td>
											<input type="text" name="txt-charging-id" id="txt-charging-id" class="inputtext-1" value="" maxlength="128" />
											&nbsp;<span id="inf-charging-id"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-gross">Gross *</label>
										</td>
										<td>
											<input type="text" name="txt-gross" id="txt-gross" class="inputtext-1" value="" maxlength="12" />
											&nbsp;<span id="inf-gross"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-netto">Netto *</label>
										</td>
										<td>
											<input type="text" name="txt-netto" id="txt-netto" class="inputtext-1" value="" maxlength="12" />
											&nbsp;<span id="inf-netto"></span>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="30%">
											<label for="txt-username">Username *</label>
										</td>
										<td width="70%">
											<input type="text" name="txt-username" id="txt-username" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-username"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-password">Password *</label>
										</td>
										<td>
											<input type="password" name="txt-password" id="txt-password" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-password"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-sender-type">Sender Type *</label>
										</td>
										<td>
											<input type="text" name="txt-sender-type" id="txt-sender-type" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-sender-type"></span>
										</td>
									</tr>
									<tr>
										<td>
											<label for="txt-message-type">Message Type *</label>
										</td>
										<td>
											<input type="text" name="txt-message-type" id="txt-message-type" class="inputtext-1" value="" maxlength="20" />
											&nbsp;<span id="inf-message-type"></span>
										</td>
									</tr>
								</table>
							</td>
						<tr>
					</table><br>
					<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
					<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
				</form>
			</div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="charging-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="10%">Operator</th>
                        <th width="10%">ADN</th>
                        <th width="15%">Charging ID</th>
                        <th width="10%">Gross</th>
                        <th width="10%">Netto</th>
                        <th width="15%">Sender Type</th>
                        <th width="15%">Message Type</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="viewlimit">
            View limit : 
			<select name="pageLimit" id="pageLimit">
				{foreach from=$pageLimit item=item key=key}
					<option value="{$item}">{$item}</option>
				{/foreach}
			</select>
		</div>
	</div>

{include file="common/tpl_footer.tpl"}
