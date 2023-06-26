{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Master Data ADN</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add ADN</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="adn-form" id="adn-form"> 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-adn-name">ADN * </label>
							</td>
							<td>
								<input type="txt" id="txt-adn-name" name="txt-adn-name" maxlength="10" />
								&nbsp;<span id="inf-adn-name"></span>
							</td>	
						</tr>
						<tr>
							<td>
								<label for="txt-description">Description</label>
							</td>
							<td>
								<textarea id="txt-description" name="txt-description"></textarea>
								&nbsp;<span id="inf-description"></span>
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
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
            <table id="adn-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th width="18%">ADN</th>
                        <th width="50%">Description</th>
                        <th width="20%">Date Created</th>
                        <th width="12" class="last">Action</th>
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
