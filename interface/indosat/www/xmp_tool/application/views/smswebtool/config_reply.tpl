{include file="common/tpl_header.tpl"}

<div class="pagetitle">{$pagetitle}</div>
<div class="midlemidle">
    <div id="maincontent">
        <div id="pageheading"><h2>Message Reply</h2></div>
        <div class="actionbar">
            <form action="{$base_url}smswebtool/config_reply/filter" method="post">
                Service
                <select name="service_filter" id="service_filter">
                  <option value="">--------</option>
                  {html_options values=$svc_ids output=$svc_names selected=$svc_id}
                </select>
                <input type="submit" name="filter" value="filter">
            </form>
	</div>

        {if count($reply_display) gt 0 && $reply_display.0.function != ''}
        <div align="center"><b>Reply Message For {$service_file}</b></div><br />
        <table class="datagrid2" width="100%">
            <tbody>
                <tr>
                    <th width="29%">Function</th>
                    <th width="42%">Message</th>
                    <th width="12%">Price</th>
                    <th width="9%">Length</th>
                    <th width="8%">Tools</th>
                </tr>
                {foreach $reply_display item=i}
                <tr>
                    <td>{$i.function}</td>
                    <td>{$i.message}</td>
                    <td>{$i.value}</td>
                    <td>{$i.length}</td>
                    <td>
                        <a href="{$base_url}smswebtool/config_reply/edit/service/{$service_file}/function/{$i.function_encode}">Edit</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
        {/if}
    </div>
</div>

{include file="common/tpl_footer.tpl"}