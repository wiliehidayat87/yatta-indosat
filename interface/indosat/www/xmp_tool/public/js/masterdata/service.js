var gbl_id = 0,
    gbl_service_name='',
    gbl_adn='',
    method='',
    path=''
    ;

$(document).ready(function() {
    getServiceList('');

	$("#search-form").submit(function() {
        getServiceList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getServiceList($(this).attr("href"));

        return false;
    });
 
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("service-form");
        $("#save").val("Save");
        $("#txt-service-name").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("service-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getServiceList('');
    }); 

    //- create new service -//
    $("#service-form").submit(function() {
        disabledForm("service-form");
               
        var datapost  = "txt-service-name=" + $("#txt-service-name").val();
            datapost += "&txt-adn=" + $("#txt-adn").val();
            datapost += "&service_name_compare=" + gbl_service_name;
            datapost += "&adn_compare=" + gbl_adn;
                     
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/service/ajaxAddNewService";
			submitMessage="Add Data Success";  
		}
        else{
            url = base_url + "masterdata/service/ajaxUpdateService/" + gbl_id;
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
                    resetForm("service-form");
                    achtungSuccess(submitMessage);
                    getServiceList('');
                }
                else {
                    //username
                    if (data.status_servname == false) {
						achtungFailed(data.msg_servname)
                        $("#txt-service-name").addClass("error-field");
                    }
                    else {
                        $("#txt-service-name").removeClass("error-field");
                    }
                    if (data.status_adn == false) {
						achtungFailed(data.msg_adn)
                        $("#txt-adn").addClass("error-field");
                    }
                    else {
                        $("#txt-adn").removeClass("error-field");
                    }
                }
                
                enabledForm("service-form");
                $("#txt-service-name").focus();

                return false;
            }
        });

        return false;
    });
    
});

function getServiceList(url) {
    if (url == '')
        url = base_url + "masterdata/service/ajaxGetServiceList";

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

function editService(id) {
    gbl_id = id;
   
    resetForm("service-form");
    $(".boxcontent").slideDown();
    disabledForm("service-form");
    $("#save").val("Update");
    
    url = base_url + "masterdata/service/ajaxEditService";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-service-name").val(data.service_name);
            $("#adn").val(data.adn);
            $("#txt-service-name").focus();
            gbl_service_name=data.service_name;
            gbl_adn=data.adn;
            
            enabledForm("service-form");
          
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

