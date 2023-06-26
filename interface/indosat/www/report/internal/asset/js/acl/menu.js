var gbl_id  = "0",
    gbl_menu_compare = "",
    gbl_sort = "";

$(document).ready(function() {
    getMenuList('');

    $("#search-form").submit(function() {
        getMenuList('');

        return false;
    });

    $("#menu-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getMenuList($(this).attr("href"));

        return false;
    });
    
    //-open form--//
    $("#btnOpenPanel").click(function() {
        resetForm("menu-form");
        $("#save").val("Save");
		$("#loadContainer").slideToggle("slow");
        $("#txt-menu-name").focus();
        $("#sort").hide();
    });

    //-close form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        $("#sort").hide();
        resetForm("menu-form");
    });        
       
    //- create new controller -//
    $("#menu-form").submit(function() {        
        showFormLoader();
        
            var getData  = "txt-menu-name=" + $("#txt-menu-name").val();
                getData += "&txt-parent=" + $("#txt-parent").val();
                getData += "&txt-link=" + $("#txt-link").val();
                getData += "&txt-status=" + $("#txt-status").val();
                getData += "&txt-sort=" + $("#txt-sort").val();
                getData += "&txt-menu-name-compare=" + gbl_menu_compare;
                getData += "&txt-sort-old=" + gbl_sort;
                
            var url = "";

            if ($("#save").val() == "Save"){
                url = base_url + "acl/menu/ajaxSaveMenu"}
            else
                url = base_url + "acl/menu/ajaxUpdateMenu/" + gbl_id;

            $.ajax({
                async: "false",
                data: getData,
                dataType: "json",
                url: url,
                type: 'POST',
                success: function(data) {
                        if (data.status == true) {
                            resetForm("menu-form");
                            $("#save").val("Save");
                            getMenuList('');
                        }
                        else {
                            if (data.status_menu_name == false) {
                            $("#txt-menu-name").addClass("error-field");
                            $("#inf-menu-name").addClass("error-font").html(data.msg_menu_name);
                            $("#txt-menu-name").focus();
                        }
                        else {
                            $("#txt-menu-name").removeClass("error-field");
                            $("#inf-menu-name").removeClass("error-font").html("");
                        }

                        if (data.status_link == false) {
                            $("#txt-link").addClass("error-field");
                            $("#inf-link").addClass("error-font").html(data.msg_link);
                            $("#txt-link").focus();
                        }
                        else {
                            $("#txt-link").removeClass("error-field");
                            $("#inf-link").removeClass("error-font").html("");
                        }
                        if (data.status_sort == false) {
                            $("#txt-sort").addClass("error-field");
                            $("#inf-sort").addClass("error-font").html(data.msg_sort);
                            $("#txt-sort").focus();
                        }
                        else {
                            $("#txt-sort").removeClass("error-field");
                            $("#inf-sort").removeClass("error-font").html("");
                        }
                    }

                    hideFormLoader();
                    enabledForm("menu-form");
                    $("#txt-menu-name").focus();

                    return false;
                }
            });

        hideFormLoader();
        enabledForm("menu-form");
        $("#txt-menu-name").focus();

        return false;
    });
});

function getMenuList(url) {
    if (url == '')
        url = base_url + "acl/menu/ajaxGetMenuList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#menu-list-table tbody").html(data.result);
            $("#menu-list-table tfoot #paging").html(data.paging);
            $("#menu-list-table tfoot #from").html(data.from);
            $("#menu-list-table tfoot #to").html(data.to);
            $("#menu-list-table tfoot #total").html(data.total);

            return false;
        }
    });
}

function editMenu(id) {
    gbl_id = id;
    
    disabledForm("menu-form");
    resetForm("menu-form");
    $("#save").val("Update");
    $("#loadContainer").slideDown();
    $("#sort").show();

    showFormLoader();
    
    url = base_url + "acl/menu/ajaxEditMenu";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-menu-name").val(data.menu_name);
            $("#txt-parent").val(data.parent);
            $("#txt-link").val(data.link);
            $("#txt-sort").val(data.sort);
            $("#txt-status").val(data.status);
            gbl_menu_compare=data.menu_name;
            gbl_sort=data.sort;
            
            enabledForm("menu-form");
            hideFormLoader();
            
            return false;
        }
    });
}

function deleteMenu(id) {
    gbl_id = id;    

    var answer = confirm("Are you sure?");

    url = base_url + "acl/menu/ajaxDeleteMenu";

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
                    getMenuList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	
    }
}
