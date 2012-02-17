{def $width=450}
{def $height=300}
{if $node.data_map.width.has_content}
	{set $height=$node.data_map.width.content}
{/if}
{if $node.data_map.height.has_content}
	{set $height=$node.data_map.height.content}
{/if}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
    <div class="class-unity">

		<div class="attribute-header">
				<h1>{attribute_view_gui attribute=$node.data_map.name}</h1>
		</div>
		
		{section show=$node.object.can_edit}
		  <form method="post" action={"content/action/"|ezurl}>
			  <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
			  <input class="button forum-account-edit" type="submit" name="EditButton" value="{'Edit'|i18n('design/ezwebin/full/forum_topic')}" />
			  <input type="hidden" name="ContentObjectLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
		  </form>
		{/section}
		{section show=$node.object.can_remove}
		  <form method="post" action={"content/action/"|ezurl}>
			  <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
			  <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
			  <input class="button" type="submit" name="ActionRemove" value="{'Remove'|i18n( 'design/ezwebin/full/forum_topic' )}" title="{'Remove this item.'|i18n( 'design/ezwebin/full/forum_topic' )}" />                      </form>
		{/section}
		
		{if $node.data_map.description.content.is_empty|not}
			<div class="attribute-long">
				{attribute_view_gui attribute=$node.data_map.description}
			</div>
		{/if}
		

		<script type="text/javascript" src="http://webplayer.unity3d.com/download_webplayer-3.x/3.0/uo/UnityObject.js"></script>


		
		<div id="unityPlayer">
			<div class="missing">
				<a href="http://unity3d.com/webplayer/" title="Unity Web Player. Install now!">
					<img alt="Unity Web Player. Install now!" src="http://webplayer.unity3d.com/installation/getunity.png" width="193" height="63" />
				</a>
			</div>
		</div>
		
		<script type="text/javascript">
		<!--
		function GetUnity() {ldelim}
		
			if (typeof unityObject != "undefined") {ldelim}
			
				return unityObject.getObjectById("unityPlayer");
				
			{rdelim}
			
			return null;
			
		{rdelim}
		
		if (typeof unityObject != "undefined") {ldelim}
		
			unityObject.embedUnity("unityPlayer", "{concat('http://', ezini('SiteSettings', 'SiteURL', 'site.ini')|explode('/index.php')[0],'/content/download/',$node.data_map.unity.contentobject_id,'/',$node.data_map.unity.id,'/file/',$node.data_map.unity.content.original_filename)}", {$width}, {$height});
			
		{rdelim}
		
		-->
		</script>

		

		<div class="attribute-star-rating">
            {attribute_view_gui attribute=$node.data_map.star_rating}
        </div>

        {include uri='design:parts/related_content.tpl'}

        {if is_unset( $versionview_mode )}
        {if $node.data_map.enable_comments.data_int}
            <h1>{"Comments"|i18n("design/ezwebin/full/article")}</h1>
                <div class="content-view-children">
                    {foreach fetch_alias( comments, hash( parent_node_id, $node.node_id ) ) as $comment}
                        {node_view_gui view='line' content_node=$comment}
                    {/foreach}
                </div>

                {if fetch( 'content', 'access', hash( 'access', 'create',
                                                      'contentobject', $node,
                                                      'contentclass_id', 'comment' ) )}
                    <form method="post" action={"content/action"|ezurl}>
                    <input type="hidden" name="ClassIdentifier" value="comment" />
                    <input type="hidden" name="NodeID" value="{$node.object.main_node.node_id}" />
                    <input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
                    <input class="button new_comment" type="submit" name="NewButton" value="{'New comment'|i18n( 'design/ezwebin/full/article' )}" />
                    </form>
                {else}
                    {if ezmodule( 'user/register' )}
                        <p>{'%login_link_startLog in%login_link_end or %create_link_startcreate a user account%create_link_end to comment.'|i18n( 'design/ezwebin/full/article', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezurl(no), '">' ), '%login_link_end', '</a>', '%create_link_start', concat( '<a href="', "/user/register"|ezurl(no), '">' ), '%create_link_end', '</a>' ) )}</p>
                    {else}
                        <p>{'%login_link_startLog in%login_link_end to comment.'|i18n( 'design/ezwebin/article/comments', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezurl(no), '">' ), '%login_link_end', '</a>' ) )}</p>
                    {/if}
                {/if}
        {/if}
        {/if}

        {def $tipafriend_access=fetch( 'user', 'has_access_to', hash( 'module', 'content',
                                                                      'function', 'tipafriend' ) )}
        {if and( ezmodule( 'content/tipafriend' ), $tipafriend_access )}
        <div class="attribute-tipafriend">
            <p><a href={concat( "/content/tipafriend/", $node.node_id )|ezurl} title="{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}">{'Tip a friend'|i18n( 'design/ezwebin/full/article' )}</a></p>
        </div>
        {/if}
    </div>
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>