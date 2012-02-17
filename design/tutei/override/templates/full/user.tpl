{* Article (main-page) - Full view *}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
    <div class="class-user">

        <div class="attribute-header">
            <h1>{$node.name|wash()}</h1>
        </div>

        <div class="attribute-image">
        {if $node.data_map.image.has_content}
            {attribute_view_gui attribute=$node.data_map.image}
        {else}            
                <img title="{$node.name|wash()}" alt="{$node.name|wash()}" style="border: 0px none;" src={"personal.png"|ezimage}> 
        {/if}
            <div class="attribute-signature">
                {attribute_view_gui attribute=$node.data_map.signature}
            </div>
        </div>

        

        <div class="attribute-relationship">
            {attribute_view_gui attribute=$node.data_map.relationship}
        </div>
		
		<div class="block">
			<h3>Conteúdo</h3>
			{foreach $node.children as $child}
			<p><a href={$child.url_alias|ezurl}>{$child.name}</a></p>
			{/foreach}
		</div>

        </div>
    </div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>