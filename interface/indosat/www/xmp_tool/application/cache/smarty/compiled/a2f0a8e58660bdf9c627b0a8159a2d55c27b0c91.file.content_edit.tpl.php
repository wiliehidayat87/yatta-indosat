<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 16:19:38
         compiled from "application/views/broadcast/content_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:79854919350bc6eaab1a8a6-10243684%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2f0a8e58660bdf9c627b0a8159a2d55c27b0c91' => 
    array (
      0 => 'application/views/broadcast/content_edit.tpl',
      1 => 1347962199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '79854919350bc6eaab1a8a6-10243684',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'pagetitle' => 0,
    'action' => 0,
    'svc_ids' => 0,
    'svc_names' => 0,
    'svc_id' => 0,
    'content' => 0,
    'content_label' => 0,
    'author' => 0,
    'datepublish_date' => 0,
    'datepublish_hour' => 0,
    'notes' => 0,
    'lastused' => 0,
    'modified' => 0,
    'created' => 0,
    'content_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc6eaab7b57',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc6eaab7b57')) {function content_50bc6eaab7b57($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/dp.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/dp.js"></script>

<div class="pagetitle"><?php echo $_smarty_tpl->tpl_vars['pagetitle']->value;?>
</div>
<div class="midlemidle">
<form action="./<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" method="post">

<table>
    <tr>
        <td>Service</td>
        <td>
            <select name="service">
                <option value='null'>-- service --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['svc_ids']->value,'output'=>$_smarty_tpl->tpl_vars['svc_names']->value,'selected'=>$_smarty_tpl->tpl_vars['svc_id']->value),$_smarty_tpl);?>

            </select>
        </td>
    </tr>
    <tr>
        <td>Content</td>
        <td>
            <textarea name=content rows=2 cols=60 wrap=physical onKeyDown="javascript:textLimitCounter(document.forms.fcontent.content, document.forms.fcontent.msgCount,250);" onKeyUp="javascript:textLimitCounter(document.forms.fcontent.content, document.forms.fcontent.msgCount,250);"><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</textarea><br>
            <input name=msgCount class=clsinput size=3 maxlength=3 readonly value=250> char(s) left
        </td>
    </tr>
    <tr>
        <td>Content Label</td>
        <td><input type=text name=content_label value='<?php echo $_smarty_tpl->tpl_vars['content_label']->value;?>
' maxlength=15></td>
    </tr>
    <tr>
        <td>Author</td>
        <td><input type=text name=author value='<?php echo $_smarty_tpl->tpl_vars['author']->value;?>
' maxlength=25></td>
    </tr>
    <tr>
        <td>Publish Date</td>
        <td>
            <input type=text readonly onclick="displayDatePicker('datepublish_date', false, 'ymd','-');" name=datepublish_date value='<?php echo $_smarty_tpl->tpl_vars['datepublish_date']->value;?>
' size=10 maxlength=10>
            <input type=button value=".." onclick="displayDatePicker('datepublish_date', false, 'ymd','-');">
            Time : <input type=text name=datepublish_hour value='<?php echo $_smarty_tpl->tpl_vars['datepublish_hour']->value;?>
' size=8 maxlength=8></td></tr>
        </td>
    </tr>
    <tr>
        <td>Notes</td>
        <td><textarea name=notes rows=1 cols=60 wrap=physical onKeyDown="textLimitCounter(document.forms.fcontent.notes, document.forms.fcontent.msgCount2,250);" onKeyUp="textLimitCounter(document.forms.fcontent.notes, document.forms.fcontent.msgCount2,250);"><?php echo $_smarty_tpl->tpl_vars['notes']->value;?>
</textarea><br>
        <input name=msgCount2 class=clsinput size=3 maxlength=3 readonly value=250> char(s) left
        </td>
    </tr>

    <?php if ($_smarty_tpl->tpl_vars['action']->value=='editSave'){?>
    <tr><td colspan=2><u>Additional infos:</u></td></tr>    
    <tr><td>Last publish</td><td><i><?php echo $_smarty_tpl->tpl_vars['lastused']->value;?>
</i> - will be important/used for random content</td></tr>
    <tr><td>Last modified</td><td><i><?php echo $_smarty_tpl->tpl_vars['modified']->value;?>
</i></td></tr>
    <tr><td>Created</td><td><i><?php echo $_smarty_tpl->tpl_vars['created']->value;?>
</i></td></tr>
    <?php }?>
</table>
    <input type="submit" value="Save">
    <?php if ($_smarty_tpl->tpl_vars['action']->value=='editSave'){?>
    <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['content_id']->value;?>
">
    <?php }?>
    
</form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>