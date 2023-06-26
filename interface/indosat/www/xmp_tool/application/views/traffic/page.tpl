{include file="common/tpl_header.tpl"}

<div class="pagetitle mo-traffic">MO Traffic</div>
<div class="middletop">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div class="roundedbox bluebox" style="margin-right:15px;">
                    <div class="boxheader"><h2>Today MO</h2></div>
                    <div class="boxcontent">
                        <div class="bignumber">999</div>
                        <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left">Period</td>
                                <td align="right">Total MO</td>
                            </tr>
                            <tr>
                                <td align="left">Yesterday</td>
                                <td align="right">123</td>
                            </tr>
                            <tr class="last">
                                <td align="left">Last 7 days</td>
                                <td align="right">123</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <div class="roundedbox bluebox" style="margin-right:15px;">
                    <div class="boxheader"><h2>MO Trends</h2></div>
                    <div class="boxoption">Option</div>
                    <div class="boxcontent" style="background-color: #FFF">
                        <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
                                codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
                                width="266"
                                height="160" id="graph-2" align="middle">

                            <param name="allowScriptAccess" value="sameDomain" />
                            <param name="movie" value="public/flash/open-flash-chart.swf" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent">
                            <embed src="public/flash/open-flash-chart.swf"
                                   quality="high"
                                   bgcolor="#FFFFFF"
                                   width="276"
                                   height="160"
                                   name="open-flash-chart"
                                   align="middle"
                                   allowScriptAccess="sameDomain"
                                   type="application/x-shockwave-flash"
                                   pluginspage="http://www.macromedia.com/go/getflashplayer"
                                   wmode="transparent" />
                        </object>
                    </div>
                    <div class="boxoption-content">
                        <form>
                            <select>
                                <option selected="selected">All</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                            </select>
                            <br/>
                            <input type="submit" value="Change" style="float: right" />
                        </form>
                    </div>
                </div>
            </td>
            <td>
                <div class="roundedbox bluebox last">
                    <div class="boxheader"><h2>Total MO (Oct)</h2></div>
                    <div class="boxcontent">
                        <div class="bignumber">999</div>
                        <table class="tableinfo" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="left">Period</td>
                                <td align="right">Total MO</td>
                            </tr>
                            <tr>
                                <td align="left">Yesterday</td>
                                <td align="right">123</td>
                            </tr>
                            <tr class="last">
                                <td align="left">Last 7 days</td>
                                <td align="right">123</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="midlemidle">
    <div class="roundedbox bluebox">
        <div class="boxheader">
            <div class="boxtoggle">Open Search Panel</div>
            <div class="searchinfo">
                Date Range : 10/25/2011 - 10/30/2011  |  SCode : 48882481  |  Operator AIS  |  Type : REG
            </div>
            <div class="clear"></div>
        </div>
        <div id="searchBar" class="boxcontent" style="width: auto; min-height:0; padding-bottom: 15px;">
            <form class="searchform" action=""> 
                <div class="daterange">
                    Date Range
                    <table width="325px" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <input type="text" />
                            </td>
                            <td valign="middle">&nbsp; to &nbsp;</td>
                            <td>
                                <input type="text" />
                            </td>
                        </tr>
                    </table>
                </div>
                <table class="searchfilter" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>SCode</td>
                        <td>
                            <select>
                                <option selected="selected">All</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                            </select>
                        </td>
                        <td>Type</td>
                        <td>
                            <select>
                                <option selected="selected">All</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                            </select>
                        </td>
                        <td>MSISDN</td>
                        <td><input type="text" /></td>
                        <td><input name="" type="checkbox" value="" /> Unique MSISDN</td>
                    </tr>   
                    <tr>
                        <td>Operator</td>
                        <td>
                            <select>
                                <option selected="selected">All</option>
                                <option>Option one</option>
                                <option>Option two</option>
                                <option>Option three</option>
                            </select>
                        </td>
                        <td>Services</td>
                        <td><input type="text" /></td>
                        <td>SMS</td>
                        <td><input type="text" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Limit</td>
                        <td>
                            <select>
                                <option selected="selected">20</option>
                                <option>30</option>
                                <option>40</option>
                                <option>All</option>
                            </select>
                        </td>
                        <td><input name="Submit" type="submit" value="Search" /></td>
                        <td><input name="reset" type="reset" value="Reset" /></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="info">
        <div class="lightbluebox sqlquery">
            <div class="icon">
                <div class="infotitle">SQL Query</div>
                SELECT * FROM rpt_mo WHERE mo_date>='2010-06-10 13:31:45' AND mo_date<='2010-06-15 23:59:42' ORDER BY mo_date desc LIMIT 20, 20
            </div>
        </div>
        <div class="lightbluebox">
            <div class="infotitle">Count Result</div>
            <div class="count">13</div>
        </div>
        <div class="lightbluebox" style="margin-right:0">
            <div class="infotitle">Execution Time</div>
            <div class="count">13</div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="middlebottom">
    <div class="export">
        Export as : <button type="button" value="PDF" onclick="">PDF</button> &nbsp; 
        <button type="button" value="XLS" onclick="">XLS</button>
    </div>
    <div class="boxheader reporttable">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th scope="col">MO Date</th>
                <th scope="col">Operator</th>
                <th scope="col">SDC</th>
                <th scope="col">MSIDN</th>
                <th scope="col">Service</th>
                <th scope="col">Type</th>
                <th scope="col" class="last">SMS Request</th>
            </tr>
            <tr>
                <td align="center">2010-06-11 19:45:35</td>
                <td align="left">Mobifone</td>
                <td align="center">0472</td>
                <td align="center">131265357466</td>
                <td align="center">AN</td>
                <td align="center">Pull</td>
                <td align="left" class="last">Hamba Allah</td>
            </tr>
            <tr>
                <td align="center">2010-06-11 19:45:35</td>
                <td align="left">Mobifone</td>
                <td align="center">0472</td>
                <td align="center">131265357466</td>
                <td align="center">AN</td>
                <td align="center">Pull</td>
                <td align="left" class="last">Hamba Allah</td>
            </tr>
            <tr>
                <td align="center">2010-06-11 19:45:35</td>
                <td align="left">Mobifone</td>
                <td align="center">0472</td>
                <td align="center">131265357466</td>
                <td align="center">AN</td>
                <td align="center">Pull</td>
                <td align="left" class="last">Hamba Allah</td>
            </tr>
            <tr>
                <td align="center">2010-06-11 19:45:35</td>
                <td align="left">Mobifone</td>
                <td align="center">0472</td>
                <td align="center">131265357466</td>
                <td align="center">AN</td>
                <td align="center">Pull</td>
                <td align="left" class="last">Hamba Allah</td>
            </tr>
            <tr>
                <td align="center">2010-06-11 19:45:35</td>
                <td align="left">Mobifone</td>
                <td align="center">0472</td>
                <td align="center">131265357466</td>
                <td align="center">AN</td>
                <td align="center">Pull</td>
                <td align="left" class="last">Hamba Allah</td>
            </tr>
        </table>
    </div>
    <div class="pagination">
        <ul>
            <li><a href="">&laquo; Prev</a></li>
            <li><a href="" class="current">1</a></li>
            <li><a href="">2</a></li>
            <li><a href="">3</a></li>
            <li><a href="">4</a></li>
            <li><a href="">5</a></li>
            <li><a href="">6</a></li>
            <li><a href="">Next &raquo;</a></li>
            <div class="clear"></div>
        </ul>
    </div>
    <div class="viewlimit">
        View limit : 
        <select>
            <option selected="selected">20</option>
            <option>30</option>
            <option>40</option>
            <option>All</option>
        </select>
    </div>
</div>

{include file="common/tpl_footer.tpl"}