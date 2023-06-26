{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage Controller</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
			<div class="boxheader">
                <div class="boxtoggle">Add Controller</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="controller-form" id="controller-form"> 
					<table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-menu-name">Menu Name *</label>
							</td>
							<td>
								<input type="text" name="txt-menu-name" id="txt-menu-name" class="inputtext-1" value="" maxlength="30" />
								&nbsp;<span id="inf-menu-name"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-parent">Parent</label>
							</td>
							<td>
								{$parent_list}
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-controller-link">Controller Link *</label>
							</td>
							<td>
								<input type="text" name="txt-controller-link" id="txt-controller-link" class="inputtext-1" value="" maxlength="30" />
								&nbsp;<span id="inf-controller-link"></span>
							</td>
						</tr>
						<tr id="sort">
							<td>
								<label for="txt-sort">Sort *</label>
							</td>
							<td>
								<input type="text" name="txt-sort" id="txt-sort" class="inputtext-1" value="" maxlength="11" />
								&nbsp;<span id="inf-sort"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-status">Status (become menu?)*</label>
							</td>
							<td>
								<select name="txt-status" id="txt-status" >
									{foreach from=$status item=status_item key=status_key}
										<option value="{$status_key}">{$status_item}</option>
									{/foreach}
								</select>
								<i>must relog to activated</i>
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
            <table id="controller-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
					<tr>
                        <th>Menu Name</th>
                        <th>Parent</th>
						<th>Controller Link</th>
						<th>Sort</th>
						<th>Status</th>
						<th width="120" class="last">Action</th>
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
