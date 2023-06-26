<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 16:19:29
         compiled from "application/views/broadcast/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:141103605350bc6ea119eb80-25031522%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '42c03d1615700f08242736b4fd3ea87218f2d8a9' => 
    array (
      0 => 'application/views/broadcast/content.tpl',
      1 => 1353322199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '141103605350bc6ea119eb80-25031522',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'pageTitle' => 0,
    'svc_ids' => 0,
    'svc_names' => 0,
    'svc_id' => 0,
    'content' => 0,
    'rec' => 0,
    'page_navigation' => 0,
    'contents' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc6ea1222a4',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc6ea1222a4')) {function content_50bc6ea1222a4($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/dp.js"></script>


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

<div class="pagetitle"><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</div>
<div class="midlemidle">

 <table width=100% cellspacing=0 cellpadding=3>
    <form name='srcform' method='post'>
        <tr><td colspan=2 class='textBig bottomline'>Text Content List</td></tr>
        <tr><td bgcolor=whitesmoke class='bottomline'>
            Service : 
            <select name="service">
                <option value='null'>-- service --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['svc_ids']->value,'output'=>$_smarty_tpl->tpl_vars['svc_names']->value,'selected'=>$_smarty_tpl->tpl_vars['svc_id']->value),$_smarty_tpl);?>

            </select>
            Content : <input type=text name=content value='<?php echo $_smarty_tpl->tpl_vars['content']->value;?>
'>
            Publish Date: 
            <input type=text readonly onclick="displayDatePicker('push_date', false, 'ymd','-');" name=push_date value='' size=10>
            <input type=button value=".." onclick="displayDatePicker('push_date', false, 'ymd','-');">
            <input type=submit class=clsbutton value='Search'>
            </td>
            <td bgcolor=whitesmoke class='bottomline' align=right><a href='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/insert' class=urlmaroon><b>NEW</b></a> | <a href='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/import' class=urlmaroon><b>IMPORT</b></a> | <a class=urlgray><b>EXPORT</b></a> &nbsp;&nbsp;</td>
        </tr>
    </form>    
</table>

<table cellpadding=0 cellspacing=0><form><tr><td><input type=Hidden name=pg value=1>Show <input type=Text class=clsinput maxlength=2 name=rec size=2 value="<?php echo $_smarty_tpl->tpl_vars['rec']->value;?>
"> Records <input type=submit class=clsbutton value=Show></td></tr></form></table>

<?php echo $_smarty_tpl->tpl_vars['page_navigation']->value;?>


<form name=fpagelist method=post action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/delete">
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
    <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['conId'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['contents']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['conId']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
    <tr>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70><b><?php echo $_smarty_tpl->tpl_vars['i']->value['service'];?>
</b></td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=70><?php echo $_smarty_tpl->tpl_vars['i']->value['content_label'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' ><?php echo $_smarty_tpl->tpl_vars['i']->value['content'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=120><?php echo $_smarty_tpl->tpl_vars['i']->value['datepublish'];?>
</td>
        <td style='border-bottom:solid 1px gainsboro;font-size:7pt' width=100>&nbsp;<?php echo $_smarty_tpl->tpl_vars['i']->value['notes'];?>
</td>
        <td align=right style='border-bottom:solid 1px gainsboro;font-size:7pt'> <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/edit?id=<?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
"><b>edit</b></a> <input type=checkbox name=cSel[] value="<?php echo $_smarty_tpl->tpl_vars['i']->value['id'];?>
"></td>
    </tr>
    <?php } ?>
</table>
</form>

<?php echo $_smarty_tpl->tpl_vars['page_navigation']->value;?>


</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>