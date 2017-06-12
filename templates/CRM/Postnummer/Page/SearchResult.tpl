{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{* Default template custom searches. This template is used automatically if templateFile() function not defined in
   custom search .php file. If you want a different layout, clone and customize this file and point to new file using
   templateFile() function.*}
<div class="crm-block crm-form-block crm-contact-custom-search-form-block">
    <div class="crm-accordion-wrapper crm-custom_search_form-accordion {if $rows}collapsed{/if}">
        <div class="crm-accordion-header crm-master-accordion-header">
          {ts}Edit Search Criteria{/ts}
        </div><!-- /.crm-accordion-header -->
        <div class="crm-accordion-body">
            <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
            <table class="form-layout-compressed">
                {* Loop through all defined search criteria fields (defined in the buildForm() function). *}
                {foreach from=$elements item=element}
                    <tr class="crm-contact-custom-search-form-row-{$element}">
                        <td class="label">{$form.$element.label}</td>
                        <td>{$form.$element.html}</td>
                    </tr>
                {/foreach}
            </table>
            <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
        </div><!-- /.crm-accordion-body -->
    </div><!-- /.crm-accordion-wrapper -->
</div><!-- /.crm-form-block -->

{if $rowsEmpty || $rows}
<div class="crm-content-block">
    <div class="crm-form-block crm-block">
        <div class="crm-submit-buttons">
        <a href="{crmURL p='civicrm/postnummer' q="reset=1&action=add"}" class="button action-item">{ts}Add a post code{/ts}</a>
        </div>
    </div>

    {if $rowsEmpty}
        {include file="CRM/Postnummer/Page/EmptyResults.tpl"}
    {/if}

    {if $rows}
      <div class="crm-results-block">
          <div class="crm-search-results">

          {include file="CRM/common/pager.tpl" location="top"}
            {strip}
            <table class="selector" summary="{ts}Search results listings.{/ts}">
                <thead class="sticky">
                    <tr>
                    {foreach from=$columnHeaders item=header}
                        <th scope="col">
                            {if $header.sort}
                                {assign var='key' value=$header.sort}
                                {$sort->_response.$key.link}
                            {else}
                                {$header.name}
                            {/if}
                        </th>
                    {/foreach}
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    </tr>
                </thead>

                {counter start=0 skip=1 print=false}
                {foreach from=$rows item=row}
                    <tr id='rowid{$row.post_code}' class="{cycle values="odd-row,even-row"}">
                        {foreach from=$columnHeaders item=header}
                            {assign var=fName value=$header.sort}
                            <td>{$row.$fName}</td>
                        {/foreach}
                        {capture assign=edit_url}{crmURL p='civicrm/postnummer' q="reset=1&action=edit&pc=`$row.post_code`"}{/capture}
                        {capture assign=delete_url}{crmURL p='civicrm/postnummer' q="reset=1&action=delete&pc=`$row.post_code`"}{/capture}
                        <td><span>
                            <a class="action-item action-item-first" title="Edit Postcode" href="{$edit_url}">Edit</a>
                        </span></td>
                        <td><span>
                            <a class="action-item action-item-last" title="Delete Postcode" href="{$delete_url}">Delete</a>
                        </span></td>
                    </tr>
                {/foreach}
            </table>
            {/strip}
            {include file="CRM/common/pager.tpl" location="bottom"}
            </p>
        {* END Actions/Results section *}
            </div>
        </div>
    {/if}
</div>
{/if}
