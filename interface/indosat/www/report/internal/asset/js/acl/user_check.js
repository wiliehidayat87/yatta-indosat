var gbl_id = 0;

$(document).ready(function() {
    getTestNumberList('');

    $("#search-form").submit(function() {
        getTestNumberList('');

        return false;
    });

    $("#test-number-list-table tfoot #paging a").live("click", function() {
        //window.location.hash = $(this).attr("href");
        getTestNumberList($(this).attr("href"));

        return false;
    });

    //-open form--//
    $("#btnOpenPanel").click(function() {
        resetForm("test-number-form");
        //$("#save").val("Save");
        $("#loadContainer").slideDown();
        $("#txt-msisdn").focus();
    });

    //-close form--//
    $("#btnClosePanel").click(function() {
        $("#loadContainer").slideUp();
        resetForm("test-number-form");
    });

    //- add new test number -//
    $("#test-number-form").submit(function() {
        disabledForm("test-number-form");
        showFormLoader();

                
        var url = "";

        if ($("#save").val() == "Add")
            url = base_url + "users/test_number/ajaxAddTestNumber";
        else
            url = base_url + "users/test_number/ajaxUpdateTestNumber" + gbl_id;

        $.ajax({
            async: "false",
            data: "msisdn=" + $("#txt-msisdn").val(),
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    resetForm("test-number-form");
                    getTestNumberList('');
                }
                else {
                    $("#txt-msisdn").addClass("error-field");
                    $("#inf-msisdn").addClass("error-font").html(data.message);
                }

                hideFormLoader();
                enabledForm("test-number-form");
                $("#txt-msisdn").focus();

                return false;
            }
        });

        return false;
    });
});

function getTestNumberList(url) {
    showLoader();

    if (url == '')
        url = base_url + "users/test_number/ajaxGetTestNumberList";

    $.ajax({
        async: "false",
        data: "search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#test-number-list-table tbody").html(data.result);
            $("#test-number-list-table tfoot #paging").html(data.paging);
            $("#test-number-list-table tfoot #from").html(data.from);
            $("#test-number-list-table tfoot #to").html(data.to);
            $("#test-number-list-table tfoot #total").html(data.total);

            hideLoader();

            return false;
        }
    });
}

function deleteTestNumber(msisdn) {
    var answer = confirm("Are you sure?");

    var url = base_url + "users/test_number/ajaxDeleteTestNumber";

	if (answer) {
        showLoader();
        
		$.ajax({
            async: "false",
            data: "msisdn=" + msisdn,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {
                    $("#btnClosePanel").trigger("click");
                    
                    getTestNumberList('');
                }
                else {
                    alert(data.message);
                }
            }
        });
	}
}