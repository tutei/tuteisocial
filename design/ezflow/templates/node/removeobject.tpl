
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<form action={concat($module.functions.removeobject.uri)|ezurl} method="post" name="ObjectRemove">

<div class="warning">
<h2>{"Are you sure you want to remove these items?"|i18n("design/ezwebin/node/removeobject")}</h2>
<ul>
{foreach $remove_list as $remove_item}
    {if $remove_item.childCount|gt(0)}
        <li>{"%nodename and its %childcount children. %additionalwarning"
             |i18n( 'design/ezwebin/node/removeobject',,
                    hash( '%nodename', $remove_item.nodeName,
                          '%childcount', $remove_item.childCount,
                          '%additionalwarning', $remove_item.additionalWarning ) )}</li>
    {else}
        <li>{"%nodename %additionalwarning"
             |i18n( 'design/ezwebin/node/removeobject',,
                    hash( '%nodename', $remove_item.nodeName,
                          '%additionalwarning', $remove_item.additionalWarning ) )}</li>
    {/if}
{/foreach}
</ul>
</div>

{if $move_to_trash_allowed}
  <input type="hidden" name="SupportsMoveToTrash" value="1" />
  <input type="hidden" name="MoveToTrash" value="0" />


{/if}


<div class="buttonblock">
{include uri="design:gui/button.tpl" name=Store id_name=ConfirmButton value="Confirm"|i18n("design/ezwebin/node/removeobject")}
{include uri="design:gui/defaultbutton.tpl" name=Discard id_name=CancelButton value="Cancel"|i18n("design/ezwebin/node/removeobject")}
</div>

</form>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>