{include file="common/tpl_header.tpl"}

<div id="content" class="clearfix">
    <div id="maincontent">
    <div class="pagetitle mo-traffic">Change Profile</div>
        <form ="" method="post">
        <div class="actionbar">
            <div class="clearfix fl">
                <span>Enter your current password to continue:&nbsp;</span>
                <br />
                <div class="cl">
                <input type="password" name="currentpass" id="currentpass" size=50 />
                </div>
            </div>

            <div id="loadContainer" class="closePanel">
                <!-- -->
            </div>
        </div>

        <fieldset class="fieldset">
        <legend>Edit Profile (Optional)</legend>
        <table cellpadding="0" cellspacing="3" border="0">
				<tr>
					<td>
						<div>First Name:</div>

						<div><input type="text" class="bginput" name="f_name" size="50" maxlength="100" value="{$f_name}" /></div>
					</td>
				</tr>
				<tr>
					<td>
						<div>Last Name:</div>
						<div><input type="text" class="bginput" name="l_name" size="50" maxlength="100" value="{$l_name}" /></div>
					</td>

				</tr>
                                <tr>
					<td>
						<div>Email:</div>
						<div><input type="text" class="bginput" name="email" size="50" maxlength="150" value="{$email}" /></div>
					</td>

				</tr>
                                <tr>
					<td>
						<div>Phone:</div>
						<div><input type="text" class="bginput" name="phone" size="50" maxlength="25" value="{$phone}" /></div>
					</td>

				</tr>
				</table>
		{if $errormessage neq ""}
        <div class="errormsg"><font color=red>{$errormessage}</font></div>
        {else}
        <br>
        {/if}
        </fieldset>
        <div style="margin-top:6px">
			<input type="submit" class="button" value="Save Changes" name="changeprofile"/>

			<input type="reset" class="button" value="Reset Fields" />
		</div>
	</form>
    </div>
</div>

{include file="common/tpl_footer.tpl"}