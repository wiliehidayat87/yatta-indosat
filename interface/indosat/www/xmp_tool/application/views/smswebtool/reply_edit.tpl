{include file="common/tpl_header.tpl"}

<div class="pagetitle">{$pagetitle}</div>
<div class="midlemidle">
    <div id="maincontent">
    	<div id="pageheading">
            <h2>Message Reply Edit</h2>                
        </div>
        <font color="red">{$message}</font>
      	<form action="{$base_url}smswebtool/config_reply/edit/service/{$edit_data.service}/function/{$edit_data.function_encode}" method="post">
        <table>
            <tr>
                <td><label for="message">Function</td>
                <td><b> {$edit_data.function}</b></td>
            </tr>
            <tr>
                <td>Message</td>
                <td><textarea name="message" id="message" rows="2" cols="80">{$edit_data.message}</textarea></td>
            </tr>
            <tr>
                <td><label for="message">Price</td>
                <td><input type="text" name="price" id="price" value="{$edit_data.price}" ></td>
            <tr>
                <td></td>
                <td><a href="{$base_url}message/read/code/{$edit_data.service}"><input type="button" name="back" value="Cancel"/></a>
                <input type="submit" name="submit" value="submit"/></td>
            </tr>
      	</table>
        </form>
     </div>
</div>

{include file="common/tpl_footer.tpl"}