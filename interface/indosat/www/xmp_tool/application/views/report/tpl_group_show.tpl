<div id="pageheading">
    <h2>Manage Group</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
            <div class="clearfix fl">
                <span><a id="btnOpenPanel" class="btnAdd"><span>Create New Group&nbsp;</span></a></span>&nbsp;&nbsp;
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

                <form name="group-form" id="group-form">
                    <div class="verticalContainer">
                        <p>
                            <label for="txt-name">Group Name *</label>
                            <input type="text" name="txt-name" id="txt-name" class="inputtext-1" value="" />
                            <span id="inf-name"></span>
                        </p>
                        <p>
                            <label for="txt-description">Description</label>
                            <textarea name="txt-description" id="txt-description" class="inputtext-1"></textarea>
                            <span id="inf-description"></span>
                        </p>
                    </div>
                    <div class="verticalContainer">
                        <p>
                            <label for="">Privileges</label>
                            {$check_menu}
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

        <div class="table-list-area">
            <div class="ajax-loader">Loading...</div>
            <table id="group-list-table" class="datagrid2" width="100%">
                <thead>
                    <tr>
                        <th width="25%">Group Name</th>
                        <th width="45%">Group Description</th>
                        <th width="15%">Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>

                <tbody></tbody>

                <tfoot>
                    <tr>
                        <td colspan="4">
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
    </div>
</div>
