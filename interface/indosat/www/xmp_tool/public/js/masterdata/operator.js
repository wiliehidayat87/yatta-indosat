var gbl_id = 0,
gbl_operator_name='',
gbl_operator_long_name='';

$(document).ready(function() {
    getOperatorList('');

    $("#search-form").submit(function() {
        getOperatorList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getOperatorList($(this).attr("href"));
        return false;
    });
     
    //-form--//
    $(".boxtoggle").click(function() {
        resetForm("operator-form");
        $("#save").val("Save");
        $("#txt-operator-name").focus();
    });

    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("operator-form");;
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getOperatorList('');
    });          
     
    //- create new operator -//
    $("#operator-form").submit(function() {
        disabledForm("operator-form");
        
        var datapost  = "txt-operator-name=" + $("#txt-operator-name").val();
            datapost += "&txt-operator-long-name=" + $("#txt-operator-long-name").val();
            datapost += "&operator-name-compare=" + gbl_operator_name ;
            datapost += "&operator-long-name-compare=" + gbl_operator_long_name;
                        
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "masterdata/operator/ajaxAddOperator";
            submitMessage="Add Data Success";            
		}
        else{
            url = base_url + "masterdata/operator/ajaxUpdateOperator/" + gbl_id ;
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
                    resetForm("operator-form");
                    achtungSuccess(submitMessage);
                    getOperatorList('');
                }
                else {
                    //username
                    if (data.status_operator_name == false) {
						achtungFailed(data.msg_operator_name)
                        $("#txt-operator-name").addClass("error-field");
                    }
                    else {
                        $("#txt-operator-name").removeClass("error-field");
                    }
                    if (data.status_operator_long_name == false) {
						achtungFailed(data.msg_operator_long_name)
                        $("#txt-operator-long-name").addClass("error-field");
                    }
                    else {
                        $("#txt-operator-long-name").removeClass("error-field");
                    }
                }

                enabledForm("operator-form");
                $("#txt-operator-name").focus();

                return false;
            }
        });

        return false;
    });
    
});

function getOperatorList(url) {
    if (url == '')
        url = base_url + "masterdata/operator/ajaxGetOperatorList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#operator-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
            
            return false;
        }
    });
}

function editOperator(id) {
    gbl_id = id;
   
    resetForm("operator-form");
    $(".boxcontent").slideDown();
    disabledForm("operator-form");
    $("#save").val("Update");
   
    url = base_url + "masterdata/operator/ajaxEditOperator";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-operator-name").val(data.operator_name);
            $("#txt-operator-long-name").val(data.operator_long_name);
            $("#txt-operator-name").focus();
            gbl_operator_name = data.operator_name;
            gbl_operator_long_name = data.operator_long_name;
    
            enabledForm("operator-form");
            
            $("#txt-operator-name").focus();
        }
    });
}

function deleteOperator(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "masterdata/operator/ajaxDeleteOperator/ + gbl_id";
 
    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("operator-form");
					achtungSuccess("Delete Success");
                    getOperatorList('');
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

