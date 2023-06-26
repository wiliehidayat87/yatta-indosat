{include file="common/tpl_header.tpl"}
<script type="text/javascript">var gbl_d_id = '{$idDownload}';</script>
<div class="pagetitle">Download Content</div>
<div class="middletop">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">Add New Content</div>
            <div class="search-area">
                <form name="search-form" id="search-form">
                    <input type="text" name="search-field" id="search-field" class="search-field" />
                    <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                </form>
            </div>
            <div class="clear"></div>
        </div>       
    </div>
</div>
<div class="midlemidle">
    <div class="boxheader reporttable">
        <table id="content-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th width="10%">Sort</th>
                    <th width="15%">Content Code</th>
                    <th width="15%">Title</th>                    
                    <th width="20%">Image</th>                    
                    <th width="10%">Price</th>                    
                    <th width="10%">Limit</th>                    
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