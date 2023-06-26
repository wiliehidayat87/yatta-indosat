{include file="common/tpl_header.tpl"}
{literal}
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>
{/literal}
<script type="text/javascript">var gbl_service_id = "{$service_id}";</script>
<script type="text/javascript">var gbl_operator = "{$operator}";</script>
    <div class="pagetitle">Manage Creator</div>
<form action ="{$base_url}service/add_service/submit_keyword_edit" method="post" >
<input type="hidden" value="{$service_id}" name="service_id" />
<input type="hidden" value="{$mechanism_id}" name="mechanism_id" />
    <div class="middletop">
            <div style="padding:10px; color:#000; background-color:#fff">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">Keyword * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-pattern" name="keyword" size="30" maxlength="50" value="{$mecha.pattern}" readonly/>
                                &nbsp;<span id="inf-pattern"></span>
                            </td>
                        </tr>
                  </table>
	</div>
    </div>
    <div class="midlemidle">
        <div id="tabs">
            <ul id="tabs">
                {foreach from=$operator item=operator_item key=operator_key}
                    <li id="tabs"><a href="#tabs-{$operator_item}" class="operator testing-1" operator="{$operator_item}" >{$operator_item}</a></li>
                {/foreach}
            </ul>

{foreach from=$operator item=operator_item key=operator_key}
    <div id="tabs-{$operator_item}">
<div><input type="checkbox" name="active[{$operator_item}]" value="1" checked="true" /> active</div>
<div>
<br />
Handler Type <select name="{$operator_item}[handler-type]" id="handler-type-{$operator_item}" onChange="javascript:selectHandler('{$operator_item}','{$service_name}')" >
<option value="">Select Handler Type</option>
<option {if $mecha.handler == 'service_creator_handler'}selected="selected" {/if}value="creator">Service Creator</option>
<option {if $mecha.handler != 'service_creator_handler'}selected="selected" {/if}value="custom">Service Custom</option>
</select>
</div>

<div id="creator-{$operator_item}">
<br />
<div>
<div style="float:left; width: 50%">
Module <select name="{$operator_item}[module]" id="select-module-{$operator_item}" onChange="javascript:getModule('{$operator_item}','{$adn}')">
<option value="">Select Module</option>
<option {if $module_name == 'registration'}selected="selected" {/if}value="registration">Registration</option>
<option {if $module_name == 'unregistration'}selected="selected" {/if}value="unregistration">UnRegistration</option>
<option {if $module_name == 'text'}selected="selected" {/if}value="text">Text</option>
<option {if $module_name == 'textdelay'}selected="selected" {/if}value="textdelay">Text Delay</option>
<option {if $module_name == 'waplink'}selected="selected" {/if}value="waplink">Wap Link</option>
<option {if $module_name == 'wappush'}selected="selected" {/if}value="wappush">Wap Push</option>
</select>
</div>
<div style="float:right; width: 49%; text-align:right">
<input type="button" name="addmodule" value="Add Module" onClick="javascript:addModule('{$operator_item}','{$adn}')" />
</div>
</div>
<br /><br />
<div id="module-content-{$operator_item}" style="padding-top: 15px">
{foreach from=$module item=modules}
    {foreach from=$modules item=module_item key=module_key}
        {$module_item}
    {/foreach}
    </div>
{/foreach}
</div>
<div id="content-operator-{$operator_item}"></div>
</div>

<div id="custom-{$operator_item}" style="display:none">
INI TEXT CUSTOM
</div>

	</div>
{/foreach}

	</div><br />
<input type="submit" value="edit_keyword" name="Edit Keyword" />
    </div>
</form>
{include file="common/tpl_footer.tpl"}
