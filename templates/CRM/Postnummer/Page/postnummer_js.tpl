{literal}
<script type="text/javascript">
  var blockId = {/literal}{$blockId}{literal};
  cj("#address_" + blockId + "_postal_code").change(function() {
    var postCode = cj('#address_' + blockId + '_postal_code').val();
    CRM.api('Postnummer', 'getsingle', {'post_code': postCode}, {
        success: function(data) {
          cj("#address_" + blockId + "_city").val(data.post_city);
          cj('#address_' + blockId + '_state_province_id option').filter(function() {
            return cj(this).val() == data.county_id;
          }).prop('selected', true);
        }
      });
  });
</script>
{/literal}