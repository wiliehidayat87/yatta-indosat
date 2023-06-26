{include file="common/tpl_header.tpl"}

<link href="{$base_url}public/css/crepo.css" rel="stylesheet">

<div class="pagetitle">{$pagetitle}</div>
<div class="midlemidle">


    <div id="content" class="clearfix">
        <div id="maincontent">
		<!-- InstanceBeginEditable name="maincontent" -->
		
		 
		   <a href="index.php?id=MN_1133941864"><b>New Content</b></a>
		   
		   <a href="index.php?id=MN_1132534924">Categories</a>
		   
		   <a href="index.php?id=MN_1132547187">Content Owners</a>
		   
		   <a href="index.php?id=MN_1132547231">Content Type</a>
		   
		   <a href="index.php?id=MN_1132809934">Mobile Handset</a>
		   
		
    
    <script language="JavaScript">
    <!--
    function doSubmit()
    {
        if (document.fnew.id[0].checked) {
            document.location.href='?id='+document.fnew.id[0].value;
        }else{
            document.fnew.submit();
        }
    }
    //-->
    </script><br /><br />
		<div id="pageheading">
			<h2><img src="../asset/images/icon/page-text.png">Create New Content Repository</h2>                
    </div>
		<div class="actionbar">&nbsp;</div>
    <form name=fnew>
		<ul class="twocolumns">
            
            	<li class="col first">
                    <table>
                        <tr>
                            <td width="20" valign="top"><input type="radio" value="PG_1134366978" name="id" checked=""></td>
                            <td>
                                <h4>Default/Normal Content</h4>
                                <p><span class=textInfo>Default content usually only has/need single content file.<br>And commmon data processing will be done.</span></p>
                            </td>
                        </tr> 
                    </table>
                </li>
                
            	<li class="col last">
                    <table>
                        <tr>
                            <td width="20" valign="top">
                                <input type="radio" value="PG_1134368280" name="id">
                            </td>
                            <td>
                                <h4>Custom Content (Packager)</h4>
                                <p><span class=textInfo>Custom content might need more than one file.<br>And usually has custom/unique data processing.<br>You can create the content from the following module :</span><br><select name="mod" size="1"  onclick="document.fnew.id[1].checked=true">
<option value='mod_mms'>MMS</option>
<option value='mod_javacomic'>Java Comic</option>
</select>
 
                            </td>
                        </tr>
                    </table>
                    
                </li>
                
            </ul>
            
            <p><input type="button" value="  OK  " onclick="doSubmit()" class="clsbutton"></p>
    </form>
    
		
		<!-- InstanceEndEditable -->
		
		</div>
        <!--maincontent -->
    </div>
    <!--content -->

</div>

{include file="common/tpl_footer.tpl"}