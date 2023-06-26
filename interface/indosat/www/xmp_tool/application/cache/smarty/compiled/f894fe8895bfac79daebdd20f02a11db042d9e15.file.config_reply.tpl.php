<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 14:34:46
         compiled from "application/views/smswebtool/config_reply.tpl" */ ?>
<?php /*%%SmartyHeaderCode:172116693050bc56169b4695-48979647%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f894fe8895bfac79daebdd20f02a11db042d9e15' => 
    array (
      0 => 'application/views/smswebtool/config_reply.tpl',
      1 => 1348476156,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '172116693050bc56169b4695-48979647',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pagetitle' => 0,
    'base_url' => 0,
    'svc_ids' => 0,
    'svc_names' => 0,
    'svc_id' => 0,
    'reply_display' => 0,
    'service_file' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc5616a0bee',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc5616a0bee')) {function content_50bc5616a0bee($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/app/xmp2012/interface/telkomsel/www/xmp_tool/application/third_party/Smarty/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="pagetitle"><?php echo $_smarty_tpl->tpl_vars['pagetitle']->value;?>
</div>
<div class="midlemidle">
    <div id="maincontent">
        <div id="pageheading"><h2>Message Reply</h2></div>
        <div class="actionbar">
            <form action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
smswebtool/config_reply/filter" method="post">
                Service
                <select name="service_filter" id="service_filter">
                  <option value="">--------</option>
                  <?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['svc_ids']->value,'output'=>$_smarty_tpl->tpl_vars['svc_names']->value,'selected'=>$_smarty_tpl->tpl_vars['svc_id']->value),$_smarty_tpl);?>

                </select>
                <input type="submit" name="filter" value="filter">
            </form>
	</div>

        <?php if (count($_smarty_tpl->tpl_vars['reply_display']->value)>0&&$_smarty_tpl->tpl_vars['reply_display']->value[0]['function']!=''){?>
        <div align="center"><b>Reply Message For <?php echo $_smarty_tpl->tpl_vars['service_file']->value;?>
</b></div><br />
        <table class="datagrid2" width="100%">
            <tbody>
                <tr>
                    <th width="29%">Function</th>
                    <th width="42%">Message</th>
                    <th width="12%">Price</th>
                    <th width="9%">Length</th>
                    <th width="8%">Tools</th>
                </tr>
                <?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['reply_display']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
?>
                <tr>
                    <td><?php echo $_smarty_tpl->tpl_vars['i']->value['function'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['i']->value['message'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['i']->value['value'];?>
</td>
                    <td><?php echo $_smarty_tpl->tpl_vars['i']->value['length'];?>
</td>
                    <td>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
smswebtool/config_reply/edit/service/<?php echo $_smarty_tpl->tpl_vars['service_file']->value;?>
/function/<?php echo $_smarty_tpl->tpl_vars['i']->value['function_encode'];?>
">Edit</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php }?>
    </div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>