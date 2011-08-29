<div class="block">
  <div class="button-left">
      <label for="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_disabled">{'Disabled'|i18n('extension/qavote/datatype')}:
      <input id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}_disabled" class="box ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" type="checkbox" name="{$attribute_base}_data_srrating_disabled_{$attribute.id}" value="1" {if $attribute.data_int}checked="checked" {/if}/>
      </label>
  </div>

  <div class="button-right">
    {def $rating = $attribute.content}

    {'Rating: %current_rating/5'|i18n('extension/qavote/datatype', '', hash( '%current_rating', concat('<span id="qavote_average_', $attribute.id, '" class="qavote-average-rating">', $rating.rating_average|wash, '</span>') ))}
    ({'%vote_count votes cast'|i18n('extension/qavote/datatype', '', hash( '%vote_count', concat('<span id="qavote_total_', $attribute.id, '">', $rating.vote_count|wash, '</span>') ))})

    {undef $rating}
  </div>
<div class="float-break"></div>
</div>