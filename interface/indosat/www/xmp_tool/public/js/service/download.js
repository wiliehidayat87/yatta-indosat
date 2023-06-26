var gbl_id  = 0,
    method  ='',
    path    ='';

$(document).ready(function(){
   getDownloadPageList('');
   
   $("#search-form").submit(function() {
        getDownloadPageList('');

        return false;
   });
   
   $(".boxtoggle").click(function(){
      window.location.href = base_url + "service/download_add";
   });
   
   $(".pagination ul li a").live("click", function() {
        getDownloadPageList($(this).attr("href"));
        return false;
   });
    
   //limitation
   $("#pageLimit").change(function() {
       getDownloadPageList('');
   });
});

function getDownloadPageList(url) {
    if (url == '')
        url = base_url + "service/download/ajaxGetDownloadPageList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val() + "&search=" + $("#search-field").val(),
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#download-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);

            return false;
        }
    });
}

function editDownload(id){
    gbl_id = id;

    path= base_url + "service/download_edit";
    method = method || "post";
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
  
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", 'id');
    hiddenField.setAttribute("value", gbl_id);

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
    
}

function deleteDownload(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "service/download/ajaxDeleteDownload";

    if (answer) {
        
        $.ajax({
            async: "false",
            data: "id=" + id,
            dataType: "json",
            url: url,
            type: 'POST',
            success: function(data) {
                if (data.status == true) {                    
                    achtungSuccess("Delete Success");
                    getDownloadPageList('');
                }
                else {
                    achtungFailed("Delete Failed");
                }
            }
        });
    }
}


function editDownloadContent(id){
    gbl_id = id;

    path= base_url + "service/download_content";
    method = method || "post";
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
  
    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", 'id');
    hiddenField.setAttribute("value", gbl_id);

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
//    window.location.href = base_url + "service/download_content/index/" + gbl_id;
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