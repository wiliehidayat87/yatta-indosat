var gbl_id = 0,
gbl_username = '';

$(document).ready(function() {
    getUserList('');
	
    $("#search-form").submit(function(){
        getUserList('');

        return false;
    });

    $(".pagination ul li a").live("click", function() {
        getUserList($(this).attr("href"));
        return false;
    });
        
    //-open form--//
    $(".boxtoggle").click(function() {
        resetForm("user-form");
        $("#save").val("Save");
        $("#txt-user-name").focus();
    });
    
    //-Reset Form--//
    $("#btnResetPanel").click(function() {
        resetForm("user-form");
    });
    
    //limitation
    $("#pageLimit").click(function() {
        getUserList('');
    });
    
    //- Add New User -//
    $("#user-form").submit(function() {
        disabledForm("user-form");
    
        var datapost  = "txt-username=" + $("#txt-username").val();
        datapost += "&txt-password=" + $("#txt-password").val();
        datapost += "&txt-confirmpass=" + $("#txt-confirmpass").val();
        datapost += "&txt-group=" + $("#txt-group").val();
        datapost += "&txt-username-compare=" + gbl_username;
            
        var url = "";
        
        if ($("#save").val() == "Save"){
            url = base_url + "acl/user/ajaxAddNewUser";
            submitMessage="Add Data Success";		
		}
        else{
            url = base_url + "acl/user/ajaxUpdateUser/" + gbl_id;
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
                    resetForm("user-form");
                    achtungSuccess(submitMessage);
                    $("#save").val("Save");
                    getUserList('');
                }
                else {
                    //username
                    if (data.status_username == false) {
						achtungFailed(data.msg_username);
                        $("#txt-username").addClass("error-field");
				    }
                    else {
                        $("#txt-username").removeClass("error-field");
                    }
                    if (data.status_password == false) {
						achtungFailed(data.msg_password);
                        $("#txt-password").addClass("error-field");
                    }
                    else {
                        $("#txt-password").removeClass("error-field");
                    }
                    if (data.status_confirmpass == false) {
						achtungFailed(data.msg_confirmpass);
                        $("#txt-confirmpass").addClass("error-field");
                    }
                    else {
                        $("#txt-confirmpass").removeClass("error-field");
                    }
                }
                
                enabledForm("user-form");
                $("#txt-username").focus();

                return false;
            }
        });
        enabledForm("user-form");
        $("#txt-username").focus();
        
        return false;
    });
});

function getUserList(url) {
    if (url == '')
        url = base_url + "acl/user/ajaxGetUserList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val()+ "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#user-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);
                   
            return false;
        }
    });
}

function editUser(id) {
    gbl_id = id;
    
    resetForm("user-form");
    $(".boxcontent").slideDown();
    disabledForm("user-form");
    $("#save").val("Update");
    
    url = base_url + "acl/user/ajaxEditUser";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-username").val(data.username);
            $("#txt-password").val(data.password);
            $("#txt-confirmpass").val(data.confirmpass);
            $("#txt-group").val(data.group);
            gbl_username = data.username;

            enabledForm("user-form");
            $("#txt-username").focus();
            
            return false;
        }
    });
}

function deleteUser(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/user/ajaxDeleteUser";

    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
					resetForm("user-form");
					achtungSuccess("Delete Success");
                    getUserList('');
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
