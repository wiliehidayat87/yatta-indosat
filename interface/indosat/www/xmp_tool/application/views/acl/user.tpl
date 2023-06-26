{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage User</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add User</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
                <form name="user-form" id="user-form"> 
					<table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%">
								<label for="txt-username">Username *</label>
							</td>
							<td>
								<input type="text" name="txt-username" id="txt-username" class="inputtext-1" value="" />
                                &nbsp;<span id="inf-username"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label for="txt-password">Password *</label>
							</td>
							<td>
								<input type="password" name="txt-password" id="txt-password" class="inputtext-1">
                                &nbsp;<span id="inf-password"></span>
							</td>
						</tr>
                        <tr>
                         	<td>
								<label for="txt-confirmpass">Conf. Password *</label>
							</td>
							<td>
								<input type="password" name="txt-confirmpass"id="txt-confirmpass" class="inputtext-1">
                                &nbsp;<span id="inf-confirmpass"></span>
							</td>
						</tr>
						<tr>
							<td>
								<label>Group</label>
							</td>
							<td>
								{$group}
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
            <table id="user-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="20%">Username</th>
                        <th width="15%">User Group</th>
                        <th width="15%" class="last">Action</th>
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
