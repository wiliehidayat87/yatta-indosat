{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage method</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle" id="btnOpenPanel">Scan Method</div>
				<div class="search-area">
					<form name="search-form" id="search-form">
						<input type="text" name="search-field" id="search-field" class="search-field" />
						<input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
					</form>
				</div>
                <div class="clear"></div>
			</div>            
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="scan-method-group-form" id="scan-method-group-form">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr valign="top">
							<td width="10%">
								<label for="">Controller Link</label>
							</td>
							<td>
								{$controller_list}
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
							<td>
								<input type="submit" name="scan" id="scan" class="button" value="Scan" />
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
            <table id="method-group-list-table"  width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="20%">Group Name</th>
                        <th width="20%">Controller Link</th>
                        <th width="30%">Method</th>
                        <th width="15%">Status</th>
                        <th width="15%" class="last">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
		<div class="pagination">
			<ul>
				<div class="clear"></div>
				<li></li>
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
