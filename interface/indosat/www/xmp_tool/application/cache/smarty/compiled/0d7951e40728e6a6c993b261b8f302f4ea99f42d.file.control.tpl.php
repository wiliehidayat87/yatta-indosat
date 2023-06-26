<?php /* Smarty version Smarty 3.1.4, created on 2012-12-03 16:03:50
         compiled from "application/views/pin/control.tpl" */ ?>
<?php /*%%SmartyHeaderCode:60845880950bc6af6dfbbd3-15466969%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0d7951e40728e6a6c993b261b8f302f4ea99f42d' => 
    array (
      0 => 'application/views/pin/control.tpl',
      1 => 1347279443,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '60845880950bc6af6dfbbd3-15466969',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'operator' => 0,
    'hourStart' => 0,
    'minuteStart' => 0,
    'hourEnd' => 0,
    'minuteEnd' => 0,
    'pageLimit' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_50bc6af6ecded',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50bc6af6ecded')) {function content_50bc6af6ecded($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/tpl_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="pagetitle">PIN Control</div>
<div class="middletop">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">Add Schedule</div>
            <div class="search-area">
                <form name="search-form" id="search-form">
                    <input type="text" name="search-field" id="search-field" class="search-field" />
                    <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form name="pin-control-form" id="pin-control-form"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr valign="top">
                        <td width="30%">
                            <label for="txt-operator">Operator *</label>
                        </td>
                        <td width="70%">
                            <?php echo $_smarty_tpl->tpl_vars['operator']->value;?>

                            &nbsp;<span id="inf-operator"></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <label for="txt-name">Name *</label>
                        </td>
                        <td width="70%">
                            <input type="text" name="txt-name" id="txt-name" class="inputtext-1" />
                            &nbsp;<span id="inf-name"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="txt-desc">Description</label>
                        </td>
                        <td>
                            <input type="text" name="txt-desc" id="txt-desc" class="inputtext-1" />
                            &nbsp;<span id="inf-desc"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="txt-active"></label>
                        </td>
                        <td>
                            <input type="checkbox" name="txt-active" id="txt-active" class="inputtext-1" /> Is Active
                            <table>
                                <tr>
                                    <td vlign='top'>Mon</td>
                                    <td>From <span><select name="txt-mon-h-start" id="txt-mon-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-mon-m-start" id="txt-mon-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-mon-h-end" id="txt-mon-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-mon-m-end" id="txt-mon-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tue</td>
                                    <td>From <select name="txt-tue-h-start" id="txt-tue-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>
<option></option>
                                        </select>
                                        <select name="txt-tue-m-start" id="txt-tue-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-tue-h-end" id="txt-tue-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-tue-m-end" id="txt-tue-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wed</td>
                                    <td>From <select name="txt-wed-h-start" id="txt-wed-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-wed-m-start" id="txt-wed-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-wed-h-end" id="txt-wed-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-wed-m-end" id="txt-wed-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Thu</td>
                                    <td>From <select name="txt-thu-h-start" id="txt-thu-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-thu-m-start" id="txt-thu-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-thu-h-end" id="txt-thu-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-thu-m-end" id="txt-thu-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fri</td>
                                    <td>From <select name="txt-fri-h-start" id="txt-fri-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-fri-m-start" id="txt-fri-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-fri-h-end" id="txt-fri-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-fri-m-end" id="txt-fri-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sat</td>
                                    <td>From <select name="txt-sat-h-start" id="txt-sat-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-sat-m-start" id="txt-sat-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-sat-h-end" id="txt-sat-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-sat-m-end" id="txt-sat-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sun</td>
                                    <td>From <select name="txt-sun-h-start" id="txt-sun-h-start">
                                            <?php echo $_smarty_tpl->tpl_vars['hourStart']->value;?>

                                        </select>
                                        <select name="txt-sun-m-start" id="txt-sun-m-start">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteStart']->value;?>

                                        </select>
                                        To
                                        <select name="txt-sun-h-end" id="txt-sun-h-end">
                                            <?php echo $_smarty_tpl->tpl_vars['hourEnd']->value;?>

                                        </select>
                                        <select name="txt-sun-m-end" id="txt-sun-m-end">
                                            <?php echo $_smarty_tpl->tpl_vars['minuteEnd']->value;?>

                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
                <input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
            </form>
        </div>
    </div>
</div>
<div class="midlemidle">
    <div class="boxheader reporttable">
        <table id="pin-control-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Operator</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                    <th nowarp>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="pagination">
        <ul>
            <div class="clear"></div>
        </ul>
    </div>
    <div class="viewlimit">
        View limit : 
        <select name="pageLimit" id="pageLimit">
            <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pageLimit']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
            <?php } ?>
        </select>
    </div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("common/tpl_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>