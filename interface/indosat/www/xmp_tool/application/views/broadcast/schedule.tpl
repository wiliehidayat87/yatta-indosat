{include file="common/tpl_header.tpl"}

<link href="{$base_url}public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="{$base_url}public/js/dp.js"></script>

<script language=JavaScript>
<!-- 
function doSelected() {
    var _frm = document.forms.fpagelist;
    var label = '';
    var _selc = document.forms.fmenu.doselected;

    if (!IsFormChecked(_frm)) {
        alert("There's no item selected.");
        return;
    }

    if (_selc.selectedIndex>0) {
        label = _selc.options[_selc.selectedIndex].text;
        _frm.dopost.value = _selc.options[_selc.selectedIndex].value;

        if (_selc.options[_selc.selectedIndex].value == 's_lbl') {
            var the_label = window.prompt('Enter Content Label?','');
            if (trim(the_label)=='') {
                return;
            }
            //_frm.dopost.value = _frm.dopost.value +'|'+the_label;
            _frm.label.value = the_label;
        }
    } else return;

    if (confirm('Are you sure want to '+label+' the selected item(s) ?')) {
        //_frm.action = '/br_bcast/cp/index.php'
        _frm.submit();
    }
}

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
        _frm.action = '{$base_url}broadcast/schedule/delete';
        _frm.submit();
    }

}

function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

// --> 
</script>

<div class="pagetitle">{$pageTitle}</div>
<div class="midlemidle">
    <div><font color="red">{$message}</font></div>
    <form name=fmenu method="post">
    <table width=100% cellspacing=0 cellpadding=3>
        <tr><td colspan=2 class='textBig bottomline'>Schedule List</td></tr>
        <tr><td bgcolor=whitesmoke class='bottomline'>
            <select name="svc_id">
                <option value='null'>-- service --</option>
                {html_options values=$svc_ids output=$svc_names selected=$svc_id}
            </select>
            <select name="adn_id">
                <option value='null'>-- adn --</option>
                {html_options values=$adn_ids output=$adn_names selected=$adn_id}
            </select>
            <select name="opr_id">
                <option value='null'>-- operator --</option>
                {html_options values=$opr_ids output=$opr_names selected=$opr_id}
            </select>
            <br>
            <select name="sta_id">
                <option value='null'>-- status --</option>
                {html_options values=$sta_ids output=$sta_names selected=$sta_id}
            </select>
            <select name=recr><option value=>-- repeat --<option value='0' >Once<option value='1' >Daily<option value='2' >Weekly</select> 
            Date: 
            <input type=text readonly onclick="displayDatePicker('push_date', false, 'ymd','-');" name=push_date value='' size=10>
            <input type=button value=".." onclick="displayDatePicker('push_date', false, 'ymd','-');">
            <input type=submit class=clsbutton value='Search'>
            
            </td>
            <td bgcolor=whitesmoke class='bottomline' align=right><a href='{$base_url}broadcast/schedule/insert' class=urlmaroon><b>NEW SCHEDULE</b></a> | With Selected: <select name=doselected><option>-<option value=sche_d>Disable Schedule<option value=sche_e>Enable Schedule<option value=s_lbl>Set Content</select> <input type=button value='Go' onclick='doSelected()'> &nbsp;&nbsp;</td>
        </tr>
    </table>
    </form>    

     <table width=100% cellpadding="2" cellspacing="2" >
   <tr>
     <th align="left" class="BottomLine" ><span class="TextMed"><b></b></span></th>
   </tr>
   <tr>
     <td width=100% >
	   
	   <table width=100%  >
	     <tr><td width=100%>
		   <table width=100% border=0>
		      <tr><td valign="bottom"><table cellpadding=0 cellspacing=0><form><tr><td><input type=Hidden name=pg value=1>Show <input type=Text class=clsinput maxlength=2 name=rec size=2 value="{$rec}"> Records <input type=submit class=clsbutton value=Show></td></tr></form></table></td><td align="right" valign="bottom"></td></tr>
		      <tr><td valign="bottom">
			  </td><td align="right"><table cellpadding=0 cellspacing=0><form><tr></tr></form></table></td></tr>
		      <tr><td colspan=2>
			  	<table cellpadding=0 cellspacing=0>
					<tr>
					<td valign="bottom"></td>
					<td width=10></td>
					<td valign="bottom"></td>
					</tr>
				</table>
			  </td></tr>
		   </table>
		 </td></tr>
        {$page_navigation}

	     <tr><td width="100%" >

<form name=fpagelist method=post>
<input type="hidden" name="label">
<input type=Hidden name=f_key><input type=Hidden name=dopost value="">
<table border=0 cellpadding=3 cellspacing=0 width=100% >
    <tr>
        <td bgColor='silver' width=70 >Service</td>
        <td bgColor='silver' width=180 >Operator</td>
        <td bgColor='silver' width=100 >Next Push</td>
        <td bgColor='silver' width=60 >Status</td>
        <td bgColor='silver' width=60 >Repeat</td>
        <td bgColor='silver' width=80 >Content</td>
        <td bgColor='silver' width=100 >Content Source</td>
        <td bgColor='silver' width=50 >Price</td>
        <td bgColor='silver'  >Handler</td>
        <td bgColor='silver' width=100 >Notes</td>
        <td bgColor='silver' align=right width=130>&nbsp;<a href="javascript:doDeleteChecked();"><b>Delete Sel</b></a>&nbsp;<a title="Clear All Check" href="javascript:SelectCheck(false);">[&nbsp;]</a>&nbsp;<a title="Check All" href="javascript:SelectCheck(true);">[X]</a></td>
    </tr>
    {foreach from=$services key=svcId item=i}
    <tr>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70><b>{$i.service}</b></td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=180>{$i.operator}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>{$i.push_time}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=60>{$i.status}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=60>{$i.recurring_type}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=80>{$i.content_select}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>{$i.content_label}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=50>{$i.price}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' >{$i.handlerfile}</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>&nbsp;{$i.notes}</td>
        <td align=right style='border-bottom:solid 1px gainsboro;font-size:7pt'> <a href="{$base_url}broadcast/schedule/edit?id={$i.id}"><b>edit</b></a> <input type=checkbox name=cSel[] value="{$i.id}"></td>
    </tr>
    {/foreach}
</table>
</form>
</td></tr>
        {$page_navigation}
         <tr><td ></td></tr>
	   </table>
	   
	   
     </td>
   </tr>
 </table>

</div>

{include file="common/tpl_footer.tpl"}
