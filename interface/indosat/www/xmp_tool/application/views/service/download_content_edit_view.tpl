{include file="common/tpl_header.tpl"}

<div class="pagetitle">Insert New Content</div>
<div class="midlemidle">
    <div class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px; color: #51697D;">
        <form name="content-add-form" id="content-add-form" enctype="multipart/form-data" method="POST" action="download_content_edit/ajaxUpdateContent">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%">
                        <label for="txt-sort">Sort </label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-sort" id="txt-sort" class="inputtext-1" value="{$sort}" maxlength="4" />
                        <input type="hidden" name="hidden-sort" id="hidden-sort" class="inputtext-1" value="{$sort}" maxlength="4" />
                        &nbsp;<span id="inf-sort"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-contentcode">Content Code *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-contentcode" id="txt-contentcode" class="inputtext-1" value="{$contentCode}" maxlength="10" />
                        <input type="hidden" name="hidden-contentcode" id="hidden-contentcode" class="inputtext-1" value="{$contentCode}" maxlength="10" />
                        &nbsp;<span id="inf-contentcode"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-title">Title *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-title" id="txt-title" class="inputtext-1" value="{$title}" maxlength="100" />
                        <input type="hidden" name="hidden-title" id="hidden-title" class="inputtext-1" value="{$title}" maxlength="100" />
                        &nbsp;<span id="inf-title"></span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-image">Image </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-image" id="file-image" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-image" id="hidden-image" class="inputtext-1" value="{$hImage}" />
                        &nbsp;<span id="inf-image"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-price">Price *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-price" id="txt-price" class="inputtext-1" value="{$price}" />
                        <input type="hidden" name="hidden-price" id="hidden-price" class="inputtext-1" value="{$price}" />
                        &nbsp;<span id="inf-price"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-limit">Limit </label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-limit" id="txt-limit" class="inputtext-1" value="{$limit}" maxlength="11" />
                        <input type="hidden" name="hidden-limit" id="hidden-limit" class="inputtext-1" value="{$limit}" maxlength="11" />
                        &nbsp;<span id="inf-limit"></span>
                    </td>
                </tr>                
                <tr>
                    <td width="30%"></td>
                    <td width="70%">
                        <input type="hidden" name="hidden-service" id="hidden-service" class="inputtext-1" value="{$serviceID}" maxlength="10" />
                    </td>
                </tr>
            </table>
            <br />
            <input type="hidden" name="id" id="id" value="{$id}">
            <input type="submit" name="save-form" id="save-form" value="Save">
            <input type="reset" name="reset-form" id="reset-form" value="Reset">
        </form>
    </div>
</div>

{include file="common/tpl_footer.tpl"}