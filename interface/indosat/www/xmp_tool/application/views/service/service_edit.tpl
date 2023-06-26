{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Edit Service Keyword</div>

{if !empty($error)}
<div id="error" >
<h3>Error</h4>
{foreach from=$error item=error_item key=error_key}
    <p>{$error_item}</p>
{/foreach}
</div>
{/if}

<fieldset>
<legend>Service Info</legend>
<form action="{$base_url}/service/add_service/change_name" method="post">
<input type="hidden" name="service_id" value="{$service_id}" />
<table border="0" cellpadding="3" cellspacing="1">
<tr>
<td>ADN</td><td>: 
<select name="adn">
{foreach from=$all_adn item=all_adn_item key=all_adn_key}
    <option value="{$all_adn_item.name}" {if ($adn == $all_adn_item.name)} selected="selected"{/if} >{$all_adn_item.name}</option>
{/foreach}
</select>
</td>
</tr>
<td>Service Name</td><td> : <input type="text" name="service_name" value="{$service_name}" /></td>
</tr>
<tr><td colspan="2"><input type="submit" value="Edit Service" name="edit_service" /></td></tr>
</table>
</form>
</fieldset>
<br />

{foreach from=$operator item=operat key=operator_key}
<input type="hidden" name="operator[]" value="{$operat}" />
{/foreach}
<input type="hidden" name="adn" value="{$adn}" id="adn" />
<input type="hidden" name="service_name" value="{$service_name}" id="service_name" />
<input type="hidden" name="service_id" value="{$service_id}" id="service_id" />
    <button id="add-new-keyword" attr="adn={$adn}&service_id={$service_id}">Add New Keyword</button>
<br />
<div id="dialog-form" title="Operator">
	<form id="form_list_operator" action ="{$base_url}service/add_service/edit_addkeyword" method="post">
	</form>
</div>
    <div class="middletop">
            	<div class="boxheader reporttable">
            <table id="adn-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th>Keyword</th>
                        <th width="130px" class="last">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$keywords item=keyword key=keyword_key}
                    <tr>
                        <td attr="{$keyword.id}" class="keyword_list">{$keyword.pattern}</td>
                        <td><a href="javascript:void(0)" id="{$keyword.pattern|urlencode}" class="add-operator">Add Operator</a> | <a href="{$base_url}service/add_service/delKeywordByPattern/{$service_id}/{$keyword.pattern|urlencode}" onclick="return confirm('Are you sure to delete?');">Delete</a></td>
                    </tr>
                    {foreach from=$operator_pattern item=operator_item key=operator_key}
                    {if $operator_key == $keyword.pattern}
                    <tr class="{$keyword.id}" attr="hide">
                        <td id="detail_header">Operator</td>
                        <td id="detail_header">Action</td>
                    </tr>
                    {foreach from=$operator_item item=operator_item2 key=operator_key2}
                    <tr class="{$keyword.id}" attr="hide">
                        <td id="detail_body">{$operator_item2.operator_name}</td>
                        <td id="detail_body"><a href="{$base_url}service/add_service/edit_keyword/{$service_id}/{$operator_item2.operator_name}/{$operator_item2.id}">Edit</a> | <a href="{$base_url}service/add_service/deleteKeyword/{$service_id}/{$operator_item2.id}" onclick="return confirm('Are you sure to delete ?');">Delete</a></td>
                    </tr>
                    {/foreach}
                    {/if}
                    {/foreach}
                    {/foreach}
                    </tbody>
            </table>
        </div>
	</div>
{include file="common/tpl_footer.tpl"}
