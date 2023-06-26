{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Master Module</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add Module</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
				</div>
				<div class="clear"></div>
			</div>
			<div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
				<form name="module-form" id="module-form"> 
                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-name">Module Name * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-name" name="txt-name" size="30" maxlength="50" />
                                &nbsp;<span id="inf-name"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txt-description">Description</label>
                            </td>
                            <td>
                                <textarea rows="2" cols="20" id="txt-description" name="txt-description" ></textarea>
                                &nbsp;<span id="inf-description"></span>
                            </td>
						</tr>
						<tr>
                            <td width="20%">
                                <label for="txt-handler">Handler * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-handler" name="txt-handler" size="30" maxlength="50" />
                                &nbsp;<span id="inf-handler"></span>
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
            <table id="module-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th width="25%">Module Name</th>
                        <th width="30%">Description</th>
                        <th width="25">Handler</th>
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
