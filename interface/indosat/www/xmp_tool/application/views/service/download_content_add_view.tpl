{include file="common/tpl_header.tpl"}

<div class="pagetitle">Insert New Content</div>
<div class="midlemidle">
    <div class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px; color: #51697D;">
        <form name="content-add-form" id="content-add-form" enctype="multipart/form-data" method="POST" action="download_content_add/ajaxAddNewContent">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%">
                        <label for="txt-sort">Sort </label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-sort" id="txt-sort" class="inputtext-1" value="" maxlength="4" />
                        &nbsp;<span id="inf-sort"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-contentcode">Content Code *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-contentcode" id="txt-contentcode" class="inputtext-1" value="" maxlength="10" />
                        &nbsp;<span id="inf-contentcode"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-title">Title *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-title" id="txt-title" class="inputtext-1" value="" maxlength="100" />
                        &nbsp;<span id="inf-title"></span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-image">Image </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-image" id="file-image" class="inputtext-1" value="" />
                        &nbsp;<span id="inf-image"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-price">Price *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-price" id="txt-price" class="inputtext-1" value="" />
                        &nbsp;<span id="inf-price"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-limit">Limit </label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-limit" id="txt-limit" class="inputtext-1" value="" maxlength="11" />
                        &nbsp;<span id="inf-limit"></span>
                    </td>
                </tr>                
                <tr>
                    <td width="30%"></td>
                    <td width="70%">
                        <input type="hidden" name="txt-service" id="txt-service" class="inputtext-1" value="{$idDownload}" maxlength="10" />
                    </td>
                </tr>
            </table>
            <br />
            <input type="submit" name="save-form" id="save-form" value="Save">
            <input type="reset" name="reset-form" id="reset-form" value="Reset">
        </form>
    </div>
</div>

{include file="common/tpl_footer.tpl"}