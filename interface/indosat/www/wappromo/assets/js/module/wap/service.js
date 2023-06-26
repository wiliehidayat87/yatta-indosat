var gbl_id = 0,
    gbl_wap_service='',
    gbl_wap_name='',
    gbl_adn='',
    gbl_mechanism='',
    method='',
    path=''
    ;

$(document).ready(function() {
    getServiceList('');

	$("#search-form").submit(function() {
        getServiceList('');

        return false;
    });

    $("#service-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getServiceList($(this).attr("href"));

        return false;
    });
    //-open form--//
    $("#btnOpenPanel").click(function() {
        resetForm("wap-service-form");
        $("#submit").val("Save");
        $("#loadContainer").slideDown();
        $("#txt-wap-name").focus();
    });

    //-close form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        resetForm("wap-service-form");
    });

    //- create new service -//
    $("#wap-service-form").submit(function() {
        disabledForm("wap-service-form");
        showFormLoader();
        
        var datapost  = "txt-wap-service=" + $("#txt-wap-service").val();
            datapost += "&txt-wap-name=" + $("#txt-wap-name").val();
            datapost += "&txt-adn=" + $("#txt-adn").val();
            datapost += "&txt-mechanism=" + $("#txt-mechanism").val();
            datapost += "&wap-name-compare=" + gbl_wap_name;
                     
        var url = "";
        
        if ($("#submit").val() == "Save")
            url = base_url + "wap/service/ajaxAddNewService";
        else
            url = base_url + "wap/service/ajaxUpdateService/" + gbl_id;
              
        $.ajax({
            async: "false",
            data: datapost,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("wap-service-form");
                    $("#submit").val("Save");

                    getServiceList('');
                }
                else {
                    if (data.status_wap_service == false) {
                        $("#txt-wap-service").addClass("error-field");
                        $("#inf-wap-service").addClass("error-font").html(data.msg_wap_service);
                        $("#txt-wap-service").focus();
                    }
                    else {
                        $("#txt-wap-service").removeClass("error-field");
                        $("#inf-wap-service").removeClass("error-font").html("");
                    }
                    if (data.status_wap_name == false) {
                        $("#txt-wap-name").addClass("error-field");
                        $("#inf-wap-name").addClass("error-font").html(data.msg_wap_name);
                        $("#txt-wap-name").focus();
                    }
                    else {
                        $("#txt-wap-name").removeClass("error-field");
                        $("#inf-wap-name").removeClass("error-font").html("");
                    }
                    if (data.status_adn == false) {
                        $("#txt-adn").addClass("error-field");
                        $("#inf-adn").addClass("error-font").html(data.msg_adn);
                        $("#txt-adn").focus();
                    }
                    else {
                        $("#txt-adn").removeClass("error-field");
                        $("#inf-adn").removeClass("error-font").html("");
                    }                                                             
                    if (data.status_mechanism == false) {
                        $("#txt-mechanism").addClass("error-field");
                        $("#inf-mechanism").addClass("error-font").html(data.msg_mechanism);
                        $("#txt-mechanism").focus();
                    }
                    else {
                        $("#txt-mechanism").removeClass("error-field");
                        $("#inf-mechanism").removeClass("error-font").html("");
                    }                     
                }

                hideFormLoader();
                enabledForm("wap-service-form");

                return false;
            }
        });
        
        return false;
    });
    
});

function getServiceList(url) {
    showLoader();

    if (url == '')
        url = base_url + "wap/service/ajaxGetServiceList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#service-list-table tbody").html(data.result);
            $("#service-list-table tfoot #paging").html(data.paging);
            $("#service-list-table tfoot #from").html(data.from);
            $("#service-list-table tfoot #to").html(data.to);
            $("#service-list-table tfoot #total").html(data.total);

            hideLoader();

            return false;
        }
    });
}

function editService(id) {
    gbl_id = id;
   
    disabledForm("wap-service-form");
    resetForm("wap-service-form");
    $("#submit").val("Update");
    $("#loadContainer").slideDown();

    showFormLoader();
    
    url = base_url + "wap/service/ajaxEditService";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-wap-service").val(data.wap_service);
            $("#txt-wap-name").val(data.wap_name);
            $("#txt-adn").val(data.adn);
            $("#txt-mechanism").val(data.mechanism);
            $("#txt-wap-service").focus();
            gbl_wap_name=data.wap_name;
            
            enabledForm("wap-service-form");
            hideFormLoader();
            
            return false;
        }
    });
}

function deleteService(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "wap/service/ajaxDeleteService";

    if (answer) {
        showLoader();
        
	$.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#btnClosePanel").trigger("click");
                    getServiceList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	
    }
}
