{include file="common/tpl_header.tpl"}

<div class="pagetitle">Telco Deployment Tools</div>
<div class="midlemidle">
    <form method="post">
    <table id="Addon-list-table" class="datagrid2" width="400" border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th></th>
                <th align="left">Telco Name</th>
                <th align="left">Status</th>
            </tr>
            {foreach from=$addons_installed key=addonId item=i}
            <tr><td>&nbsp;</td><td>{$i}</td><td>installed</td></tr>
            {/foreach}
            {foreach from=$addons_available key=addonId item=i}
            <tr><td><input type="checkbox" name="cb_addon[]" value="{$i}"></td><td>{$i}</td><td><font color="green">available</font></td></tr>
            {/foreach}
        </thead>
    </table>
    <input type="submit" value="install">
    </form>
</div>
 
{include file="common/tpl_footer.tpl"}