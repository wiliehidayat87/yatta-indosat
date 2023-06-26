<!-- BASE -->
<link rel="stylesheet" type="text/css" href="{$cssPath}base.css" />
<link rel="stylesheet" type="text/css" href="{$cssPath}template.css" />
<link rel="stylesheet" type="text/css" href="{$cssPath}template-dropdown.css" />
<link rel="stylesheet" type="text/css" href="{$cssPath}template-subnavigation.css" />
<link rel="stylesheet" type="text/css" href="{$cssPath}jquery-ui-1.8.2.custom.css" />

<!--[if IE 6]>
<script src="iepngfix.js"></script>
<link rel="stylesheet" href="ie6only.css" />
<link rel="stylesheet" href="ie6only2.css" />
<![endif]-->
<!-- END BASE -->

<!--[if  !IE7]>
{literal}
<style type="text/css">
    #container {display:table;height:100%}
</style>
{/literal}
<![endif]-->

<script type="text/javascript">
    var domain = '{$domain}';
    var imagePath = domain + 'asset/image/';
    {if isset($closeReasonDelimiter)}
        var closeReasonDelimiter = '{$closeReasonDelimiter}';
    {/if}

    {if isset($maxDashboardChart)}
        var maxDashboardChart = '{$maxDashboardChart}';
    {/if}
</script>

<!-- THIRD PARTY -->
<script type="text/javascript" src="{$jsPath}iepngfix.js"></script>
<script type="text/javascript" src="{$jsPath}jquery-1.4.2.min.js"></script>
<!---->

<script type="text/javascript" src="{$jsPath}jquery-ui-1.8.custom/js/jquery-ui-1.8.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="{$jsPath}jquery-ui-1.8.custom/css/jquery-ui-1.8.custom.css" />

<script type="text/javascript" src="{$pluginPath}boxy-0.1.4/javascripts/jquery.boxy.js"></script>
<link rel="stylesheet" type="text/css" href="{$pluginPath}boxy-0.1.4/stylesheets/boxy.css" />

<script type="text/javascript" src="{$pluginPath}achtung-0.3.0/ui.achtung-min.js"></script>
<link rel="stylesheet" type="text/css" href="{$pluginPath}achtung-0.3.0/ui.achtung-min.css" />

<script type="text/javascript" src="{$pluginPath}daterangepicker/js/daterangepicker.jQuery.js"></script>
<link rel="stylesheet" type="text/css" href="{$pluginPath}daterangepicker/css/ui.daterangepicker.css" />
<!-- END THIRD PARTY -->


<!-- BASE -->
<script type="text/javascript" src="{$jsPath}base.js"></script>
<script type="text/javascript" src="{$jsPath}base_achtung.js"></script>
<script type="text/javascript" src="{$jsPath}base_loader.js"></script>

{if (isset($jsFile))}
    {if is_array($jsFile)}
        {foreach from=$jsFile item=js}
            <script type="text/javascript" src="{$jsPath}{$js}"></script>
    {/foreach}
    {else}
        <script type="text/javascript" src="{$jsPath}{$jsFile}"></script>
    {/if}
{/if}

{if isset($jsScript)}
    {if is_array($jsScript)}
        {foreach from=$jsScript item=js}
            {$js}
        {/foreach}
    {else}
        {$jsScript}
    {/if}
{/if}

<!-- END MCP -->
<link rel="stylesheet" type="text/css" href="{$cssPath}site.css" />

<script type="text/javascript">
    $("#blocker").remove();
</script>

