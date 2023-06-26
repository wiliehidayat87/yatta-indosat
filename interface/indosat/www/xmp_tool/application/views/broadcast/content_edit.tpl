{include file="common/tpl_header.tpl"}

<link href="{$base_url}public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="{$base_url}public/js/dp.js"></script>

<div class="pagetitle">{$pagetitle}</div>
<div class="midlemidle">
<form action="./{$action}" method="post">

<table>
    <tr>
        <td>Service</td>
        <td>
            <select name="service">
                <option value='null'>-- service --</option>
                {html_options values=$svc_ids output=$svc_names selected=$svc_id}
            </select>
        </td>
    </tr>
    <tr>
        <td>Content</td>
        <td>
            <textarea name=content rows=2 cols=60 wrap=physical onKeyDown="javascript:textLimitCounter(document.forms.fcontent.content, document.forms.fcontent.msgCount,250);" onKeyUp="javascript:textLimitCounter(document.forms.fcontent.content, document.forms.fcontent.msgCount,250);">{$content}</textarea><br>
            <input name=msgCount class=clsinput size=3 maxlength=3 readonly value=250> char(s) left
        </td>
    </tr>
    <tr>
        <td>Content Label</td>
        <td><input type=text name=content_label value='{$content_label}' maxlength=15></td>
    </tr>
    <tr>
        <td>Author</td>
        <td><input type=text name=author value='{$author}' maxlength=25></td>
    </tr>
    <tr>
        <td>Publish Date</td>
        <td>
            <input type=text readonly onclick="displayDatePicker('datepublish_date', false, 'ymd','-');" name=datepublish_date value='{$datepublish_date}' size=10 maxlength=10>
            <input type=button value=".." onclick="displayDatePicker('datepublish_date', false, 'ymd','-');">
            Time : <input type=text name=datepublish_hour value='{$datepublish_hour}' size=8 maxlength=8></td></tr>
        </td>
    </tr>
    <tr>
        <td>Notes</td>
        <td><textarea name=notes rows=1 cols=60 wrap=physical onKeyDown="textLimitCounter(document.forms.fcontent.notes, document.forms.fcontent.msgCount2,250);" onKeyUp="textLimitCounter(document.forms.fcontent.notes, document.forms.fcontent.msgCount2,250);">{$notes}</textarea><br>
        <input name=msgCount2 class=clsinput size=3 maxlength=3 readonly value=250> char(s) left
        </td>
    </tr>

    {if $action == 'editSave'}
    <tr><td colspan=2><u>Additional infos:</u></td></tr>    
    <tr><td>Last publish</td><td><i>{$lastused}</i> - will be important/used for random content</td></tr>
    <tr><td>Last modified</td><td><i>{$modified}</i></td></tr>
    <tr><td>Created</td><td><i>{$created}</i></td></tr>
    {/if}
</table>
    <input type="submit" value="Save">
    {if $action == 'editSave'}
    <input type="hidden" name="id" value="{$content_id}">
    {/if}
    
</form>
</div>

{include file="common/tpl_footer.tpl"}