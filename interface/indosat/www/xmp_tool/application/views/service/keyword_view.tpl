{include file="common/tpl_header.tpl"}
<script type="text/javascript">var gbl_service_id = "{$service_id}";</script>
<script type="text/javascript">var gbl_operator = "{$operator}";</script>
<div class="pagetitle">Manage Keyword</div>
<div class="midlemidle">
    <div id="tabMechanism">			
        <b>Choose Operator</b>			
        <div id="tabs">
            <ul id="tabs">
                {foreach from=$operatortab item=operator_item key=operator_key}
                    <li id="tabs"><a href="#tabs-{$operator_item.id}" class="operator testing-1" operator="{$operator_item.id}" >{$operator_item.name}</a></li>
                {/foreach}                                    
            </ul>
            <form name="mechanism-form" id="mechanism-form">
                {foreach from=$operatortab item=operator_item key=operator_key}
                    <div id="tabs-{$operator_item.id}" class="testing-1">
                        <div name="keywordForm" id="keywordForm-{$operator_item.id}">
                            <fieldset class="fieldset2" id="fieldset1-{$operator_item.id}">
                                <legend>Mechanism</legend>
                                    <p>
                                        <input type="button" name="add-keyword" operator="{$operator_item.id}" id="add-new-keyword-{$operator_item.id}" class="button" value="Add New Keyword" />
                                    </p>
                                    <div name="add-keyword-form" id="add-new-keyword-{$operator_item.id}">
                                        <fieldset class="fieldset-new">
                                            <legend> New Keyword </legend>                                                        
                                                <p>
                                                    <label for="txt-new-keyword-{$operator_item.id}">Keyword:</label>
                                                    <input type="text" name="txt-new-keyword-{$operator_item.id}" id="txt-new-keyword-{$operator_item.id}" class="add-new-keyword-{$operator_item.id} new-keyword" operator="{$operator_item.id}" />
                                                </p>
                                                <p>
                                                    <label for="box-custom-handler-{$operator_item.id}">is service custom:</label>
                                                    <input type="checkbox" name="box-custom-handler-{$operator_item.id}" id="box-custom-handler-{$operator_item.id}" class="box-custom-handler-{$operator_item.id} box-custom" operator="{$operator_item.id}" />
                                                    <select name="custom-handler[{$operator_item.id}]" id="custom-handler-{$operator_item.id}" class="custom-handler-list" operator="{$operator_item.id}" >
                                                        <option>Choose Custom</option>
                                                        {foreach from=$serviceCustom item=serviceCustom_item key=serviceCustom_key}
                                                        <option value="{$serviceCustom_item.handler}">{$serviceCustom_item.name}</option>
                                                        {/foreach}
                                                    </select>
                                                </p>
                                                <p>
                                                    <input type="button" name="btnClose-{$operator_item.id}" id="btnClose-{$operator_item.id}" class="button" value="Close" />
                                                    <input type="button" name="btnkeyword-{$operator_item.id}" id="btnKeyword-{$operator_item.id}" class="button" value="Submit" />&nbsp;
                                                </p>
                                        </fieldset>
                                    </div>                    
                                    {$n = 0}
                                    {foreach from=$mechatab item=mechanism_item key=mechanism_key}
                                    {if $mechanism_item.operator_id === $operator_item.id}
                                        {$n = 1}
                                    {/if}
                                    {/foreach}
                                    {if $n == 0}
                                        <script type="text/javascript">
                                        $(document).ready(function() {
                                        $("#fieldset2-{$operator_item.id}").show();
                                        });
                                        </script>                                    
                                    {/if}
                                    <fieldset class="fieldset2" id="fieldset2-{$operator_item.id}">
                                        <legend>Reply</legend>                                       
                                        <div id="tabPattern">                                      
                                            <div id="tabsPat{$operator_item.id}" class ="tabs2">
                                                <ul id="tabs2">
                                                    {foreach from=$mechatab item=mechanism_item key=mechanism_key}
                                                    {if $mechanism_item.operator_id === $operator_item.id}
                                                        <li id="tabs2"><a href="#tabs-{$operator_item.id}-{$mechanism_item.id}" class="pattern testing-2" operator="{$operator_item.id}" mechanism="{$mechanism_item.id}" >{$mechanism_item.pattern}</a><span class='ui-icon ui-icon-close' operator="{$operator_item.id}" mechanism="{$mechanism_item.id}">Remove Tab</span></li>                                                                                    
                                                    {/if}
                                                    {/foreach}
                                                </ul>                                                
                                                {foreach from=$mechatab item=mechanism_item key=mechanism_key}
                                                    {if $mechanism_item.operator_id === $operator_item.id}
                                                        <div id="tabs-{$operator_item.id}-{$mechanism_item.id}" class="testing-2">
                                                            {$mechanism_item.form_tab}
                                                        </div>
                                                    {/if}
                                                {/foreach}
                                        </div>                                                                                                                    
                                    </div>                                        
                                </fieldset>
                            </fieldset>
                        </div>
                    </div>
                {/foreach}
                <div class="cb"></div>
                <p class="submit-area" align="right">
                    <input type="submit" name="submit" id="submit" class="button" value="Submit" />                                        
                </p>
            </form>
        </div>			
    </div>
</div>
{include file="common/tpl_footer.tpl"}
