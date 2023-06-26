<?php /* Smarty version Smarty 3.1.4, created on 2012-12-23 23:34:08
         compiled from "application/views/broadcast/content_import.tpl" */ ?>
<?php /*%%SmartyHeaderCode:129822616550d73280a02b36-40954581%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd0801b5412deb2838935e95d64cd2636e7962552' => 
    array (
      0 => 'application/views/broadcast/content_import.tpl',
      1 => 1347962199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129822616550d73280a02b36-40954581',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'pagetitle' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50d73280ab26d',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50d73280ab26d')) {function content_50d73280ab26d($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script language='javascript'>
function doSubmit ()
{ 
    var theForm = document.forms.fcontent;
    var ok  = false;
    for (var i=0; i<theForm.length; i++)
    {
        if (theForm.elements[i].type == 'file')
        {
            if (trim(theForm.elements[i].value)!='')
                ok = true;
        }
    }

    if (!ok)
    {
        alert ('Please select a content file to upload');
        return FALSE;
    }
    var rand_no = Math.random();
    var fwin = 'Popup_Window' + rand_no;
    var w = window.open('<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/import_progress',fwin,'width=300,height=150,resizeable=0');
    theForm.target = fwin;
    theForm.dopost.value='IMPORTCONTENTFILE';
    theForm.action = '<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/import_process';
    setTimeout("document.forms.fcontent.submit()",200);
    //document.location.href='index.php?id=PG_1227617312';
    //theForm.submit();
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
</script>

<div class="pagetitle"><?php echo $_smarty_tpl->tpl_vars['pagetitle']->value;?>
</div>
<div class="midlemidle">
    <form name=fcontent action='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content/import_process' method=post enctype="multipart/form-data">
        <input type=hidden name=id value=''>
        <input type=hidden name=dopost value='import'>
        <input type=hidden name=urlref value='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
broadcast/content'>
    <table>
        <tr><td>File 1</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>File 2</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>File 3</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>&nbsp;</td><td><i>
        <b>The file is text file with following rules:</b> <br>
        - Comma separated<br>
        - Text with space enclosed with double quote <br>
        - Mandatory columns (in order) : SERVICE, AUTHOR, CONTENT, PUBLISHTIME<br>
        
        <b>Example</b> :<br>
        ZODIAK,TEXT1,"Zodiak adalah ramalan bintang","2008-11-10 10:00:00",KB<br>
        ZODIAK,TEXT2,"Zodiak adalah ramalan bintang",0,KB<br>
        ZODIAK,ISATTEXT1,"Zodiak 'adalah' ramalan bintang","",KB<br>
        </i></td></tr>
        <tr><td></td><td><input type="button" value="Import" onClick="javascript:doSubmit()"></td></tr>
    </table>
    </form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>