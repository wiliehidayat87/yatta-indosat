$(function() {
    // Toggle form visibility
    $("a#add").live('click', function() {
        $("#loadContainer").toggle();
    });

    $("button#cancel").live('click', function() {
        toggleForm();
    });

    $("select#shortCode").live('change', function() {
        var shortCode = $(this).val();
        var destination = domain + 'api/getOperator';

        loader();
        $.ajax({
            async: false,
            type: 'post',
            data: 'shortCode=' + shortCode,
            dataType: 'json',
            url: destination,
            success: function(result) {
                if ('OK' == result.status) {
                    var operatorList = result.data[1];
                    var htmlCode = '<option value="">-- OPERATOR --</option>';

                    for (var i = 0; i < operatorList.length; i++) {
                        htmlCode += '<option value="' + operatorList[i].id + '">' + operatorList[i].operator + '</option>';
                    }

                    $("select#operator").html(htmlCode);
                }
                else {
                    achtungCreate(result.message, true);
                }
            }
        });
        loaded();
        $("select#operator").focus();
    });

    $("button#submit").live('click', function() {
        var startDate = $("input#startDate").val();
        var endDate = $("input#endDate").val();
        var shortCode = $("select#shortCode").val();
        var operatorId = $("select#operatorId").val();
        var msisdn = $("input#msisdn").val();
        var type = $("select#type").val();
        var subject = $("input#subject").val();
        var request = $("input#request").val();
        var status = $("select#status").val();
        var limit = $("select#limit").val();
        var includeArchive = $("input#archive:checked").val();

        var url = domain + 'traffic/getTrafficReportForTable';
        var parameters = 'startDate=' + startDate + '&endDate=' + endDate + '&shortCode=' + shortCode + '&operatorId=' + operatorId + '&msisdn=' + msisdn + '&type=' + type + '&subject=' + subject+ '&request=' + request + '&status=' + status + '&limit=' + limit + '&includeArchive=' + includeArchive;
        var element = 'trafficTable';
        buildTable(url, parameters, element);
        closeForm();
    });

    $("span.pagination").live('click', function() {
        var startDate = $("input#startDate").val();
        var endDate = $("input#endDate").val();
        var shortCode = $("select#shortCode").val();
        var operatorId = $("select#operatorId").val();
        var msisdn = $("input#msisdn").val();
        var type = $("select#type").val();
        var subject = $("input#subject").val();
        var request = $("input#request").val();
        var status = $("select#status").val();
        var limit = $("select#limit").val();
        var includeArchive = $("input#archive:checked").val();
        var page = this.title;

        var url = domain + 'traffic/getTrafficReportForTable';
        var parameters = 'startDate=' + startDate + '&endDate=' + endDate + '&shortCode=' + shortCode + '&operatorId=' + operatorId + '&msisdn=' + msisdn + '&type=' + type + '&subject=' + subject+ '&request=' + request + '&status=' + status + '&limit=' + limit + '&includeArchive=' + includeArchive + '&page=' + page;
        var element = 'trafficTable';
        buildTable(url, parameters, element);
        closeForm();
    });

    var url = domain + 'traffic/getTrafficReportForTable';
    var parameters = '';
    var element = 'trafficTable';
    buildTable(url, parameters, element);

    $("#startDate").datepicker({dateFormat: 'yy-mm-dd'});
    $("#endDate").datepicker({dateFormat: 'yy-mm-dd'});
});

