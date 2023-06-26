{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Master Data Operator</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Operator</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="operator-form" id="operator-form"> 
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-operator-name">Operator Name * </label>
							</td>
							<td>
								<input type="text" id="txt-operator-name" name="txt-operator-name" class="inputtext-1 w-200" maxlength="30" />
								&nbsp;<span id="inf-operator-name"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-operator-long-name">Operator Long Name * </label>
							</td>
							<td>
								<input type="text" id="txt-operator-long-name" name="txt-operator-long-name" class="inputtext-1 w-290" maxlength="255" />
								&nbsp;<span id="inf-operator-long-name"></span>
							</td>
						</tr>
					</table><br>
					<input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
					<input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
				</form>
			</div>
        </div>
    </div>
    <div class="midlemidle">
        <div class="boxheader reporttable">
            <table id="operator-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="38%">Operator Name</th>
                        <th width="50%">Operator Long Name</th>
                        <th width="12%">Action</th>
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
