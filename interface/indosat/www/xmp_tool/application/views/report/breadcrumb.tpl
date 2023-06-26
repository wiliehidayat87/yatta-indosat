<div id="usercontrol">
    <ul id="breadcrumbs">
    {if isset($breadcrumb)}
        <li>You are here: &nbsp;</li>
        {foreach from=$breadcrumb item=crumb}
            <li>
                {if $crumb.url != ''}
                    <a href="{$crumb.url}" title="Go to {$crumb.title}">
                {/if}
                {$crumb.title}
                {if $crumb.url != ''}
                    </a>
                {/if}
            </li>
        {/foreach}
    {/if}
</ul>
<!--breadcrumbs -->
</div>

