<div id="pageheading">
    <h2>CS Tools</h2>
    {include file=breadcrumb.tpl}
</div>

<div class="actionbar">
    <div class="clearfix" style="font-weight: bold;">
        <a class="btnAdd" id="add"><span>MO / MT History</span></a> 
<!--        <a class="btnAdd" id="showChart"><span>Show Chart</span></a>-->
    </div>

    <div id="loadContainer" style="display:none;">
        <table cellspacing="2">
            <tr>
                <td width="40%" style="text-align: right;">ADN :</td>
                <td>
                    <input type='text' name='adn'>
                </td>
            </tr>
            <tr>
                <td width="40%" style="text-align: right;">MSISDN :</td>
                <td>
                    <input type='text' name='msisdn'>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
            </tr>
        </table>
    </div>
</div>

<div class="warning"></div>
<fieldset style="border:none; border-top:1px solid #000; margin:0px; padding: 0px;">
<div id="chartTable">
    <table border=1 cellspacing=0 cellpadding=2>
		<tr>
			<td>No</td>
			<td>ADN</td>
			<td>MSISDN</td>
			<td>OPERATOR</td>
			<td>SERVICE</td>
			<td>MSG DATA</td>
		</tr>
    </table>
</div>
</fieldset>
<div id="revenueTable"></div>

