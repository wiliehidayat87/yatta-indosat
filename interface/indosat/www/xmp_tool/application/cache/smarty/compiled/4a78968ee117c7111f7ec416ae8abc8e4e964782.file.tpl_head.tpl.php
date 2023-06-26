<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 11:50:25
         compiled from "application/views/common/tpl_head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:48075328650bc2f9195e222-84464957%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a78968ee117c7111f7ec416ae8abc8e4e964782' => 
    array (
      0 => 'application/views/common/tpl_head.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48075328650bc2f9195e222-84464957',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_url' => 0,
    'id' => 0,
    'param' => 0,
    'service_id' => 0,
    'operator' => 0,
    'jsFile' => 0,
    'js' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc2f919c985',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc2f919c985')) {function content_50bc2f919c985($_smarty_tpl) {?><script type="text/javascript"> var base_url="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
";</script>
<script type="text/javascript"> var cont_id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
";</script>
<script type="text/javascript"> var param="<?php echo $_smarty_tpl->tpl_vars['param']->value;?>
";</script>
<script type="text/javascript"> var service_id="<?php echo $_smarty_tpl->tpl_vars['service_id']->value;?>
";</script>
<script type="text/javascript"> var operator_id='<?php echo $_smarty_tpl->tpl_vars['operator']->value;?>
';</script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/autocomplete/jquery-1.5.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/autocomplete/jquery-ui-1.8.16.custom.min.js"></script>
<!--<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/jquery-ui-1.7.1.custom.min.js"></script>-->
<!--<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/daterangepicker.jQuery.js"></script>-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/ui.achtung.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/common.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/navigation/ddsmoothmenu.js"></script>
<?php if ((isset($_smarty_tpl->tpl_vars['jsFile']->value))){?>
    <?php if (is_array($_smarty_tpl->tpl_vars['jsFile']->value)){?>
        <?php  $_smarty_tpl->tpl_vars['js'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['js']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['jsFile']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['js']->key => $_smarty_tpl->tpl_vars['js']->value){
$_smarty_tpl->tpl_vars['js']->_loop = true;
?>
            <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
"></script>
    <?php } ?>
    <?php }else{ ?>
        <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/js/<?php echo $_smarty_tpl->tpl_vars['jsFile']->value;?>
"></script>
    <?php }?>
<?php }?>

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/tablelist.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/ui.daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" title="ui-theme" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/ui.achtung.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
public/css/jquery-ui-1.8.16.custom.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/navigation/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/navigation/ddsmoothmenu-v.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
themes/default/css/service/creator.css" type="text/css" />
<style>
#tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script><?php }} ?>