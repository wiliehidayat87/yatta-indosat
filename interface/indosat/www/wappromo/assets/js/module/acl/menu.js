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
