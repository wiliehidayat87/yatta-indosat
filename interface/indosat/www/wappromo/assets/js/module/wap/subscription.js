var gbl_id = 0,
gbl_wap_service='',
gbl_wap_name='',
gbl_adn='',
gbl_mechanism='',
method='',
path='';

$(document).ready(function() {
    getSubscriptionList('');

    $("#search-form").submit(function() {
        getSubscriptionList('');

        return false;
    });

    $("#subscription-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getSubscriptionList($(this).attr("href"));

        return false;
    });
    
    
    //******************
    /*jQuery('#wap-subscription-form').uploadProgress({
        //progressURL: $("#wap-subscription-form").attr('action'),
        //progressURL: base_url + 'wap/subscription/upload_dulu',
        progressURL: url,
        start: function() {
            alert('start');

        },
        success: function(o) {
            alert('sukses');
            jQuery(this).get(0).reset();

            alert("Data Tersimpan ");
        }
    });*/
// *******************/
//- create new service -//


//** upload method
   
});

function getSubscriptionList(url) {
    showLoader();

    if (url == '')
        url = base_url + "wap/subscription/ajaxGetSubscriptionList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#subscription-list-table tbody").html(data.result);
            $("#subscription-list-table tfoot #paging").html(data.paging);
            $("#subscription-list-table tfoot #from").html(data.from);
            $("#subscription-list-table tfoot #to").html(data.to);
            $("#subscription-list-table tfoot #total").html(data.total);

            hideLoader();

            return false;
        }
    });
}

function editSubscription(id) {
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

function deleteSubscription(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "wap/subscription/ajaxDeleteSubscription";

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
                    getSubscriptionList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	
    }
}


/*function ajaxFileUpload() {
    alert('masuk');
    $.ajaxFileUpload ( {
        url: base_url + 'wap/subscription/upload_dulu', 
        secureuri:false,
        fileElementId:'txt-unreg-image',
        dataType: 'json',
        success: function (data) {
            alert('ok');
            alert('Error: ' + data.error + ' - Respons: ' + data.respons)
        },
        error: function (data, status, e) {
            alert('Error: ' + e);
        }
    }
    )
    return true;   
}*/


/*var url = "";
function saveHandler(){
    //var error_flag = 0;
    var error_flag = 0;
    if ($("#txt-wap-name").val() == "") {
        $("#txt-wap-name").addClass("error-field");
        $("#inf-wap-name").addClass("error-font").html("required field");
        $("#txt-wap-name").focus();
        error_flag = 1;
    }
    else {
        $("#txt-wap-name").removeClass("error-field");
        $("#inf-wap-name").removeClass("error-font").html("");
    }
    if ($("#txt-wap-service").val() == "") {
        $("#txt-wap-service").addClass("error-field");
        $("#inf-wap-service").addClass("error-font").html("required field");
        $("#txt-wap-service").focus();
        error_flag = 1;
    }
    else {
        $("#txt-wap-service").removeClass("error-field");
        $("#inf-wap-service").removeClass("error-font").html("");
    }
    if ($("#txt-wap-title").val() == "") {
        $("#txt-wap-title").addClass("error-field");
        $("#inf-wap-title").addClass("error-font").html("required field");
        $("#txt-wap-title").focus();
        error_flag = 1;
    }
    else {
        $("#txt-wap-title").removeClass("error-field");
        $("#inf-wap-title").removeClass("error-font").html("");
    }                                                             
    if ($("#txt-unavailable-text").val() == "") {
        $("#txt-unavailable-text").addClass("error-field");
        $("#inf-unavailable-text").addClass("error-font").html("required field");
        $("#txt-unavailable-text").focus();
        error_flag = 1;
    }
    else {
        $("#txt-unavailable-text").removeClass("error-field");
        $("#inf-unavailable-text").removeClass("error-font").html("");
    }
    if ($("#txt-success-text").val() == "") {
        $("#txt-success-text").addClass("error-field");
        $("#inf-success-text").addClass("error-font").html("required field");
        $("#txt-success-text").focus();
        error_flag = 1;
    }
    else {
        $("#txt-success-text").removeClass("error-field");
        $("#inf-success-text").removeClass("error-font").html("")
    }   
        
    if ($("#txt-homepage").val() == "no" && $("#txt-conf-page").val() == "no" ) {
        $("#txt-conf-page").addClass("error-field");
        $("#inf-conf-page").addClass("error-font").html('Either homepage or confirmation page must set to active');
        $("#txt-conf-page").focus();
        error_flag = 1;
    }
    else {
        $("#txt-conf-page").removeClass("error-field");
        $("#inf-conf-page").removeClass("error-font").html("")
    }   
        
    if ($("#txt-conf-page").val() == "yes" && $("#txt-conf-text").val() == "" ) {
        $("#txt-conf-text").addClass("error-field");
        $("#inf-conf-text").addClass("error-font").html('Confirmation Text cannot empty because confirmation page set to active');
        $("#txt-conf-text").focus();
        error_flag = 1;
    }
    else {
        $("#txt-conf-text").removeClass("error-field");
        $("#inf-conf-text").removeClass("error-font").html("")
    }  
        
    if(error_flag > 0){
        return false;
    }
       

    if ($("#submit").val() == "Save")
        url = base_url + "wap/subscription/ajaxAddNewSubscription";
    else
        url = base_url + "wap/subscription/ajaxUpdateSubscription/" + gbl_id;
//progressURL: $("#wap-subscription-form").attr('action'),    
}*/

