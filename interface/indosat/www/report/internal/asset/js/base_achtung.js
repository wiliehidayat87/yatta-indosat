var achtungloader = '';

function achtungCreate(message, sticky) {
    var timeout = (true == sticky) ? 0 : 10;
    achtungBox = $.achtung({message: message, timeout: timeout});
    return achtungBox;
}

function achtungClose(achtungBox) {
    achtungbox.achtung('close');
}

function achtungShowLoader() {
    var loader = '<img src="' + domain + 'asset/image/ajax-loader.gif" title="Currently processing your request. This may take a while." alt="Currently processing your request. This may take a while." /><p>Processing your request, please wait.</p>';
    achtungLoader = achtungCreate(loader, true);
}

function achtungHideLoader() {
    achtungLoader.achtung('close');
}

function achtungDestroy(){
	if( $("#achtung-overlay").size() != 0 ) $("#achtung-overlay").remove();
}

