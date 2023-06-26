<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:43:40
         compiled from "application/views/broadcast/schedule_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:188751724750bc582c1c8c38-39209047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '570dc387ebe73d1b1f665a52cb697c28334f69f4' => 
    array (
      0 => 'application/views/broadcast/schedule_edit.tpl',
      1 => 1347962199,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188751724750bc582c1c8c38-39209047',
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
    'opr_ids' => 0,
    'opr_names' => 0,
    'opr_id' => 0,
    'adn_ids' => 0,
    'adn_names' => 0,
    'adn_id' => 0,
    'content_label' => 0,
    'cSelect_ids' => 0,
    'cSelect_names' => 0,
    'cSelect_id' => 0,
    'handlerfile' => 0,
    'push_time_date' => 0,
    'push_time_hour' => 0,
    'sta_ids' => 0,
    'sta_names' => 0,
    'sta_id' => 0,
    'rep_ids' => 0,
    'rep_names' => 0,
    'rep_id' => 0,
    'price' => 0,
    'notes' => 0,
    'last_content_id' => 0,
    'modified' => 0,
    'created' => 0,
    'schedule_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc582c27895',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc582c27895')) {function content_50bc582c27895($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/function.html_options.php';
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
            <td>Operator</td>
            <td>
            <select name="operator">
                <option value='null'>-- operator --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['opr_ids']->value,'output'=>$_smarty_tpl->tpl_vars['opr_names']->value,'selected'=>$_smarty_tpl->tpl_vars['opr_id']->value),$_smarty_tpl);?>

            </select>
            </td>
        </tr>
        <tr>
            <td>Shortcode</td>
            <td>
            <select name="adn">
                <option value='null'>-- adn --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['adn_ids']->value,'output'=>$_smarty_tpl->tpl_vars['adn_names']->value,'selected'=>$_smarty_tpl->tpl_vars['adn_id']->value),$_smarty_tpl);?>

            </select>
            </td>
        </tr>
        <tr><td>Content Source</td><td><input type=text id=content_label name=content_label value='<?php echo $_smarty_tpl->tpl_vars['content_label']->value;?>
' maxlength=15></td></tr>
        <tr>
            <td>Content Select</td>
            <td>
            <select name="content_select">
                <option value='null'>-- content select --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['cSelect_ids']->value,'output'=>$_smarty_tpl->tpl_vars['cSelect_names']->value,'selected'=>$_smarty_tpl->tpl_vars['cSelect_id']->value),$_smarty_tpl);?>

            </select>
            </td>
        </tr>
        <tr><td>Handler</td><td><input type=text name=handlerfile id=handlerfile value='<?php echo $_smarty_tpl->tpl_vars['handlerfile']->value;?>
' maxlength=255></td></tr>
        <tr><td>Push Date</td><td>
        <input type=text readonly onclick="displayDatePicker('push_time_date', false, 'ymd','-');" name=push_time_date value='<?php echo $_smarty_tpl->tpl_vars['push_time_date']->value;?>
' size=10  maxlength=10>
        <input type=button value=".." onclick="displayDatePicker('push_time_date', false, 'ymd','-');">
        Time : <input type=text name=push_time_hour value='<?php echo $_smarty_tpl->tpl_vars['push_time_hour']->value;?>
' size=8 maxlength=8></td></tr>
        <tr>
            <td>Status</td>
            <td>
            <select name="status">
                <option value='null'>-- status --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['sta_ids']->value,'output'=>$_smarty_tpl->tpl_vars['sta_names']->value,'selected'=>$_smarty_tpl->tpl_vars['sta_id']->value),$_smarty_tpl);?>

            </select>
            </td>
        </tr>
        <tr>
            <td>Repeat</td>
            <td>
            <select name="recurring_type">
                <option value='null'>-- repeat --</option>
                <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['rep_ids']->value,'output'=>$_smarty_tpl->tpl_vars['rep_names']->value,'selected'=>$_smarty_tpl->tpl_vars['rep_id']->value),$_smarty_tpl);?>

            </select>
            </td>
        </tr>
        <tr><td>Price</td><td><input type=text name=price value='<?php echo $_smarty_tpl->tpl_vars['price']->value;?>
' maxlength=5 size=5></td></tr>
        <tr><td>Notes</td><td><textarea name=notes rows=1 cols=60 wrap=physical onKeyUp="textLimitCounter(this.form.notes, this.form.msgCount,128);"><?php echo $_smarty_tpl->tpl_vars['notes']->value;?>
</textarea><br>
            <input name=msgCount class=clsinput size=3 maxlength=3 readonly value=128> char(s) left
        </td></tr>
        <?php if ($_smarty_tpl->tpl_vars['action']->value=='editSave'){?>
        <tr><td colspan=2><u>Additional infos:</u></td></tr>    
        <tr><td>Last Content #id</td><td><i><?php echo $_smarty_tpl->tpl_vars['last_content_id']->value;?>
</i></td></tr>
        <tr><td>Last modified</td><td><i><?php echo $_smarty_tpl->tpl_vars['modified']->value;?>
</i></td></tr>
        <tr><td>Created</td><td><i><?php echo $_smarty_tpl->tpl_vars['created']->value;?>
</i></td></tr>
        <?php }?>
    </table>
    <input type="submit" value="Save">
    <?php if ($_smarty_tpl->tpl_vars['action']->value=='editSave'){?>
    <input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['schedule_id']->value;?>
">
    <?php }?>
</form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>