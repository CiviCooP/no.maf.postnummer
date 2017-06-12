{literal}
<script type="text/javascript">
  cj("#address_1_postal_code").change(function() {
    var postCode = cj('#address_1_postal_code').val();
    CRM.api('Postnummer', 'getsingle', {'post_code': postCode}, {
        success: function(data) {
          cj("#address_1_city").val(data.post_city);
          cj("#address_1_state_province_id option").filter(function() {
            return cj(this).val() == data.county_id;
          }).prop("selected", true);
          cj("#address_1_state_province_id").trigger('change');
        }
      });
  });
</script>
{/literal}