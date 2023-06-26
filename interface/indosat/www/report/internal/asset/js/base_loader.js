function createLoaderLayer(){
    $("body").append('<div id="fxLoader" align="center" style="display:none;"><div id="boxLoader"><table><tr><td width="24"><img src="' + imagePath + 'ajax-loader.gif"/></td><td style="padding: 3px 0 0 10px;">Processing</td></tr></table></div></div>');
}

function destroyLoaderLayer(){
    $("#fxLoader").remove();
}

if( $("#fxLoader").size() == 0 ){
    createLoaderLayer();
}

function loader(){
    $("#fxLoader").css({
        'position':'fixed',
        'top':0,
        'width':'100%',
        'height':'100%',
        'border-top': '4px solid #007dc3'
    });
    $("#boxLoader").css({
        'background': '#007dc3',
        'padding': '4px 0',
        'width': '200px',
        'color': '#FFFFFF',
        'font-weight': 'bold'
    })
    .addClass('ui-corner-bl')
    .addClass('ui-corner-br');

    $("#fxLoader").show();
}

function loaded(){
    $("#fxLoader").fadeOut();
}

