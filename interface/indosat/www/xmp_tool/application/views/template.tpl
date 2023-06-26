<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{block name=pageMeta}<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />{/block}
<title>{block name=pageTitle}XMP Tools{/block}</title>
<script type="text/javascript"> var base_url="{$base_url}"</script>
<script type="text/javascript"> var cont_id="{$id}"</script>
<script type="text/javascript" src="{$base_url}public/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="{$base_url}public/js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="{$base_url}public/js/daterangepicker.jQuery.js"></script>
<script type="text/javascript" src="{$base_url}public/js/ui.achtung.js"></script>
<script type="text/javascript" src="{$base_url}public/js/common.js"></script>
{if (isset($jsFile))}
    {if is_array($jsFile)}
        {foreach from=$jsFile item=js}
            <script type="text/javascript" src="{$base_url}public/js/{$js}"></script>
    {/foreach}
    {else}
        <script type="text/javascript" src="{$base_url}public/js/{$jsFile}"></script>
    {/if}
{/if}

<link rel="stylesheet" href="{$base_url}themes/default/css/style.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}themes/default/css/tablelist.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}public/css/ui.daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}public/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" title="ui-theme" />
<link rel="stylesheet" href="{$base_url}public/css/ui.achtung.css" type="text/css" />
</head>

<body>
<div class="wrapper"> 
	<div class="header">
        <div class="leftlogo"><img src="{$themeUrl}/img/xmp-logo.png" /></div>
        <div class="rightlogo"><img src="{$themeUrl}/img/linkit-logo.png" /></div>
        <div class="menu">
			<ul>
				<li><a href="#">MO Traffic</a></li>
				<li><a href="#">Broadcast</a>
					<ul>
						<li><a href="#">Push Content</a></li>
					</ul>
				</li>
				<li><a href="#">Message Reply</a></li>
				<li><a href="#">Customer Service</a></li>
				<li><a href="#">Quiz</a></li>
				<li><a href="#">Service Creator</a></li>
				<li><a href="#">Reports</a></li>
				<li><a href="#">Administration</a></li>
				<li><a href="#">Settings</a>
                                    <ul>
					<li><a href="{$base_url}acl/changeprofile">Change Profile</a></li>
					<li><a href="{$base_url}acl/changepass">Change Password</a></li>
                                    </ul>
                                </li>
				<li><a href="#">Logout</a></li>
			</ul>			
        </div>
    </div>
    <div class="middle">
    	{block name=pageContent}{/block}
    </div>
    <div id="footer">
        <div class="wrapper">Copyright &copy; 2011 LinkIT 360&deg;. All Rights Reserved.</div>
    </div>
</div>
</body>
</html>
