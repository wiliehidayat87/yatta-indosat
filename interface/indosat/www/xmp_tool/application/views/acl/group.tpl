{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage group</div>
    <div class="middletop">
		<div class="roundedbox bluebox">
            <div class="boxheader">
				<div class="boxtoggle" id="btnOpenPanel">Add Group</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
                <div class="clear"></div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
                <form name="group-form" id="group-form"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr valign="top">
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <label for="txt-group-name">Group Name *</label>
                                        </td>
                                        <td>
                                            <input type="text" name="txt-group-name" id="txt-group-name" class="inputtext-1" value="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="txt-group-description">Description</label>
                                        </td>
                                        <td>
                                            <textarea name="txt-group-description" id="txt-group-description" class="inputtext-1"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr valign="top">
                                        <td>
                                            <label for="">Privileges</label>
                                        </td>
                                        <td align="left">
                                            {$check_menu}
                                        </td>
                                    </tr>
                               </table>
                            </td>
                        <tr>
                    </table>
                    <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
                    <input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
                </form>
            </div>
        </div>
    </div>
    <div class="midlemidle">
		<div class="boxheader reporttable">
            <table id="group-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="25%">Group Name</th>
                        <th width="40%">Group Description</th>
                        <th width="15%">Status</th>
                        <th width="20%" class="last">Action</th>
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
