var gbl_id = 0;

$(document).ready(function() {
    getChargingList('');

    $("#search-form").submit(function() {
        getChargingList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getChargingList($(this).attr("href"));

        return false;
    });
    
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("charging-form");
        $("#save").val("Save");
        $("#txt-operator").focus();
       enabledForm("charging-form");

    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("charging-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getChargingList('');
    });          
    
    //- create new charging -//
    $("#charging-form").submit(function() {
        disabledForm("charging-form");

        var getData  = "txt-operator=" + $("#txt-operator").val();
        getData += "&txt-adn=" + $("#txt-adn").val();
        getData += "&txt-charging-id=" + $("#txt-charging-id").val();
        getData += "&txt-gross=" + $("#txt-gross").val();
        getData += "&txt-netto=" + $("#txt-netto").val();
        getData += "&txt-username=" + $("#txt-username").val();
        getData += "&txt-password=" + $("#txt-password").val();
        getData += "&txt-sender-type=" + $("#txt-sender-type").val();
        getData += "&txt-message-type=" + $("#txt-message-type").val();

        var url = "";

        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/charging/ajaxSaveCharging";
            submitMessage="Add Data Success";
		}
        else{
            url = base_url + "masterdata/charging/ajaxUpdateCharging/" + gbl_id;
            submitMessage="Update Data Success";
		}
		
        $.ajax({
            async: "false",
            data: getData,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("charging-form");
                    achtungSuccess(submitMessage);
                    $("#save").val("Save");
                    getChargingList('');
                }
                else {
                    if (data.status_operator == false) {
						achtungFailed(data.msg_operator)
                        $("#txt-operator").addClass("error-field");
						$("#txt-operator").focus();
                    }
                    else {
                        $("#txt-operator").removeClass("error-field");
                    }
                    if (data.status_adn == false) {
						achtungFailed(data.msg_adn)
                        $("#txt-adn").addClass("error-field");
                        $("#txt-adn").focus();
                    }
                    else {
                        $("#txt-adn").removeClass("error-field");
                    }                     
                    if (data.status_charging_id == false) {
						achtungFailed(data.msg_charging_id)
                        $("#txt-charging-id").addClass("error-field");
                        $("#txt-charging-id").focus();
                    }
                    else {
                        $("#txt-charging-id").removeClass("error-field");
                    }
                    if (data.status_gross == false) {
						achtungFailed(data.msg_gross)
                        $("#txt-gross").addClass("error-field");
                        $("#txt-gross").focus();
                    }
                    else {
                        $("#txt-gross").removeClass("error-field");
                    }                    
                    if (data.status_netto == false) {
						achtungFailed(data.msg_netto)
                        $("#txt-netto").addClass("error-field");
                        $("#txt-netto").focus();
                    }
                    else {
                        $("#txt-netto").removeClass("error-field");
                    }
                    if (data.status_username == false) {
						achtungFailed(data.msg_username)
                        $("#txt-username").addClass("error-field");
                        $("#txt-username").focus();
                    }
                    else {
                        $("#txt-username").removeClass("error-field");
                    }
                    if (data.status_password == false) {
						achtungFailed(data.msg_password)
                        $("#txt-password").addClass("error-field");
						$("#txt-password").focus();
                    }
                    else {
                        $("#txt-password").removeClass("error-field");
                    }
                    if (data.status_sender_type == false) {
						achtungFailed(data.msg_sender_type)
                        $("#txt-sender-type").addClass("error-field");
                        $("#txt-sender-type").focus();
                    }
                    else {
                        $("#txt-sender-type").removeClass("error-field");
                    }
                    if (data.status_message_type == false) {
						achtungFailed(data.msg_message_type)
                        $("#txt-message-type").addClass("error-field");
                        $("#txt-message-type").focus();
                    }
                    else {
                        $("#txt-message-type").removeClass("error-field");
                    }                    
                }

                enabledForm("charging-form");

                return false;
            }
        });
        
        return false;
    });
});

function getChargingList(url) {
    if (url == '')
        url = base_url + "masterdata/charging/ajaxGetChargingList";

    $.ajax({
        async: "false",
		data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#charging-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editCharging(id) {
    gbl_id = id;
    
    resetForm("charging-form");
    $(".boxcontent").slideDown();
    disabledForm("charging-form");
    $("#save").val("Update");
  
    url = base_url + "masterdata/charging/ajaxEditCharging";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-operator").val(data.operator);
            $("#txt-adn").val(data.adn);
            $("#txt-charging-id").val(data.charging_id);
            $("#txt-gross").val(data.gross);
            $("#txt-netto").val(data.netto);
            $("#txt-username").val(data.username);
            $("#txt-password").val(data.password);
            $("#txt-sender-type").val(data.sender_type);
            $("#txt-message-type").val(data.message_type);

            $("#txt-operator").focus();

            enabledForm("charging-form");

            return false;            
        }
    });
}

function deleteCharging(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "masterdata/charging/ajaxDeleteCharging";

    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("charging-form");
					achtungSuccess("Delete Success");
                    getChargingList('');
                }
                else {
                    achtungFailed("Delete Failed");
                }
            }
        });
	
    }
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
