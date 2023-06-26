var gbl_id = 0,
    gbl_username = '';

$(document).ready(function() {
    getUserList('');
	
    $("#search-form").submit(function(){
	getUserList('');

	return false;
    });

    $("#user-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getUserList($(this).attr("href"));

        return false;
    });
    
    //-Open Form--//
    $("#btnOpenPanel").click(function() {
        resetForm("user-form");
        $("#save").val("Save");
        $("#loadContainer").slideDown();
        $("#txt-username").focus();
    });

    //-Close Form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        resetForm("user-form");
    });
    
    //- Add New User -//
    $("#user-form").submit(function() {
        disabledForm("user-form");
        showFormLoader();
    
        var datapost  = "txt-username=" + $("#txt-username").val();
            datapost += "&txt-password=" + $("#txt-password").val();
            datapost += "&txt-confirmpass=" + $("#txt-confirmpass").val();
            datapost += "&txt-group=" + $("#txt-group").val();
            datapost += "&txt-username-compare=" + gbl_username;
            
        var url = "";
        
        if ($("#save").val() == "Save")
            url = base_url + "acl/user/ajaxAddNewUser";
        else
            url = base_url + "acl/user/ajaxUpdateUser/" + gbl_id;
            
        $.ajax({
            async: "false",
            data: datapost,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("user-form");
                    $("#save").val("Save");

                    getUserList('');
                }
                else {
                    //username
                    if (data.status_username == false) {
                        $("#txt-username").addClass("error-field");
                        $("#inf-username").addClass("error-font").html(data.msg_username);
                    }
                    else {
                        $("#txt-username").removeClass("error-field");
                        $("#inf-username").removeClass("error-font").html("");
                    }
                    if (data.status_password == false) {
                        $("#txt-password").addClass("error-field");
                        $("#inf-password").addClass("error-font").html(data.msg_password);
                    }
                    else {
                        $("#txt-password").removeClass("error-field");
                        $("#inf-password").removeClass("error-font").html("");
                    }
                    if (data.status_confirmpass == false) {
                        $("#txt-confirmpass").addClass("error-field");
                        $("#inf-confirmpass").addClass("error-font").html(data.msg_confirmpass);
                    }
                    else {
                        $("#txt-confirmpass").removeClass("error-field");
                        $("#inf-confirmpass").removeClass("error-font").html("");
                    }
                }
                
                hideFormLoader();
                enabledForm("user-form");
                $("#txt-username").focus();

                return false;
            }
        });
                
        return false;
    });
});

function getUserList(url) {
    showLoader();
    
    if (url == '')
        url = base_url + "acl/user/ajaxGetUserList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#user-list-table tbody").html(data.result);
            $("#user-list-table tfoot #paging").html(data.paging);
            $("#user-list-table tfoot #from").html(data.from);
            $("#user-list-table tfoot #to").html(data.to);
            $("#user-list-table tfoot #total").html(data.total);
            
            hideLoader();
            
            return false;
        }
    });
}

function editUser(id) {
    gbl_id = id;
    
    disabledForm("user-form");
    resetForm("user-form");
    $("#save").val("Update");
    $("#loadContainer").slideDown();

    showFormLoader();
    
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

            $("#txt-username").focus();

            hideFormLoader();
            enabledForm("user-form");

            return false;
        }
    });
}

function deleteUser(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/user/ajaxDeleteUser";

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
                    
                    getUserList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	}
}