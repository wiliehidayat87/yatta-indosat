{include file="common/tpl_header.tpl"}

<div class="pagetitle">CS Tools</div>
<div class="middletop">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">MO/Mt History</div>
            <div class="search-area">

            </div>
            <div class="clear"></div>
        </div>
        <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form id="history" method='post'>
                <table cellspacing="2">
                    <tr>
                        <td width="40%" style="text-align: right;">ADN :</td>
                        <td>
                            <select id="adn" name="adn">
                                <option value="">-- ADN --</option>
                                {foreach from=$adn item=adn_item key=adn_key}
                                    <option value="{$adn_item->adn}">{$adn_item->adn}</option>
                                {/foreach}									
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: right;">MSISDN :</td>
                        <td>
                            <input type='text' name='msisdn' id='msisdn'>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: right;">Message Data :</td>
                        <td>
                            <input type='text' name='msgdata' id='msgdata'>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="text-align: right;">Operator:</td>
                        <td>
                            <select id="operator" name="operator">
                                <option value="">-- operator --</option>
                                {foreach from=$operator item=operator_item key=operator_key}
                                    <option value="{$operator_item->id}">{$operator_item->long_name}</option>
                                {/foreach}	
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <td width="40%" style="text-align: right;">Service:</td>
                        <td>
                            <select id="service" name="service">
                                <option value="">-- service --</option>
                                {foreach from=$service item=service_item key=service_key}
                                    <option value="{$service_item->name}">{$service_item->name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr> 
                    <tr>
                        <td width="40%" style="text-align: right;">Subject:</td>
                        <td>
                            <select id="subject" name="subject">
                                <option value="">-- subject --</option>
                                {foreach from=$subject item=subject_item key=subject_key}
                                    <option value="{$subject_item->SUBJECT}">{$subject_item->SUBJECT}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>   
                    <tr>
                        <td width="40%" style="text-align: right;">Date:</td>
                        <td>
                            <select id="month" name="month">
                                <option value="">-- Month --</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            {assign var=startyear value=$smarty.now|date_format:"%Y"}



                            <select id="year" name="year">
                                <option value="">-- Year --</option>
                                {section name="year" loop=-6 start=$startyear}

                                    <option>{$smarty.section.year.index+$startyear}</option>
                                {/section}
                            </select>
                        </td>
                    </tr>   
                    <tr>
                        <td>&nbsp;</td>
                        <td><button type="button" id="submit">Submit</button> <button type="reset" id="cancel">Cancel</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="midlemidle">
    <div class="boxheader reporttable">
        <table id="historyTable" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Adn</th>
                    <th>Msisdn</th>
                    <th>Operator</th>
                    <th>Service</th>
                    <th>Message</th>
                    <th nowrap>Last Status</th>
                    <th nowrap>Msg Status</th>
                    <th nowrap>Close Reason</th>
                    <th>Price</th>
                    <th>Subject</th>
                    <th>Date</th>
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
            {foreach from=$pageLimit item=item key=key}
                <option value="{$item}">{$item}</option>
            {/foreach}
        </select>
    </div>
</div>

{include file="common/tpl_footer.tpl"}
