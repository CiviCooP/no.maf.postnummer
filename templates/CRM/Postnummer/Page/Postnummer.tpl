<div class="crm-content-block crm-block">
  <div id="help">
    The existing post codes oppgave are listed below. You can edit, delete or add a new one from this screen.
  </div>
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><div class="icon add-icon"></div>{$newButtonLabel}</span>
    </a>
  </div>
  {include file='CRM/common/jsortable.tpl'}
  <div id="postnummer_wrapper" class="dataTables_wrapper">
    <table id="postnummer-table" class="display">
      <thead>
      <tr>
        <th>{$postCodeLabel}</th>
        <th>{$postCityLabel}</th>
        <th>{$communityNumberLabel}</th>
        <th>{$communityNameLabel}</th>
        <th>{$categoryLabel}</th>
        <th id="nosort"></th>
      </tr>
      </thead>
      <tbody>
      {assign var="row_class" value="odd-row"}
      {foreach from=$postCodes key=rowNum item=postCode}
        <tr id="row1" class={$row_class}>
          <td>{$postCode.post_code}</td>
          <td>{$postCode.post_city}</td>
          <td>{$postCode.community_number}</td>
          <td>{$postCode.community_name}</td>
          <td>{$postCode.category}</td>
          <td>
              <span>
                {foreach from=$postCodeRecord.actions item=actionLink}
                  {$actionLink}
                {/foreach}
              </span>
          </td>
        </tr>
        {if $row_class eq "odd-row"}
          {assign var="row_class" value="even-row"}
        {else}
          {assign var="row_class" value="odd-row"}
        {/if}
      {/foreach}
      </tbody>
    </table>
  </div>
  <div class="action-link">
    <a class="button new-option" href="{$addUrl}">
      <span><div class="icon add-icon"></div>{$newButtonLabel}</span>
    </a>
  </div>
</div>
