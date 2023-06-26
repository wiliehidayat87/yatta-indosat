{include file="common/tpl_header.tpl"}

<div class="pagetitle">List Winner</div>
    <div class="midlemidle">
        <div class="info">
            <div class="lightbluebox sqlquery">
                <div class="icon">
                    <div class="infotitle">SQL Query</div>
                    <div id="sqlQuery">{$sqlQuery}</div>
                </div>
            </div>
            <div class="lightbluebox">
                <div class="infotitle">Count Result</div>
                <div class="count" id="countTotal">{$countTotal}</div>
            </div>
            <div class="lightbluebox" style="margin-right:0">
                <div class="infotitle">Execution Time</div>
                <div class="count" id="countTime">{$countTime}</div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="middlebottom">
        <div class="export">             
            <form method="post" action="export_file">            
            Export as : <input type="submit" name="exportPDF" value="PDF" onclick=""> &nbsp;<input type="submit" name="exportXLS" value="XLS">
            <input type="hidden" id="exportMSISDN" name="exportMSISDN" value="">
            <input type="hidden" id="exportMSISDNCheck" name="exportMSISDNCheck" value="">
            </form>
        </div>
        <div class="boxheader reporttable">
            <table id="MOTraffic-list-table" class="datagrid2" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">MSISDN</th>
                        <th scope="col" class="last">Total Hit</th>
                    </tr>
                </thead>
                {$winnerResult}
                <tbody></tbody>
            </table>
        </div>
        <div class="pagination">
            <ul>
                <div class="clear"></div>
            </ul>
        </div>
    </div>	

{include file="common/tpl_footer.tpl"}
