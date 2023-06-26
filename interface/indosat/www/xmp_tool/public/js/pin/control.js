var gbl_id = 0;

$(document).ready(function() {
    getControlList('');

    $("#search-form").submit(function() {
        getControlList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getControlList($(this).attr("href"));

        return false;
    });
    
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("pin-control-form");
        $("#save").val("Save");
        enabledForm("pin-control-form");

    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("pin-control-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getControlList('');
    });
    
    //- create new charging -//
    $("#pin-control-form").submit(function() {
        disabledForm("pin-control-form");

        var getData  = "txt-operator=" + $("#txt-operator").val();
        getData += "&txt-name=" + $("#txt-name").val();
        getData += "&txt-desc=" + $("#txt-desc").val();
        getData += "&txt-active=" + ($("#txt-active").is(':checked') ? '1' : '0');
        getData += "&txt-mon=" + $("#txt-mon-h-start").val() + ":" + $("#txt-mon-m-start").val() + "-" + $("#txt-mon-h-end").val() + ":" + $("#txt-mon-m-end").val();
        getData += "&txt-tue=" + $("#txt-tue-h-start").val() + ":" + $("#txt-tue-m-start").val() + "-" + $("#txt-tue-h-end").val() + ":" + $("#txt-tue-m-end").val();
        getData += "&txt-wed=" + $("#txt-wed-h-start").val() + ":" + $("#txt-wed-m-start").val() + "-" + $("#txt-wed-h-end").val() + ":" + $("#txt-wed-m-end").val();
        getData += "&txt-thu=" + $("#txt-thu-h-start").val() + ":" + $("#txt-thu-m-start").val() + "-" + $("#txt-thu-h-end").val() + ":" + $("#txt-thu-m-end").val();
        getData += "&txt-fri=" + $("#txt-fri-h-start").val() + ":" + $("#txt-fri-m-start").val() + "-" + $("#txt-fri-h-end").val() + ":" + $("#txt-fri-m-end").val();
        getData += "&txt-sat=" + $("#txt-sat-h-start").val() + ":" + $("#txt-sat-m-start").val() + "-" + $("#txt-sat-h-end").val() + ":" + $("#txt-sat-m-end").val();
        getData += "&txt-sun=" + $("#txt-sun-h-start").val() + ":" + $("#txt-sun-m-start").val() + "-" + $("#txt-sun-h-end").val() + ":" + $("#txt-sun-m-end").val();
        
        var url = "";

        if ($("#save").val() == "Save"){
            url = base_url + "pin/control/ajaxSaveControl";
            submitMessage="Add Data Success";
        }
        else{
            url = base_url + "pin/control/ajaxUpdateControl/" + gbl_id;
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
                    resetForm("pin-control-form");
                    achtungSuccess(submitMessage);
                    $("#save").val("Save");
                    getControlList('');
                } else {
                    if (data.status_operator == false) {
                        achtungFailed(data.msg_operator)
                        $("#txt-operator").addClass("error-field");
                        $("#txt-operator").focus();
                    } else {
                        $("#txt-operator").removeClass("error-field");
                    }
                    if (data.status_name == false) {
                        achtungFailed(data.msg_name)
                        $("#txt-name").addClass("error-field");
                        $("#txt-name").focus();
                    } else {
                        $("#txt-name").removeClass("error-field");
                    }                     
                    if (data.status_desc == false) {
                        achtungFailed(data.msg_desc)
                        $("#txt-desc").addClass("error-field");
                        $("#txt-desc").focus();
                    } else {
                        $("#txt-desc").removeClass("error-field");
                    }             
                    if (data.status_mon == false) {
                        achtungFailed(data.msg_mon)
                        $("#txt-mon").addClass("error-field");
                        $("#txt-mon").focus();
                    } else {
                        $("#txt-mon").removeClass("error-field");
                    }
                    if (data.status_tue == false) {
                        achtungFailed(data.msg_tue)
                        $("#txt-tue").addClass("error-field");
                        $("#txt-tue").focus();
                    } else {
                        $("#txt-tue").removeClass("error-field");
                    }
                    if (data.status_wed == false) {
                        achtungFailed(data.msg_wed)
                        $("#txt-wed").addClass("error-field");
                        $("#txt-wed").focus();
                    } else {
                        $("#txt-wed").removeClass("error-field");
                    }
                    if (data.status_thu == false) {
                        achtungFailed(data.msg_thu)
                        $("#txt-thu").addClass("error-field");
                        $("#txt-thu").focus();
                    } else {
                        $("#txt-thu").removeClass("error-field");
                    }
                    if (data.status_fri == false) {
                        achtungFailed(data.msg_fri)
                        $("#txt-fri").addClass("error-field");
                        $("#txt-fri").focus();
                    } else {
                        $("#txt-fri").removeClass("error-field");
                    }
                    if (data.status_sat == false) {
                        achtungFailed(data.msg_sat)
                        $("#txt-sat").addClass("error-field");
                        $("#txt-sat").focus();
                    } else {
                        $("#txt-sat").removeClass("error-field");
                    }
                    if (data.status_sun == false) {
                        achtungFailed(data.msg_sun)
                        $("#txt-sun").addClass("error-field");
                        $("#txt-sun").focus();
                    } else {
                        $("#txt-sun").removeClass("error-field");
                    }
                }

                enabledForm("pin-control-form");

                return false;
            }
        });
        
        return false;
    });
});

function getControlList(url) {
    if (url == '')
        url = base_url + "pin/control/ajaxGetControlList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#pin-control-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editControl(id) {
    gbl_id = id;
    
    resetForm("pin-control-form");
    $(".boxcontent").slideDown();
    disabledForm("pin-control-form");
    $("#save").val("Update");
  
    url = base_url + "pin/control/ajaxEditControl";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-operator").val(data.operator);
            $("#txt-name").val(data.name);
            $("#txt-desc").val(data.desc);
            (data.active == 1 ? $("#txt-active").attr("checked", true):$("#txt-active").attr("checked", false));
            $("#txt-mon-h-start").val(data.monHStart);
            $("#txt-mon-m-start").val(data.monMStart);
            $("#txt-mon-h-end").val(data.monHEnd);
            $("#txt-mon-m-end").val(data.monMEnd);
            
            $("#txt-tue-h-start").val(data.tueHStart);
            $("#txt-tue-m-start").val(data.tueMStart);
            $("#txt-tue-h-end").val(data.tueHEnd);
            $("#txt-tue-m-end").val(data.tueMEnd);
            
            $("#txt-wed-h-start").val(data.wedHStart);
            $("#txt-wed-m-start").val(data.wedMStart);
            $("#txt-wed-h-end").val(data.wedHEnd);
            $("#txt-wed-m-end").val(data.wedMEnd);
            
            $("#txt-thu-h-start").val(data.thuHStart);
            $("#txt-thu-m-start").val(data.thuMStart);
            $("#txt-thu-h-end").val(data.thuHEnd);
            $("#txt-thu-m-end").val(data.thuMEnd);
            
            $("#txt-fri-h-start").val(data.friHStart);
            $("#txt-fri-m-start").val(data.friMStart);
            $("#txt-fri-h-end").val(data.friHEnd);
            $("#txt-fri-m-end").val(data.friMEnd);
            
            $("#txt-sat-h-start").val(data.satHStart);
            $("#txt-sat-m-start").val(data.satMStart);
            $("#txt-sat-h-end").val(data.satHEnd);
            $("#txt-sat-m-end").val(data.satMEnd);
            
            $("#txt-sun-h-start").val(data.sunHStart);
            $("#txt-sun-m-start").val(data.sunMStart);
            $("#txt-sun-h-end").val(data.sunHEnd);
            $("#txt-sun-m-end").val(data.sunMEnd);

            enabledForm("pin-control-form");

            return false;            
        }
    });
}

function deleteControl(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "pin/control/ajaxDeleteControl";

    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("pin-control-form");
                    achtungSuccess("Delete Success");
                    getControlList('');
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
