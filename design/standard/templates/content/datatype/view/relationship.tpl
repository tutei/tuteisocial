{set-block scope=global variable=cache_ttl}0{/set-block}

{def $currentUser=fetch( 'user', 'current_user' )}


{def $page_limit = ezini( 'ProfileSettings', 'UserListLength', 'dappsocial.ini' )
$classes = ezini( 'MenuContentSettings', 'ExtraIdentifierList', 'menu.ini' )
$children_count = $attribute.content}

<div class="relationshiptype_full">

    {if eq($currentUser.contentobject_id,$attribute.contentobject_id)}

        {def $requesters=fetch('social', 'requests', hash('user_id',$attribute.contentobject_id))}
        {if gt(count($requesters),0)}<h3>Requests</h3>{/if}

        {foreach $requesters as $requester}

            {def $object=fetch( 'content', 'object', hash( 'object_id', $requester ) )}
            <div class="invite">
            <form action={"content/action"|ezurl} method="post">
                <a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a> - {get_elaboration($requester,$currentUser.contentobject_id)}
                <div class="options">
                <input name="SocialApprove" type="submit" value="{"Approve"|i18n('extension/dappsocial/actions')}" />
                <input name="SocialDelete" type="submit" value="{"Remove"|i18n('extension/dappsocial/actions')}" />
                <input name="ContentObjectID" type="hidden" value="{$requester}" />
                </div>
            </form>
            </div>
        {/foreach}


    {elseif is_friend($currentUser.contentobject_id,$attribute.contentobject_id)}
        <div class="friend"><strong>Friend</strong></div>
    {elseif is_pending($currentUser.contentobject_id,$attribute.contentobject_id)}
        <div class="pending"><strong>Pending</strong></div>
    {elseif is_pending($attribute.contentobject_id, $currentUser.contentobject_id)}
        <div class="pending">
            <strong>He invited you:</strong>
            <div class="invite">
            <form action={"content/action"|ezurl} method="post">
                {get_elaboration($attribute.contentobject_id,$currentUser.contentobject_id)}
                <div class="options">
                <input name="SocialApprove" type="submit" value="{"Approve"|i18n('extension/dappsocial/actions')}" />
                <input name="SocialDelete" type="submit" value="{"Remove"|i18n('extension/dappsocial/actions')}" />
                <input name="ContentObjectID" type="hidden" value="{$attribute.contentobject_id}" />
                </div>
            </form>
            </div>
        </div>
    {elseif $currentUser.is_logged_in}
            {ezcss_require( 'colorbox.css' )}
            {ezscript_require( array('ezjsc::jquery','jquery.colorbox-min.js', 'dappsocial.js') )}
            <script type="text/javascript">
            <!--
            $(document).ready(
                            function (){ldelim}
                                $(".example8").colorbox({ldelim}width:"50%", inline:true, href:"#inline_example1"{rdelim});
                            {rdelim});

                            //Example of preserving a JavaScript event for inline calls.
                            $("#click").click(function(){ldelim}
                                    $('#click').css({ldelim}"background-color":"#f00", "color":"#fff", "cursor":"inherit"{rdelim}).text("Open this window again and this message will still be here.");
                                    return false;
                            });

                            $(function(){ldelim}
                                limitChars('Elaboration', {ezini( 'RequestSettings', 'ElaborationMaxLength', 'dappsocial.ini' )}, 'charlimitinfo');
                                $('#Elaboration').keyup(function(){ldelim}

                                 limitChars('Elaboration', {ezini( 'RequestSettings', 'ElaborationMaxLength', 'dappsocial.ini' )}, 'charlimitinfo');

                                 {rdelim})

                             {rdelim});

            -->
            </script>
            <div class="add-as-friend">
                <a class='example8' href="#">{"Add as Friend"|i18n('extension/dappsocial/actions')}</a>
            </div>
            <!-- This contains the hidden content for inline calls -->
            <div style='display:none'>
                <div id='inline_example1' style='padding:10px; background:#fff;'>

                    <form action={"content/action"|ezurl} method="post">

                        <p>{"Personal message(optional)"|i18n('extension/dappsocial/actions')}</p>
                        <div>
                             <div id="charlimitinfo"></div>
                            <textarea name="Elaboration" id="Elaboration" rows="2" cols="20">{"Hello, %friendname! Do you want to be my friend? %username"|i18n('extension/dappsocial/actions','',hash('%friendname',$attribute.object.name,'%username',$currentUser.contentobject.name))}</textarea>
                        </div>
                        <div>
                            <input name="SocialRequest" type="submit" value="{"Send"|i18n('extension/dappsocial/actions')}" />
                                   <input name="ContentObjectID" type="hidden" value="{$attribute.contentobject_id}" />
                        </div>
                    </form>

                </div>
            </div>



        {/if}



    <h3>{"Friends (%total)"|i18n('extension/dappsocial/profile','',hash('%total', $attribute.content))}</h3>

    {def $friends=fetch('social', 'list', hash('user_id',$attribute.contentobject_id, 'offset', $#view_parameters.offset, 'limit',$page_limit ))}
    <div class="wall">

    {foreach $friends as $friend}
        {def $object=fetch( 'content', 'object', hash( 'object_id', $friend ) )}


        <div class="friend">
            <p><a href={$object.main_node.url_alias|ezurl} title="{$object.name}">{$object.main_node.data_map.first_name.content|shorten(12)}</a></p>
            <p>
            {if $object.main_node.data_map.image.has_content}
                <a href={$object.main_node.url_alias|ezurl} title="{$object.name}"><img src={$object.main_node.data_map.image.content[socialmini].full_path|ezroot} alt="{$object.name}" /></a>
            {else}
                <a href={$object.main_node.url_alias|ezurl} title="{$object.name}"><img src={"personal.png"|ezimage} alt="{$object.name}" /></a>
            {/if}
            </p>
            {if eq($currentUser.contentobject_id,$attribute.contentobject_id)}
            <form action={"content/action"|ezurl} method="post">
                <input name="SocialDelete" type="image" src={"editdelete.png"|ezimage} value="{"Remove"|i18n('extension/dappsocial/actions')}" />
                <input name="ContentObjectID" type="hidden" value="{$object.id}" />
            </form>
            {/if}
        </div>

    {/foreach}

    {include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$attribute.object.main_node.url_alias
                     item_count=$children_count
                     view_parameters=$#view_parameters
                     item_limit=$page_limit}

    </div>

</div>