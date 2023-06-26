<div id="pageheading">
    <h2>Manage Menu</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
            <div class="clearfix fl">
                <span><a id="btnOpenPanel" class="btnAdd"><span>Create New Menu&nbsp;</span></a></span>&nbsp;&nbsp;
            </div>
            <div class="search-area">
                <form name="search-form" id="search-form">
                    <input type="text" name="search-field" id="search-field" class="search-field" />
                    <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                </form>
            </div>
            
            <div class="cb"></div>
            
            <div id="loadContainer" class="loadContainer closePanel">
                <div class="form-loader">Please wait...</div>

                <form name="menu-form" id="menu-form">
                    <div class="verticalContainer">
                        <p>
                            <label for="txt-menu-name">Menu Name *</label>
                            <input type="text" name="txt-menu-name" id="txt-menu-name" class="inputtext-1" value="" maxlength="30" />
                            <span id="inf-menu-name"></span>
                        </p>
                        <p>
                            <label for="txt-parent">Parent </label>
                            {$parent_list}
                            <span id="inf-parent"></span>
                        </p>
                        <p>
                            <label for="txt-menu-link">Link *</label>
                            <input type="text" name="txt-link" id="txt-link" class="inputtext-1" value="" maxlength="30" />
                            <span id="inf-link"></span>
                        </p>
                        <div id="sort">
                        <p><label for="txt-sort">Sort *</label>
                            <input type="text" name="txt-sort" id="txt-sort" class="inputtext-1" value="" maxlength="11" />
                            <span id="inf-sort"></span>
                        </p>
                        </div>
                        <p>
                            <label for="txt-status">Status</label>
                            <select name="txt-status" id="txt-status" >
                            {foreach from=$status item=status_item key=status_key}
                            <option value="{$status_key}">{$status_item}</option>
                            {/foreach}
                            </select>
                            <i>must relog to activated</i>
                        </p>
                    </div>
                    <div class="cb"></div>
                    
                    <p class="submit-area">
                        <input type="submit" name="save" id="save" class="button" value="Save" />
                        <input type="button" name="btnClosePanel" id="btnClosePanel" class="button" value="Cancel" />
                    </p>
                </form>
            </div>
        </div>

        <table id="menu-list-table" class="datagrid2" width="100%">
            <thead>
	      		<tr>
                            <th width="150">Menu Name</th>
                            <th width="100">Parent</th>
		            <th width="100">Link</th>
		            <th width="80">Sort</th>
		            <th width="80">Status</th>
		            <th width="120">Action</th>
		      	</tr>
            </thead>

            <tbody></tbody>
                
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div id="pagination" class="fl">
                            Page: <span id="paging"></span>
                        </div>
                        <div id="pagination-info" class="fr">
                            <span id="from" class="fwb">0</span> - <span id="to" class="fwb">0</span> from <span id="total" class="fwb">0</span>
                        </div>
                        <div class="cb"></div>
                    </td>
                </tr>
            </tfoot>
        </table>
