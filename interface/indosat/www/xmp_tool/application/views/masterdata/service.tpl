{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Master Service</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Service</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="service-form" id="service-form"> 
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-service-name">Service Name * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-service-name" name="txt-service-name" size="30" maxlength="50" />
                                &nbsp;<span id="inf-service-name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txt-name">ADN  </label>
                            </td>
                            <td>
                                <select name="txt-adn" id="txt-adn" >
									{foreach from=$adn item=adn_item key=adn_key}
										<option value="{$adn_item}">{$adn_item}</option>
									{/foreach}
								</select>
                                &nbsp;<span id="inf-adn"></span>
                            </td>
						</tr>
                        <tr>
                            <td>
                                <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;&nbsp;
								<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="service-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th width="10%">Service Name</th>
                        <th width="10%">ADN</th>
                        <th width="40%">Description</th>
                        <th width="20%">Date Created</th>
                        <th width="20">Action</th>
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
