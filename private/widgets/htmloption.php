
<option
  <?php htmlattr('value', $value) ?>
  <?php htmlattr('selected', ($value === $selected) ? 'selected' : '') ?>
>
  <?php html($label) ?>
</option>
