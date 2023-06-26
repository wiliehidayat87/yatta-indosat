var gbl_id = 0,
    gbl_code='',
    gbl_sort=''
    ;

$(document).ready(function() {
    getContentList('');
    
    $("#search-form").submit(function() {
        getContentList('');

        return false;
    });    
    
    $("#content-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getContentList($(this).attr("href"));

        return false;
    });
    //-open form--//
    $("#btnOpenPanel").click(function() {
        resetForm("wap-content-form");
        $("#submit").val("Save");
        $("#loadContainer").slideDown();
        $("#txt-site").removeClass("error-field");
        $("#txt-code").removeClass("error-field");
        $("#sort").hide();
        $("#txt-site").focus();
    });

    //-close form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        $("#txt-site").removeClass("error-field");
        $("#txt-code").removeClass("error-field");
        $("#sort").hide();
        resetForm("wap-content-form");
    });

    //- create new service -//
    $("#wap-content-form").submit(function() {
        disabledForm("wap-content-form");
        showFormLoader();
        
        var datapost  = "txt-site=" + $("#txt-site").val();
            datapost += "&txt-content=" + $("#txt-content").val();
            datapost += "&txt-code=" + $("#txt-code").val();
            datapost += "&txt-price=" + $("#txt-price").val();
            datapost += "&txt-sort=" + $("#txt-sort").val();
            datapost += "&txt-code-compare=" + gbl_code;
            datapost += "&txt-sort-old=" + gbl_sort;
                     
        var url = "";
        
        if ($("#submit").val() == "Save")
            url = base_url + "wap/content/ajaxAddNewContent";
        else
            url = base_url + "wap/content/ajaxUpdateContent/" + gbl_id;
              
        $.ajax({
            async: "false",
            data: datapost,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("wap-content-form");
                    $("#txt-site").removeClass("error-field");
                    $("#txt-code").removeClass("error-field");
                    $("#submit").val("Save");

                    getContentList('');
                }
                else {
                    if (data.status_site == false) {
                        $("#txt-site").addClass("error-field");
                        $("#inf-site").addClass("error-font").html(data.msg_site);
                        $("#txt-site").focus();
                    }
                    else {
                        $("#txt-site").removeClass("error-field");
                        $("#inf-site").removeClass("error-font").html("");
                    }
                    
                    if (data.status_code == false) {
                        $("#txt-code").addClass("error-field");
                        $("#inf-code").addClass("error-font").html(data.msg_code);
                        $("#txt-code").focus();
                    }
                    else {
                        $("#txt-code").removeClass("error-field");
                        $("#inf-code").removeClass("error-font").html("");
                    }                                                             
                    if (data.status_price == false) {
                        $("#txt-price").addClass("error-field");
                        $("#inf-price").addClass("error-font").html(data.msg_price);
                        $("#txt-price").focus();
                    }
                    else {
                        $("#txt-price").removeClass("error-field");
                        $("#inf-price").removeClass("error-font").html("");
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
                enabledForm("wap-content-form");

                return false;
            }
        });
        
        return false;
    });
    
});

function getContentList(url) {
    showLoader();

    if (url == '')
        url = base_url + "wap/content/ajaxGetContentList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#content-list-table tbody").html(data.result);
            $("#content-list-table tfoot #paging").html(data.paging);
            $("#content-list-table tfoot #from").html(data.from);
            $("#content-list-table tfoot #to").html(data.to);
            $("#content-list-table tfoot #total").html(data.total);

            hideLoader();

            return false;
        }
    });
}

function editContent(id) {
    gbl_id = id;
   
    disabledForm("wap-content-form");
    resetForm("wap-content-form");
    $("#submit").val("Update");
    $("#loadContainer").slideDown();
    $("#sort").show();

    showFormLoader();
    
    url = base_url + "wap/content/ajaxEditContent";

    $.ajax({
        async: "false",
        data: "id=" + id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#txt-site").val(data.site);
            $("#txt-code").val(data.code);
            $("#txt-price").val(data.price);
            $("#txt-sort").val(data.sort);
            gbl_code=data.code;
            gbl_sort=data.sort;
            
            enabledForm("wap-content-form");
            hideFormLoader();
            
            return false;
        }
    });
}

function deleteContent(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "wap/content/ajaxDeleteContent";

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
                    getContentList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	
    }
}
