var gbl_id = 0,
gbl_pattern='',
gbl_operator='',
gbl_service='',
method='',
path=''
;

$(document).ready(function() {
    getCreatorList('');

    $("#search-form").submit(function() {
        getCreatorList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getCreatorList($(this).attr("href"));

        return false;
    });
 
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("creator-form");
        $("#save").val("Save");
        $("#txt-pattern").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("creator-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getCreatorList('');
    }); 

    //- create new service -//
    $("#creator-form").submit(function() {
        disabledForm("creator-form");
               
        var datapost  = "txt-pattern=" + $("#txt-pattern").val();
        datapost += "&txt-operatorId=" + $("#operatorId").val();
        datapost += "&txt-serviceId=" + $("#serviceId").val();
        datapost += "&pattern_compare=" + gbl_pattern;
        datapost += "&operatorId_compare=" + gbl_operator;
        datapost += "&serviceId_compare=" + gbl_service;
                     
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "service/creator/ajaxAddNewCreator";
            submitMessage="Add Data Success";  
        }
        else{
            url = base_url + "service/creator/ajaxUpdateCreator/" + gbl_id;
            submitMessage="Update Data Success";            
        }
              
        $.ajax({
            async: "false",
            data: datapost,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#save").val("Save");
                    resetForm("creator-form");
                    achtungSuccess(submitMessage);
                    getCreatorList('');
                }
                else {
                    //username
                    if (data.status_pattern == false) {
                        achtungFailed(data.status_pattern)
                        $("#txt-pattern").addClass("error-field");
                    }
                    else {
                        $("#txt-pattern").removeClass("error-field");
                    }
                }
                
                enabledForm("creator-form");
                $("#txt-pattern").focus();

                return false;
            }
        });

        return false;
    });
    
});

function getCreatorList(url) {
    if (url == '')
        url = base_url + "service/creator/ajaxGetCreatorList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#service-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);

            return false;
        }
    });
}

function editCreator(id) {
    gbl_id = id;
   
    resetForm("creator-form");
    $(".boxcontent").slideDown();
    disabledForm("creator-form");
    $("#save").val("Update");
    
    url = base_url + "service/creator/ajaxEditCreator";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-pattern").val(data.pattern);
            $("#operatorId").val(data.operator_id);
            $("#serviceId").val(data.service_id);
            $("#txt-pattern").focus();
            gbl_pattern=data.pattern;
            gbl_operatorId=data.operator_id;
            
            enabledForm("creator-form");
          
        }
    });
}

function achtungSuccess(message){
    $.achtung({
        timeout: 5, // Seconds
        className: 'achtungSuccess',
        icon: 'ui-icon-check',
        message: message
    });
}

function achtungFailed(message){
    $.achtung({
        timeout: 5, // Seconds
        className: 'achtungFail',
        icon: 'ui-icon-check',
        message: message
    });
}

