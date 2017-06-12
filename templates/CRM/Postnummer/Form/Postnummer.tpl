{* HEADER *}
<h3>{$formHeader}</h3>
<div class="crm-block crm-form-block">
  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  {if $action == 8}
    {* Delete a postcode *}
    {$form.post_code.html}
    <div class="help">
      {ts}Are you sure you want to delete the postal code?{/ts}
    </div>
  {else}
    {* edit or add a postcode *}
    {foreach from=$elementNames item=elementName}
      <div class="crm-section">
        <div class="label">{$form.$elementName.label}</div>
        <div class="content">{$form.$elementName.html}</div>
        <div class="clear"></div>
      </div>
    {/foreach}
  {/if}

  {* FOOTER *}
  <div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>