{include file="common/tpl_header.tpl"}

<div class="pagetitle">PIN Control</div>
<div class="middletop">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">Add Schedule</div>
            <div class="search-area">
                <form name="search-form" id="search-form">
                    <input type="text" name="search-field" id="search-field" class="search-field" />
                    <input type="submit" name="search-button" id="search-button" class="search-button" value="&nbsp;" />
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form name="pin-control-form" id="pin-control-form"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr valign="top">
                        <td width="30%">
                            <label for="txt-operator">Operator *</label>
                        </td>
                        <td width="70%">
                            {$operator}
                            &nbsp;<span id="inf-operator"></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">
                            <label for="txt-name">Name *</label>
                        </td>
                        <td width="70%">
                            <input type="text" name="txt-name" id="txt-name" class="inputtext-1" />
                            &nbsp;<span id="inf-name"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="txt-desc">Description</label>
                        </td>
                        <td>
                            <input type="text" name="txt-desc" id="txt-desc" class="inputtext-1" />
                            &nbsp;<span id="inf-desc"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="txt-active"></label>
                        </td>
                        <td>
                            <input type="checkbox" name="txt-active" id="txt-active" class="inputtext-1" /> Is Active
                            <table>
                                <tr>
                                    <td vlign='top'>Mon</td>
                                    <td>From <span><select name="txt-mon-h-start" id="txt-mon-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-mon-m-start" id="txt-mon-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-mon-h-end" id="txt-mon-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-mon-m-end" id="txt-mon-m-end">
                                            {$minuteEnd}
                                        </select>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tue</td>
                                    <td>From <select name="txt-tue-h-start" id="txt-tue-h-start">
                                            {$hourStart}<option></option>
                                        </select>
                                        <select name="txt-tue-m-start" id="txt-tue-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-tue-h-end" id="txt-tue-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-tue-m-end" id="txt-tue-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wed</td>
                                    <td>From <select name="txt-wed-h-start" id="txt-wed-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-wed-m-start" id="txt-wed-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-wed-h-end" id="txt-wed-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-wed-m-end" id="txt-wed-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Thu</td>
                                    <td>From <select name="txt-thu-h-start" id="txt-thu-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-thu-m-start" id="txt-thu-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-thu-h-end" id="txt-thu-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-thu-m-end" id="txt-thu-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fri</td>
                                    <td>From <select name="txt-fri-h-start" id="txt-fri-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-fri-m-start" id="txt-fri-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-fri-h-end" id="txt-fri-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-fri-m-end" id="txt-fri-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sat</td>
                                    <td>From <select name="txt-sat-h-start" id="txt-sat-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-sat-m-start" id="txt-sat-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-sat-h-end" id="txt-sat-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-sat-m-end" id="txt-sat-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sun</td>
                                    <td>From <select name="txt-sun-h-start" id="txt-sun-h-start">
                                            {$hourStart}
                                        </select>
                                        <select name="txt-sun-m-start" id="txt-sun-m-start">
                                            {$minuteStart}
                                        </select>
                                        To
                                        <select name="txt-sun-h-end" id="txt-sun-h-end">
                                            {$hourEnd}
                                        </select>
                                        <select name="txt-sun-m-end" id="txt-sun-m-end">
                                            {$minuteEnd}
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>
                <input type="submit" name="save" id="save" class="button" value="Save" />&nbsp;
                <input type="button" name="btnResetPanel" id="btnResetPanel" class="button" value="Reset" />
            </form>
        </div>
    </div>
</div>
<div class="midlemidle">
    <div class="boxheader reporttable">
        <table id="pin-control-list-table" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Operator</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                    <th>Sun</th>
                    <th nowarp>Action</th>
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
