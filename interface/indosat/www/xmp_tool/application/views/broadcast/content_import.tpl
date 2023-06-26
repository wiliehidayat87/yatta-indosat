{include file="common/tpl_header.tpl"}

<script language='javascript'>
function doSubmit ()
{ 
    var theForm = document.forms.fcontent;
    var ok  = false;
    for (var i=0; i<theForm.length; i++)
    {
        if (theForm.elements[i].type == 'file')
        {
            if (trim(theForm.elements[i].value)!='')
                ok = true;
        }
    }

    if (!ok)
    {
        alert ('Please select a content file to upload');
        return FALSE;
    }
    var rand_no = Math.random();
    var fwin = 'Popup_Window' + rand_no;
    var w = window.open('{$base_url}broadcast/content/import_progress',fwin,'width=300,height=150,resizeable=0');
    theForm.target = fwin;
    theForm.dopost.value='IMPORTCONTENTFILE';
    theForm.action = '{$base_url}broadcast/content/import_process';
    setTimeout("document.forms.fcontent.submit()",200);
    //document.location.href='index.php?id=PG_1227617312';
    //theForm.submit();
}
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
</script>

<div class="pagetitle">{$pagetitle}</div>
<div class="midlemidle">
    <form name=fcontent action='{$base_url}broadcast/content/import_process' method=post enctype="multipart/form-data">
        <input type=hidden name=id value=''>
        <input type=hidden name=dopost value='import'>
        <input type=hidden name=urlref value='{$base_url}broadcast/content'>
    <table>
        <tr><td>File 1</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>File 2</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>File 3</td><td><input type=file name=contentfile[] ></td></tr>
        <tr><td>&nbsp;</td><td><i>
        <b>The file is text file with following rules:</b> <br>
        - Comma separated<br>
        - Text with space enclosed with double quote <br>
        - Mandatory columns (in order) : SERVICE, AUTHOR, CONTENT, PUBLISHTIME<br>
        
        <b>Example</b> :<br>
        ZODIAK,TEXT1,"Zodiak adalah ramalan bintang","2008-11-10 10:00:00",KB<br>
        ZODIAK,TEXT2,"Zodiak adalah ramalan bintang",0,KB<br>
        ZODIAK,ISATTEXT1,"Zodiak 'adalah' ramalan bintang","",KB<br>
        </i></td></tr>
        <tr><td></td><td><input type="button" value="Import" onClick="javascript:doSubmit()"></td></tr>
    </table>
    </form>
</div>

{include file="common/tpl_footer.tpl"}