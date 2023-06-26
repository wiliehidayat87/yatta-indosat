{include file="common/tpl_header.tpl"}

    <div class="pagetitle">Manage Creator</div>
<form action ="{$base_url}service/add_service/submit" method="post" >
    <div class="middletop">
            <div style="padding:10px; color:#000; background-color:#fff">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">Service Name * </label>
                            </td>
                            <td>
                                <input type="text" class="inputtext-1" id="txt-pattern" name="service_name" size="30" maxlength="50" />
                                &nbsp;<span id="inf-pattern"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <label for="txt-pattern">ADN (ShortCode) * </label>
                            </td>
                            <td>
                                 <select name="adn" id="adn" >
					{foreach from=$adn item=adn_item key=adn_key}
						<option value="{$adn_item.name}">{$adn_item.name}</option>
					{/foreach}
				</select>
                            </td>
                        </tr>

                        <tr>
                            <td width="20%" valign="top">
                                <label for="txt-pattern">Operator * </label>
                            </td>
                            <td>
					{foreach from=$operator item=operator_item key=operator_key}
						<input type="checkbox" value="{$operator_item.name}" name="operator[]">{$operator_item.name}</input><br />
                                        {/foreach}
				</select>
                            </td>
                        </tr>
                  </table>

	</div>
    </div>
    <div class="midlemidle">
<input type="submit" name="submit" value="add keyword" />
		<div class="boxheader reporttable">
            <table id="service-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
                 <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
	</div>
</form>
{include file="common/tpl_footer.tpl"}
