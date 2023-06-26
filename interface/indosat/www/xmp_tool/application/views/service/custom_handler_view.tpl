{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage Custom Handler</div>
    <div class="middletop">
        <div class="roundedbox bluebox">
            <div class="boxheader">
                <div class="boxtoggle">Add New Handler</div>
                <div class="search-area">
                    <form name="search-form" id="search-form">
                        <input type="text" name="search-field" id="search-field" class="search-field" />
                        <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                    </form>
                </div>
            </div>
            <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form name="custom-handler-form" id="custom-handler-form">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr valign="top">
                        <td width="50%">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="30%">
                                        <label for="txt-pattern">Pattern *</label>
                                    </td>
                                    <td width="70%">
                                        <input type="text" name="txt-pattern" id="txt-pattern" class="inputtext-1" value="" maxlength="12" />
                                        &nbsp;<span id="inf-pattern"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <label for="txt-service">Service *</label>
                                    </td>
                                    <td width="70%">
                                        {$service}
                                        &nbsp;<span id="inf-service"></span>
                                    </td>
                                </tr>
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
                                    <td width="30%">
                                        <label for="txt-handler">Handler *</label>
                                    </td>
                                    <td width="70%">
                                        <input type="text" name="txt-handler" id="txt-handler" class="inputtext-1" value="" maxlength="128" />
                                        &nbsp;<span id="inf-handler"></span>
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
            <table id="custom_handler-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="20%">Pattern</th>
                        <th width="20%">Service Name</th>
                        <th width="15%">Operator</th>
                        <th width="15%">Handler</th>
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