var gbl_data    = '',
    gbl_search  = 0;

$(document).ready(function() {
    //autocomplete search          
    getBONUSTrafficList('','');  

    $(".pagination ul li a").live("click", function() {
        getBONUSTrafficList($(this).attr("href"),'');

        return false;
    });

    //limitation
    $("#pageLimitBottom").change(function() {
        if (gbl_data == '')
            gbl_data = '';
        else
            var searchData  = "&limitSearch=" + $("#pageLimitBottom").val();
            gbl_data = gbl_data + searchData;
        
        getBONUSTrafficList('', gbl_data);
    });
    
    //date range
    $(".Sessiondate").datetimepicker({
        showSecond: false,
        timeFormat: 'hh:mm',
        dateFormat: 'yy-mm-dd'
    });
    
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("mo-traffic-form");
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("mo-traffic-form");
    });

    //Search Submit
    $("#mo-traffic-form").submit(function() {
        disabledForm("mo-traffic-form");
        var msisdnCheck = "";
        
        if ($("#msisdnCheckbox").is(':checked')){
            msisdnCheck = $("#msisdnCheckbox").val();
        }else{
            msisdnCheck = 0;
        }
        gbl_search = 1;
        
        var searchData  = "dateFrom=" + $("#dateFrom").val();
            searchData += "&dateTo=" + $("#dateTo").val();
            searchData += "&adnNumber=" + $("#adn-list").val();
            searchData += "&operatorName=" + $("#operator-list").val();
            searchData += "&reqType=" + $("#type-list").val();
            searchData += "&serviceName=" + $("#serviceName").val();
            searchData += "&msisdnNumber=" + $("#msisdnInput").val();
            searchData += "&msisdnCheckbox=" + msisdnCheck;
            searchData += "&smsRequest=" + $("#smsRequest").val();            
            searchData += "&limitSearch=" + $("#pageLimit").val();            
            searchData += "&searchParam=" + gbl_search;            

        var url = "";
        if ($("#search").val() == "Search")
            url = base_url + "bonus/tool/ajaxgetBONUSTrafficList";
        
        gbl_data = searchData;
        
        getBONUSTrafficList(url, gbl_data);        
        
        enabledForm("mo-traffic-form");
        return false;
    });
});

function getBONUSTrafficList(url, dataSearch) {
    if (url == '')
        url = base_url + "bonus/tool/ajaxgetBONUSTrafficList";
    
    if (dataSearch == ''){
        var searchData  = "&limit=" + $("#pageLimitBottom").val();
            data = gbl_data + searchData;
    }
    else{
        if (gbl_search == 0)
            data = "&limit=" + $("#pageLimitBottom").val();
        else
            data = dataSearch;
    }
    
    $.ajax({
        async: "false",
        data: data,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            if (data.status == true) {
                $("#MOTraffic-list-table tbody").html(data.result);
                $(".pagination ul").html(data.paging);
                $("#sqlQuery").html(data.query);
                $(".searchinfo").html(data.searchInfo);
                $("#countTotal").html(data.total);
                $("#countTime").html(data.exec_time);
                
                $("#dateFrom").removeClass("error-field");
                $("#dateTo").removeClass("error-field");
                $("#msisdnInput").removeClass("error-field");
                $("#serviceName").removeClass("error-field");
                
                //For Export
                $("#exportFromDate").attr('value', data.fromDate);
                $("#exportUntilDate").attr('value', data.untilDate);
                $("#exportOperator").attr('value', data.operator);
                $("#exportADN").attr('value', data.adn);
                $("#exportMSISDN").attr('value', data.msisdn);
                $("#exportMSISDNCheck").attr('value', data.msisdnCheck);
                $("#exportService").attr('value', data.service);
                $("#exportType").attr('value', data.type);
                $("#exportSMS").attr('value', data.sms);                
            }else {
                if (data.status_checkDate == false) {
                    $("#dateFrom").addClass("error-field");
                    $("#dateTo").addClass("error-field");
                    $.achtung({
                        timeout: 5, // Seconds
                        className: 'achtungFail',
                        icon: 'ui-icon-check',
                        message: 'Date To must be greater or equal than Date From'
                    });
                }
                else {
                    $("#dateFrom").removeClass("error-field");
                    $("#dateTo").removeClass("error-field");
                }
                                                
                if (data.status_msisdnNumber == false) {
                    $("#msisdnInput").addClass("error-field");
                    $.achtung({
                        timeout: 5, // Seconds
                        className: 'achtungFail',
                        icon: 'ui-icon-check',
                        message: 'MSISDN must numeric'
                    });
                }
                else {
                    $("#msisdnInput").removeClass("error-field");
                }
            }
            
            return false;
        }
    });
}
