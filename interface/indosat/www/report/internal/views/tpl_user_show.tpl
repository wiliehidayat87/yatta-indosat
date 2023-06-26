<div id="pageheading">
    <h2>Manage User</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
            <div class="clearfix fl">
                <span><a id="btnOpenPanel" class="btnAdd"><span>Create New User&nbsp;</span></a></span>&nbsp;&nbsp;                
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

                <form name="user-form" id="user-form">
                    <div class="verticalContainer">
                        <p><label for="txt-username">Username *</label>
                           <input type="text" name="txt-username" id="txt-username" class="inputtext-1" value="" />
                            <span id="inf-username"></span>
                        </p>
                        <p><label for="txt-password">Password *</label>
                            <input type="password" name="txt-password" id="txt-password" class="inputtext-1">
                            <span id="inf-password"></span>
                        </p>
                        <p><label for="txt-confirmpass">Conf. Password *</label>
                            <input type="password" name="txt-confirmpass"id="txt-confirmpass" class="inputtext-1">
                            <span id="inf-confirmpass"></span>
                        </p>
                        <p><label>Group</label>
                            {$group}
                            <span id="inf-group"></span>
                        </p>
                    </div>
                    
                    <div class="cb"></div>
                    
                    <p class="submit-area">
                        <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;&nbsp;
                        <input type="button" name="btnClosePanel" id="btnClosePanel" class="" value="Cancel" />
                    </p>
                </form>
            </div>
        </div>
        <div class="table-list-area">
            <div class="ajax-loader">Loading...</div>
                <table id="user-list-table" class="datagrid2" width="100%">
                    <thead>
	      		<tr>
                            <th width="35%">Username</th>
                            <th width="35%">User Group</th>
                            <th width="30%">Action</th>
		      	</tr>
                    </thead>

                    <tbody></tbody>
                
                    <tfoot>
                        <tr>
                            <td colspan="5">
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
        </div>
