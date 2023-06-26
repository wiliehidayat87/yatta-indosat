<script type="text/javascript"> var base_url="{$base_url}";</script>
<script type="text/javascript"> var cont_id="{$id}";</script>
<script type="text/javascript"> var param="{$param}";</script>
<script type="text/javascript"> var service_id="{$service_id}";</script>
<script type="text/javascript"> var operator_id='{$operator}';</script>
<script type="text/javascript" src="{$base_url}public/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="{$base_url}public/js/autocomplete/jquery-1.5.min.js"></script>
<script type="text/javascript" src="{$base_url}public/js/autocomplete/jquery-ui-1.8.16.custom.min.js"></script>
<!--<script type="text/javascript" src="{$base_url}public/js/jquery-ui-1.7.1.custom.min.js"></script>-->
<!--<script type="text/javascript" src="{$base_url}public/js/daterangepicker.jQuery.js"></script>-->
<script type="text/javascript" src="{$base_url}public/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="{$base_url}public/js/ui.achtung.js"></script>
<script type="text/javascript" src="{$base_url}public/js/common.js"></script>
<script type="text/javascript" src="{$base_url}public/js/navigation/ddsmoothmenu.js"></script>
{if (isset($jsFile))}
    {if is_array($jsFile)}
        {foreach from=$jsFile item=js}
            <script type="text/javascript" src="{$base_url}public/js/{$js}"></script>
    {/foreach}
    {else}
        <script type="text/javascript" src="{$base_url}public/js/{$jsFile}"></script>
    {/if}
{/if}

<link rel="stylesheet" href="{$base_url}themes/default/css/tablelist.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}themes/default/css/style.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}public/css/ui.daterangepicker.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}public/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" title="ui-theme" />
<link rel="stylesheet" href="{$base_url}public/css/ui.achtung.css" type="text/css" />
<link rel="stylesheet" href="{$base_url}public/css/jquery-ui-1.8.16.custom.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="{$base_url}themes/default/css/navigation/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="{$base_url}themes/default/css/navigation/ddsmoothmenu-v.css" />
<link rel="stylesheet" href="{$base_url}themes/default/css/service/creator.css" type="text/css" />
<style>
#tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
</style>
<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})

</script>