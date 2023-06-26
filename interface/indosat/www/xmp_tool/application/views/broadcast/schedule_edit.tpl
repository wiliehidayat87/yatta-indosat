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
            <td>Operator</td>
            <td>
            <select name="operator">
                <option value='null'>-- operator --</option>
                {html_options values=$opr_ids output=$opr_names selected=$opr_id}
            </select>
            </td>
        </tr>
        <tr>
            <td>Shortcode</td>
            <td>
            <select name="adn">
                <option value='null'>-- adn --</option>
                {html_options values=$adn_ids output=$adn_names selected=$adn_id}
            </select>
            </td>
        </tr>
        <tr><td>Content Source</td><td><input type=text id=content_label name=content_label value='{$content_label}' maxlength=15></td></tr>
        <tr>
            <td>Content Select</td>
            <td>
            <select name="content_select">
                <option value='null'>-- content select --</option>
                {html_options values=$cSelect_ids output=$cSelect_names selected=$cSelect_id}
            </select>
            </td>
        </tr>
        <tr><td>Handler</td><td><input type=text name=handlerfile id=handlerfile value='{$handlerfile}' maxlength=255></td></tr>
        <tr><td>Push Date</td><td>
        <input type=text readonly onclick="displayDatePicker('push_time_date', false, 'ymd','-');" name=push_time_date value='{$push_time_date}' size=10  maxlength=10>
        <input type=button value=".." onclick="displayDatePicker('push_time_date', false, 'ymd','-');">
        Time : <input type=text name=push_time_hour value='{$push_time_hour}' size=8 maxlength=8></td></tr>
        <tr>
            <td>Status</td>
            <td>
            <select name="status">
                <option value='null'>-- status --</option>
                {html_options values=$sta_ids output=$sta_names selected=$sta_id}
            </select>
            </td>
        </tr>
        <tr>
            <td>Repeat</td>
            <td>
            <select name="recurring_type">
                <option value='null'>-- repeat --</option>
                {html_options values=$rep_ids output=$rep_names selected=$rep_id}
            </select>
            </td>
        </tr>
        <tr><td>Price</td><td><input type=text name=price value='{$price}' maxlength=5 size=5></td></tr>
        <tr><td>Notes</td><td><textarea name=notes rows=1 cols=60 wrap=physical onKeyUp="textLimitCounter(this.form.notes, this.form.msgCount,128);">{$notes}</textarea><br>
            <input name=msgCount class=clsinput size=3 maxlength=3 readonly value=128> char(s) left
        </td></tr>
        {if $action == 'editSave'}
        <tr><td colspan=2><u>Additional infos:</u></td></tr>    
        <tr><td>Last Content #id</td><td><i>{$last_content_id}</i></td></tr>
        <tr><td>Last modified</td><td><i>{$modified}</i></td></tr>
        <tr><td>Created</td><td><i>{$created}</i></td></tr>
        {/if}
    </table>
    <input type="submit" value="Save">
    {if $action == 'editSave'}
    <input type="hidden" name="id" value="{$schedule_id}">
    {/if}
</form>
</div>

{include file="common/tpl_footer.tpl"}