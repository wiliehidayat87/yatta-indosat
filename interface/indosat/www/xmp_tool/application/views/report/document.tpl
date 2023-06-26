<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		{if isset($meta)}
            {foreach from=$meta item=i}
                {$i}
            {/foreach}
		{/if}

        <title>{$title}</title>
    <script type="text/javascript">var base_url = "{$base_url}";</script>    
    </head>
    <body>
        <div id="blocker" style="background: #fff; display: block; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999;">
            <h1 style="padding: 100px;">Please wait while we prepare the display for the requested page.</h1>
        </div>
        <div id="container">
            {include file="report/header.tpl"}
            {include file="report/content.tpl"}
        </div>
        {include file="report/footer.tpl"}
    </body>
    {include file="report/css_js.tpl"}
</html>

