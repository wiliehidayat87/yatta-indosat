var gbl_id = 0;

$(document).ready(function() {
    getCustomHandlerList('');

	$("#search-form").submit(function() {
        getCustomHandlerList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getCustomHandlerList($(this).attr("href"));

        return false;
    });

    //limitation
    $("#pageLimit").click(function() {
        getCustomHandlerList('');
    });

    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("custom-handler-form");
        $("#save").val("Save");
        $("#txt-pattern").focus();
        $("#sort").hide();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("custom-handler-form");
    });

    //- create new custom handler -//
    $("#custom-handler-form").submit(function() {
        disabledForm("custom-handler-form");

        var getData  = "txt-operator=" + $("#txt-operator").val();
        getData += "&txt-pattern=" + $("#txt-pattern").val();
        getData += "&txt-service=" + $("#txt-service").val();
        getData += "&txt-handler=" + $("#txt-handler").val();

        var url = "";

        if ($("#save").val() == "Save")
            url = base_url + "service/custom_handler/ajaxSaveCustomHandler";
        else
            url = base_url + "service/custom_handler/ajaxUpdateCustomHandler/" + gbl_id;

        $.ajax({
            async: "false",
            data: getData,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("custom-handler-form");
                    $("#save").val("Save");

                    getCustomHandlerList('');
                } else {
                    if (data.status_pattern == false) {
                        $("#txt-pattern").addClass("error-field");
                        $.achtung({
                            timeout: 5, // Seconds
                                className: 'achtungFail',
                                icon: 'ui-icon-check',
                                message: 'Pattern Name Required'
                        });
                        //$("#inf-pattern").addClass("error-font").html(data.msg_pattern);
                        $("#txt-pattern").focus();
                    } else {
                        $("#txt-pattern").removeClass("error-field");
                        $("#inf-pattern").removeClass("error-font").html("");
                    }
                    if (data.status_operator == false) {
                        $("#txt-operator").addClass("error-field");
                        $("#inf-operator").addClass("error-font").html(data.msg_operator);
                        $("#txt-operator").focus();
                    } else {
                        $("#txt-operator").removeClass("error-field");
                        $("#inf-operator").removeClass("error-font").html("");
                    }
                    if (data.status_service == false) {
                        $("#txt-service").addClass("error-field");
                        $("#inf-service").addClass("error-font").html(data.msg_service);
                        $("#txt-service").focus();
                    } else {
                        $("#txt-service").removeClass("error-field");
                        $("#inf-service").removeClass("error-font").html("");
                    }
                    if (data.status_handler == false) {
                        $("#txt-handler").addClass("error-field");
                        $.achtung({
                            timeout: 5, // Seconds
                                className: 'achtungFail',
                                icon: 'ui-icon-check',
                                message: 'Pattern Name Required'
                        });
                        //$("#inf-handler").addClass("error-font").html(data.msg_handler);
                        $("#txt-handler").focus();
                    } else {
                        $("#txt-handler").removeClass("error-field");
                        $("#inf-handler").removeClass("error-font").html("");
                    }

                }

                enabledForm("custom-handler-form");

                return false;
            }
        });

        return false;
    });

});

function getCustomHandlerList(url) {
    if (url == '')
        url = base_url + "service/custom_handler/ajaxGetCustomHandlerList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val() + "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#custom_handler-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);

            return false;
        }
    });
}

function editCustomHandler(id) {
    gbl_id = id;

    resetForm("custom-handler-form");
    $(".boxcontent").slideDown();
    disabledForm("custom-handler-form");
    $("#save").val("Update");

    url = base_url + "service/custom_handler/ajaxEditCustomHandler";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-pattern").val(data.pattern);
            $("#txt-operator").val(data.operator);
            $("#txt-service").val(data.service);
            $("#txt-handler").val(data.handler);

            $("#txt-operator").focus();
            enabledForm("custom-handler-form");

            return false;
        }
    });
}

function deleteCustomHandler(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "service/custom_handler/ajaxDeleteCustomHandler/" + gbl_id;

    if (answer) {

        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("custom-handler-form");
                    getCustomHandlerList('');
                }
                else {
                    alert(data.message);
                }
            }
        });

    }
}