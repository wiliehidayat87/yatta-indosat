{include file="common/tpl_header.tpl"}

<link href="{$base_url}public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="{$base_url}public/js/dp.js"></script>


<script language=JavaScript>
<!-- 
function IsFormChecked(_frm){
    var ceked = false;
    for(var i=0; i<_frm.length; i++){
    if (_frm.elements[i].type=="checkbox"){
        if (_frm.elements[i].checked){
            ceked = true; break; }
        }
    }
    return ceked;
}

function SelectCheck(bool){
    var _frm = document.forms.fpagelist;
    for(var i=0; i<_frm.length; i++){
        if (_frm.elements[i].type=="checkbox")
            _frm.elements[i].checked = bool; 
    }
}

function doDeleteSingle(keys){
    var _frm = document.forms.fpagelist;
    if (confirm('Are you sure want to delete this item ?')){
        //document.location.href = url;
        _frm.f_key.value=keys;
        _frm.dopost.value = 'sd';
        _frm.submit();
    }
}

function doDeleteChecked(){
    var _frm = document.forms.fpagelist;
    if (!IsFormChecked(_frm)){
        alert("There's no item selected.");
        return;
    }
    if (confirm('Are you sure want to delete selected item(s) ?')){
        _frm.dopost.value = 'md';
        _frm.submit();
    }

}
// --> 
</script>

<div class="pagetitle">{$pageTitle}</div>
<div class="midlemidle">

 <table width=100% cellspacing=0 cellpadding=3>
    <form name='srcform' method='post'>
        <tr><td colspan=2 class='textBig bottomline'>Text Content List</td></tr>
        <tr><td bgcolor=whitesmoke class='bottomline'>
            Service : 
            <select name="service">
                <option value='null'>-- service --</option>
                {html_options values=$svc_ids output=$svc_names selected=$svc_id}
            </select>
            Content : <input type=text name=content value='{$content}'>
            Publish Date: 
            <input type=text readonly onclick="displayDatePicker('push_date', false, 'ymd','-');" name=push_date value='' size=10>
            <input type=button value=".." onclick="displayDatePicker('push_date', false, 'ymd','-');">
            <input type=submit class=clsbutton value='Search'>
            </td>
            <td bgcolor=whitesmoke class='bottomline' align=right><a href='{$base_url}broadcast/content/insert' class=urlmaroon><b>NEW</b></a> | <a href='{$base_url}broadcast/content/import' class=urlmaroon><b>IMPORT</b></a> | <a class=urlgray><b>EXPORT</b></a> &nbsp;&nbsp;</td>
        </tr>
    </form>    
</table>

<table cellpadding=0 cellspacing=0><form><tr><td><input type=Hidden name=pg value=1>Show <input type=Text class=clsinput maxlength=2 name=rec size=2 value="{$rec}"> Records <input type=submit class=clsbutton value=Show></td></tr></form></table>

{$page_navigation}

<form name=fpagelist method=post action="{$base_url}broadcast/content/delete">
<input type=Hidden name=dopost value="">
<table border=0 cellpadding=3 cellspacing=0 width=100% >
    <tr>
        <td bgColor='silver' width=70 >Service</td>
        <td bgColor='silver' width=70 >Label</td>
        <td bgColor='silver'  >Content</td>
        <td bgColor='silver' width=120 >Date Publish</td>
        <td bgColor='silver' width=100 >Notes</td>
        <td bgColor='silver' align=right width=130>&nbsp;<a href="javascript:doDeleteChecked();"><b>Delete Sel</b></a>&nbsp;<a title="Clear All Check" href="javascript:SelectCheck(false);">[&nbsp;]</a>&nbsp;<a title="Check All" href="javascript:SelectCheck(true);">[X]</a></td>
    </tr>
    {foreach from=$contents key=conId item=i}
    <tr>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70><b>{$i.service}</b></td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70>{$i.content_label}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' >{$i.content}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=120>{$i.datepublish}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>&nbsp;{$i.notes}</td>
        <td align=right style='border-bottom:solid 1px gainsboro;font-size:7pt'> <a href="{$base_url}broadcast/content/edit?id={$i.id}"><b>edit</b></a> <input type=checkbox name=cSel[] value="{$i.id}"></td>
    </tr>
    {/foreach}
</table>
</form>

{$page_navigation}

</div>

{include file="common/tpl_footer.tpl"}
