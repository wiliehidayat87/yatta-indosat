var gbl_id  = 0,
    method  ='',
    path    ='';

$(document).ready(function(){
   getDownloadContentList('');
   
   $("#search-form").submit(function() {
        getDownloadContentList('');

        return false;
   });
   
   $(".boxtoggle").click(function(){
//      alert(gbl_d_id);
      
      path= base_url + "service/download_content_add";
      method = method || "post";
      var form = document.createElement("form");
      form.setAttribute("method", method);
      form.setAttribute("action", path);

      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", 'id');
      hiddenField.setAttribute("value", gbl_d_id);

      form.appendChild(hiddenField);

      document.body.appendChild(form);
      form.submit();
      
//      window.location.href = base_url + "service/download_content_add" + "&id=" + gbl_d_id;
   });
   
   $(".pagination ul li a").live("click", function() {
        getDownloadContentList($(this).attr("href"));
        return false;
   });
    
   //limitation
   $("#pageLimit").change(function() {
       getDownloadContentList('');
   });
});

function getDownloadContentList(url) {
    if (url == '')
        url = base_url + "service/download_content/ajaxGetDownloadContentList";

    $.ajax({
        async: "false",
        data: "limit=" + $("#pageLimit").val() + "&search=" + $("#search-field").val() + "&id=" + gbl_d_id,
        dataType: "json",
        url: url,
        type: 'POST',
        success: function(data) {
            $("#content-list-table tbody").html(data.result);
            $(".pagination ul").html(data.paging);

            return false;
        }
    });
}

function editDownloadContent(id){
    gbl_id = id;

    path= base_url + "service/download_content_edit";
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

function deleteDownloadContent(id) {
    gbl_id = id;

    var answer = confirm("Are you sure?");

    url = base_url + "service/download_content/ajaxDeleteDownloadContent";

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
                    getDownloadContentList('');
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