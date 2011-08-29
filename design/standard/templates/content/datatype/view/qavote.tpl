{set-block scope=global variable=cache_ttl}0{/set-block}

{def $rating = $attribute.content}

{*$attribute|attribute(show,1)*}


<div class="vote-area">
<div class="vote">
    <span id="qavote_{$attribute.id}_{$attribute.version}_1" title="This is useful and clear" class="vote-up-{if not(eq($rating.user_vote,1))}off{else}on{/if}{if not(eq($rating.user_vote,0))} qavote-disabled{/if}">up vote</span>
    <span id="qavote_count_{$attribute.id}" class="vote-count-post">{sub($rating.vote_up,$rating.vote_down)}</span>
    <span id="qavote_{$attribute.id}_{$attribute.version}_-1" title="This is unclear or not useful" class="vote-down-{if not(eq($rating.user_vote,-1))}off{else}on{/if}{if not(eq($rating.user_vote,0))} qavote-disabled{/if}">down vote</span>
</div>


<p id="qavote_just_rated_{$attribute.id}" class="qavote-just-rated hide">{'Thank you for rating!'|i18n('extension/qavote/datatype', 'When rating')}</p>
<p id="qavote_has_rated_{$attribute.id}" class="qavote-has-rated hide">{'You have already rated this page, you can only rate it once!'|i18n('extension/qavote/datatype', 'When rating')}</p>
<p id="qavote_changed_rating_{$attribute.id}" class="qavote-changed-rating hide">{'Your rating has been changed, thanks for rating!'|i18n('extension/qavote/datatype', 'When rating')}</p>
({'%vote_count votes cast'|i18n('extension/qavote/datatype', '', hash( '%vote_count', concat('<span id="qavote_total_', $attribute.id, '">', $rating.vote_count|wash, '</span>') ))})

</div>

{run-once}
{ezcss_require( 'qavote.css' )}


{* Enable rating code if not disabled on attribute and user has access to rate! *}


{if and( $attribute.data_int|not, has_access_to_limitation( 'ezjscore', 'call', hash( 'FunctionList', 'qavote_rate' ) ))}
    {ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio',  'qavote_jquery.js' ) )}
{else}
    {if ezmodule( 'user/register' )}
        <p id="qavote_no_permission_{$attribute.id}" class="qavote-no-permission">{'%login_link_startLog in%login_link_end or %create_link_startcreate a user account%create_link_end to rate this page.'|i18n( 'extension/qavote/datatype', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezurl('no'), '">' ), '%login_link_end', '</a>', '%create_link_start', concat( '<a href="', "/user/register"|ezurl('no'), '">' ), '%create_link_end', '</a>' ) )}</p>
    {else}
        <p id="qavote_no_permission_{$attribute.id}" class="qavote-no-permission">{'%login_link_startLog in%login_link_end to rate this page.'|i18n( 'extension/qavote/datatype', , hash( '%login_link_start', concat( '<a href="', '/user/login'|ezurl('no'), '">' ), '%login_link_end', '</a>' ) )}</p>
    {/if}
{/if}
{/run-once}
{undef $rating}

