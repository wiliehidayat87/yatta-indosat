var gbl_pageURI	= "";
var adn 		= "";
var msisdn 		= "";
var msgdata 	= "";
var operator 	= "";
var service 	= "";
var date		= "";

$(function() {
    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });

    $(".pagination ul li a").live("click", function() {
        getHistoryList($(this).attr("href"));
        return false;
    });
    
    $("#pageLimit").click(function() {
        getHistoryList('');
    }); 

    $("button#cancel").live('click', function() {
        $("#loadContainer").toggle();
    });
    
    $(".boxtoggle").click(function() {
        resetForm("history");
    });
    
    getHistoryList('');
    
    $("button#submit").live('click', function() {
        getHistoryList('');
    //        closeForm();
    });

});

function getHistoryList(url){
    if (url == '')
        url = base_url + "cs/history/getHistoryTable";

    adn = $("select#adn").val();
    msisdn = $("input#msisdn").val();
    msgdata = $("input#msgdata").val();
    operator = $("select#operator").val();
    service = $("select#service").val();
    subject = $("select#subject").val();
        
    if($("select#year").val() != '' || $("select#month").val() != ''){
        date = $("select#year").val() + "-" + $("select#month").val() + "-";
    }
    else { 
        date = '';
    }
    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&adn=" + adn + "&msisdn=" + msisdn + "&msgdata=" + msgdata + "&operator=" + operator + "&service=" + service + "&subject=" + subject + "&date=" + date,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#historyTable tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function closeForm() {
    $(".boxcontent").slideUp();
}

function achtungFailed(message){
    $.achtung({
        timeout: 5, // Seconds
        className: 'achtungFail',
        icon: 'ui-icon-check',
        message: message
    });
}
