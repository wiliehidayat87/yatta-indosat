var gbl_id 		= 0,
	gbl_name	= '',
	gbl_desc	= '',
	gbl_menu 	= '';
	
$(document).ready(function() {
    getGroupList('');

    $("#search-form").submit(function() {
        getGroupList('');

        return false;
    });

    $("#group-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getGroupList($(this).attr("href"));

        return false;
    });

    //-open form--//
    $("#btnOpenPanel").click(function() {
        resetForm("group-form");
        $("#save").val("Save");
        $("#loadContainer").slideToggle("slow");
        $("#txt-name").focus();
    });
	
    //-close form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        resetForm("group-form");
    });
    
    $('.check-menu').click(function(){
        var_name = $(this).attr("name"); 
	id=var_name.replace(/[a-zA-Z]/g,"");
	
	if(this.checked==true)
            {
		if($("input[name='child" +id+"']").is(':checked'))
                    {
			$("input[name='parent" + id +"']").attr('checked','checked');
                    }
		else
                    {
			$("input[name='child"+id+"']").attr('checked', 'checked');
                    }
            }
	else
            {
		var_name=var_name.replace(/[0-9]/g,"");
		if(var_name=="parent")
                    {
			$("input[name='child"+ id +"']").attr('checked',false);
                    }
		else
                    {
			if($("input[name='child" + id + "']").is(':checked'))
                            {
				$(this).attr('checked',false)
                            }
			else
                            {
				$("input[name='parent" + id +"']").attr('checked',false);
                            }
                    }
            }
    });
       
    //- create new group -//
    $("#group-form").submit(function() {
        disabledForm("group-form");
        showFormLoader();

            var getData  = "txt-name=" + $("#txt-name").val();
                getData += "&txt-desc=" + $("#txt-description").val();
                getData += "&txt-name-compare=" + gbl_name;
                getData += "&txt-desc-compare=" + gbl_desc;
                getData += "&txt-menu-compare=" + gbl_menu;

            var menu     = "";

            for (i = 1; i <= $(".check-menu").size(); i++) {
                if ($("#menu-" + i).is(':checked')) {
                    if (menu != "")
                        menu += ",";

                    menu += $("#menu-" + i).val();
                }
            }

            getData += "&txt-menu=" + menu;
			
			
            var url = "";

            if ($("#save").val() == "Save")
                url = base_url + "acl/group/ajaxAddNewGroup";
            else
                url = base_url + "acl/group/ajaxUpdateGroup/" + gbl_id;

            $.ajax({
                async: "false",
                data: getData,
                dataType: "json",
                url: url,
                type: 'POST',
                success: function(data) {
                    if (data.status == true) {
                        resetForm("group-form");
                        $("#save").val("Save");
                        
                        getGroupList('');
					}
                    else {
						if (data.status_group_name == false) {
							$("#txt-name").addClass("error-field");
							$("#inf-name").addClass("error-font").html(data.msg_group_name);
						}
						else {
							$("#txt-name").removeClass("error-field");
							$("#inf-name").removeClass("error-font").html("");
						}
					}

                    hideFormLoader();
                    enabledForm("group-form");
                    $("#txt-name").focus();

                    return false;
                }
            });

        hideFormLoader();
        enabledForm("group-form");
        $("#txt-name").focus();

        return false;
    });
});

function getGroupList(url) {
    showLoader();

    if (url == '')
        url = base_url + "acl/group/ajaxGetGroupList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#group-list-table tbody").html(data.result);
            $("#group-list-table tfoot #paging").html(data.paging);
            $("#group-list-table tfoot #from").html(data.from);
            $("#group-list-table tfoot #to").html(data.to);
            $("#group-list-table tfoot #total").html(data.total);
			
            hideLoader();

            return false;
        }
    });
}

function editGroup(id) {
    gbl_id = id;
    
    resetForm("group-form");
    $("#save").val("Update");
    $("#loadContainer").slideDown();

    showFormLoader();
    
    url = base_url + "acl/group/ajaxEditGroup";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-name").val(data.name);
            $("#txt-description").val(data.desc);
			gbl_name = 	data.name;
			gbl_desc =	data.desc;
			gbl_menu =	data.menu;
			
            var menuLength = $(".check-menu").size();

            $.each(data.menu, function(index, value) {
                for (i = 1; i <= menuLength; i++) {
                    if ($("#menu-" + i).val() == value)
                        $("#menu-" + i).attr("checked", true);
                }
            });

            $("#txt-name").focus();

            hideFormLoader();
        }
    });
}

function deleteGroup(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "acl/group/ajaxDeleteGroup";

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
                    
                    getGroupList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	}
}
