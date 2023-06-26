<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 13:27:56
         compiled from "application/views/broadcast/schedule.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185297951150bc466c07bb30-84163476%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ab7c21bfb906e431dc308ea06450bd97c2a3dd3' => 
    array (
      0 => 'application/views/broadcast/schedule.tpl',
      1 => 1353322199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185297951150bc466c07bb30-84163476',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'pageTitle' => 0,
    'message' => 0,
    'svc_ids' => 0,
    'svc_names' => 0,
    'svc_id' => 0,
    'adn_ids' => 0,
    'adn_names' => 0,
    'adn_id' => 0,
    'opr_ids' => 0,
    'opr_names' => 0,
    'opr_id' => 0,
    'sta_ids' => 0,
    'sta_names' => 0,
    'sta_id' => 0,
    'rec' => 0,
    'page_navigation' => 0,
    'services' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc466c15b58',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc466c15b58')) {function content_50bc466c15b58($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/dp.js"></script>

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
        _frm.action = '<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/schedule/delete';
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

<div class="pagetitle"><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</div>
<div class="midlemidle">
    <div><font color="red"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</font></div>
    <form name=fmenu method="post">
    <table width=100% cellspacing=0 cellpadding=3>
        <tr><td colspan=2 class='textBig bottomline'>Schedule List</td></tr>
        <tr><td bgcolor=whitesmoke class='bottomline'>
            <select name="svc_id">
                <option value='null'>-- service --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['svc_ids']->value,'output'=>$_smarty_tpl->tpl_vars['svc_names']->value,'selected'=>$_smarty_tpl->tpl_vars['svc_id']->value),$_smarty_tpl);?>

            </select>
            <select name="adn_id">
                <option value='null'>-- adn --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['adn_ids']->value,'output'=>$_smarty_tpl->tpl_vars['adn_names']->value,'selected'=>$_smarty_tpl->tpl_vars['adn_id']->value),$_smarty_tpl);?>

            </select>
            <select name="opr_id">
                <option value='null'>-- operator --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['opr_ids']->value,'output'=>$_smarty_tpl->tpl_vars['opr_names']->value,'selected'=>$_smarty_tpl->tpl_vars['opr_id']->value),$_smarty_tpl);?>

            </select>
            <br>
            <select name="sta_id">
                <option value='null'>-- status --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['sta_ids']->value,'output'=>$_smarty_tpl->tpl_vars['sta_names']->value,'selected'=>$_smarty_tpl->tpl_vars['sta_id']->value),$_smarty_tpl);?>

            </select>
            <select name=recr><option value=>-- repeat --<option value='0' >Once<option value='1' >Daily<option value='2' >Weekly</select> 
            Date: 
            <input type=text readonly onclick="displayDatePicker('push_date', false, 'ymd','-');" name=push_date value='' size=10>
            <input type=button value=".." onclick="displayDatePicker('push_date', false, 'ymd','-');">
            <input type=submit class=clsbutton value='Search'>
            
            </td>
            <td bgcolor=whitesmoke class='bottomline' align=right><a href='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/schedule/insert' class=urlmaroon><b>NEW SCHEDULE</b></a> | With Selected: <select name=doselected><option>-<option value=sche_d>Disable Schedule<option value=sche_e>Enable Schedule<option value=s_lbl>Set Content</select> <input type=button value='Go' onclick='doSelected()'> &nbsp;&nbsp;</td>
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
	     <tr><td width=100%<?php ?>>
		   <table width=100% border=0>
		      <tr><td valign="bottom"><table cellpadding=0 cellspacing=0><form><tr><td><input type=Hidden name=pg value=1>Show <input type=Text class=clsinput maxlength=2 name=rec size=2 value="<?php echo $_smarty_tpl->tpl_vars['rec']->value;?>
"> Records <input type=submit class=clsbutton value=Show></td></tr></form></table></td><td align="right" valign="bottom"></td></tr>
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
        <?php echo $_smarty_tpl->tpl_vars['page_navigation']->value;?>


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
    <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['svcId'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['services']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['svcId']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
    <tr>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70><b><?php echo $_smarty_tpl->tpl_vars['i']->value['service'];?>
</b></td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=180><?php echo $_smarty_tpl->tpl_vars['i']->value['operator'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100><?php echo $_smarty_tpl->tpl_vars['i']->value['push_time'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=60><?php echo $_smarty_tpl->tpl_vars['i']->value['status'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=60><?php echo $_smarty_tpl->tpl_vars['i']->value['recurring_type'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=80><?php echo $_smarty_tpl->tpl_vars['i']->value['content_select'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100><?php echo $_smarty_tpl->tpl_vars['i']->value['content_label'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=50><?php echo $_smarty_tpl->tpl_vars['i']->value['price'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' ><?php echo $_smarty_tpl->tpl_vars['i']->value['handlerfile'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>&nbsp;<?php echo $_smarty_tpl->tpl_vars['i']->value['notes'];?>
</td>
        <td align=right style='border-bottom:solid 1px gainsboro;font-size:7pt'> <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/schedule/edit?id=<?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
"><b>edit</b></a> <input type=checkbox name=cSel[] value="<?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
"></td>
    </tr>
    <?php } ?>
</table>
</form>
</td></tr>
        <?php echo $_smarty_tpl->tpl_vars['page_navigation']->value;?>

         <tr><td ></td></tr>
	   </table>
	   
	   
     </td>
   </tr>
 </table>

</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>