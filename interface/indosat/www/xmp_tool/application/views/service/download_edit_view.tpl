{include file="common/tpl_header.tpl"}

<div class="pagetitle">Edit Service</div>
<div class="midlemidle">
    <div class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px; color: #51697D;">
        <form name="download-add-form" id="download-add-form" enctype="multipart/form-data" method="POST" action="download_edit/ajaxUpdateService">
            <table width="50%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="30%">
                        <label for="txt-name">Name *</label>
                    </td>
                    <td width="70%">
                        <select name="name-list" id="name-list">
                            <option value="{$nameID}">{$nameService}_{$nameServiceADN}</option>
                            {foreach from=$nameList item=name_item key=name_key}
                                {if $name_item.id!== $nameID}
                                <option value="{$name_item.id}">{$name_item.name}_{$name_item.adn}</option>
                                {/if}
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-title">Title *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-title" id="txt-title" class="inputtext-1" value="{$title}" maxlength="128" />
                        <input type="hidden" name="hidden-title" id="hidden-title" class="inputtext-1" value="{$title}" maxlength="128" />
                        &nbsp;<span id="inf-title"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-disclaimer">Disclaimer *</label>
                    </td>
                    <td width="70%">
                        <textarea name="txt-disclaimer" id="txt-disclaimer" class="inputtext-1" />{$disclaimer}</textarea>
                        &nbsp;<span id="inf-disclaimer"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for = "txt-description">Description *</label>
                    </td>
                    <td width="70%">
                        <input type="text" name="txt-description" id="txt-description" class="inputtext-1" value="{$description}" />
                        &nbsp;<span id="inf-description"></span>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <label for="txt-type">Type *</label>
                    </td>
                    <td width="70%">
                        <select name="type-list" id="type-list">
                            <option value="{$type}">{$type}</option>
                            {foreach from=$typeList item=type_item key=type_key}
                                {if $type_item!== $type}
                                <option value="{$type_item}">{$type_item}</option>
                                {/if}
                            {/foreach}
                        </select>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%">
                        <label for = "file-header-1">Header Image 1 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-header-1" id="file-header-1" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-header-1" id="hidden-header-1" class="inputtext-1" value="{$hHeader1}" />
                        &nbsp;<span id="inf-header-1"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">176 x 220 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-header-2">Header Image 2 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-header-2" id="file-header-2" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-header-2" id="hidden-header-2" class="inputtext-1" value="{$hHeader2}" />
                        &nbsp;<span id="inf-header-2"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">240 x 320 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-header-3">Header Image 3 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-header-3" id="file-header-3" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-header-3" id="hidden-header-3" class="inputtext-1" value="{$hHeader3}" />
                        &nbsp;<span id="inf-header-3"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">480 x 320 px</span>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%">
                        <label for = "file-footer-1">Footer Image 1 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-footer-1" id="file-footer-1" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-footer-1" id="hidden-footer-1" class="inputtext-1" value="{$hFooter1}" />
                        &nbsp;<span id="inf-footer-1"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">176 x 220 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-footer-2">Footer Image 2 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-footer-2" id="file-footer-2" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-footer-2" id="hidden-footer-2" class="inputtext-1" value="{$hFooter2}" />
                        &nbsp;<span id="inf-footer-2"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">240 x 320 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-footer-3">Footer Image 3 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-footer-3" id="file-footer-3" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-footer-3" id="hidden-footer-3" class="inputtext-1" value="{$hFooter3}" />
                        &nbsp;<span id="inf-footer-3"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">480 x 320 px</span>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%">
                        <label for = "file-promo-1">Promo Image 1 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-promo-1" id="file-promo-1" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-promo-1" id="hidden-promo-1" class="inputtext-1" value="{$hPromo1}" />
                        &nbsp;<span id="inf-promo-1"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">176 x 220 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-promo-2">Promo Image 2 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-promo-2" id="file-promo-2" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-promo-2" id="hidden-promo-2" class="inputtext-1" value="{$hPromo2}" />
                        &nbsp;<span id="inf-promo-2"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">240 x 320 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-promo-3">Promo Image 3 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-promo-3" id="file-promo-3" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-promo-3" id="hidden-promo-3" class="inputtext-1" value="{$hPromo3}" />
                        &nbsp;<span id="inf-promo-3"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">480 x 320 px</span>
                    </td>
                </tr>
            </table>
            <hr>
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="25%">
                        <label for = "file-background-1">Background Image 1 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-background-1" id="file-background-1" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-background-1" id="hidden-background-1" class="inputtext-1" value="{$hBg1}" />
                        &nbsp;<span id="inf-background-1"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">176 x 220 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-background-2">Background Image 2 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-background-2" id="file-background-2" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-background-2" id="hidden-background-2" class="inputtext-1" value="{$hBg2}" />
                        &nbsp;<span id="inf-background-2"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">240 x 320 px</span>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label for = "file-background-3">Background Image 3 </label>
                    </td>
                    <td width="60%">
                        <input type="file" name="file-background-3" id="file-background-3" class="inputtext-1" value="" />
                        <input type="hidden" name="hidden-background-3" id="hidden-background-3" class="inputtext-1" value="{$hBg3}" />
                        &nbsp;<span id="inf-background-3"></span>
                    </td>
                    <td width="15%">
                        <span class="info-img">480 x 320 px</span>
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