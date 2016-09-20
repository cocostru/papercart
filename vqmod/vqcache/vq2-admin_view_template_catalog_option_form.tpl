<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-option" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">

			<div class="form-group required option-alt <?php if ($option_alt == 0 && $option_description) echo 'hidden'; ?>">
				<label class="col-sm-2 control-label">Paper Mod. Type</label>
				<div class="col-sm-10">
					<select name="option_alt" class="form-control">
						<option value="0" <?php if ($option_alt == 0) echo 'selected'; ?> >--</option>
						<option value="1" <?php if ($option_alt == 1) echo 'selected'; ?> >Base Unit</option>
						<option value="2" <?php if ($option_alt == 2) echo 'selected'; ?> >Alt. Unit</option>
						<option value="3" <?php if ($option_alt == 3) echo 'selected'; ?> >Alt. Quantity</option>
						<option value="4" <?php if ($option_alt == 4) echo 'selected'; ?> >Alt. Location</option>
					</select>
				</div>
			</div>
			
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_description[$language['language_id']]) ? $option_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <select name="type" id="input-type" class="form-control">
                <optgroup label="<?php echo $text_choose; ?>">
                <?php if ($type == 'select') { ?>
                <option value="select" selected="selected"><?php echo $text_select; ?></option>
                <?php } else { ?>
                <option value="select"><?php echo $text_select; ?></option>
                <?php } ?>
                <?php if ($type == 'radio') { ?>
                <option value="radio" selected="selected"><?php echo $text_radio; ?></option>
                <?php } else { ?>
                <option value="radio"><?php echo $text_radio; ?></option>
                <?php } ?>
                <?php if ($type == 'checkbox') { ?>
                <option value="checkbox" selected="selected"><?php echo $text_checkbox; ?></option>
                <?php } else { ?>
                <option value="checkbox"><?php echo $text_checkbox; ?></option>
                <?php } ?>
                <?php if ($type == 'image') { ?>
                <option value="image" selected="selected"><?php echo $text_image; ?></option>
                <?php } else { ?>
                <option value="image"><?php echo $text_image; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $text_input; ?>">
                <?php if ($type == 'text') { ?>
                <option value="text" selected="selected"><?php echo $text_text; ?></option>
                <?php } else { ?>
                <option value="text"><?php echo $text_text; ?></option>
                <?php } ?>
                <?php if ($type == 'textarea') { ?>
                <option value="textarea" selected="selected"><?php echo $text_textarea; ?></option>
                <?php } else { ?>
                <option value="textarea"><?php echo $text_textarea; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $text_file; ?>">
                <?php if ($type == 'file') { ?>
                <option value="file" selected="selected"><?php echo $text_file; ?></option>
                <?php } else { ?>
                <option value="file"><?php echo $text_file; ?></option>
                <?php } ?>
                </optgroup>
                <optgroup label="<?php echo $text_date; ?>">
                <?php if ($type == 'date') { ?>
                <option value="date" selected="selected"><?php echo $text_date; ?></option>
                <?php } else { ?>
                <option value="date"><?php echo $text_date; ?></option>
                <?php } ?>
                <?php if ($type == 'time') { ?>
                <option value="time" selected="selected"><?php echo $text_time; ?></option>
                <?php } else { ?>
                <option value="time"><?php echo $text_time; ?></option>
                <?php } ?>
                <?php if ($type == 'datetime') { ?>
                <option value="datetime" selected="selected"><?php echo $text_datetime; ?></option>
                <?php } else { ?>
                <option value="datetime"><?php echo $text_datetime; ?></option>
                <?php } ?>
                </optgroup>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <table id="option-value" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left required"><?php echo $entry_option_value; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                
			<td class="text-left" data-toggle="tooltip" title="<?= $option_alt == 4 && count($new_zones) > 0 ? 'There is(are) ' . count($new_zones) . ' NEW zone(s).<br/>Need to UPDATE.' : ($option_alt == 2 && count($new_units) > 0 ? 'There is(are) ' . count($new_units) . ' NEW unit(s).<br/>Need to UPDATE.' : ($option_alt == 1 && count($new_base_units) > 0 ? 'There is(are) ' . count($new_base_units) . ' NEW unit(s).<br/>Need to UPDATE.' : 'There is nothing new.<br/>No need to update.')) ?>" data-placement="left"><?php if($option_alt && $option_alt > 0): ?><button type="button" class="btn btn-primary z-refresh"><i class="fa fa-refresh"></i></button><?php endif; ?></td>
			
              </tr>
            </thead>
            <tbody>
              <?php $option_value_row = 0; ?>
              <?php foreach ($option_values as $option_value) { ?>
              <tr id="option-value-row<?php echo $option_value_row; ?>">
                <td class="text-left"><input type="hidden" name="option_value[<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $option_value['option_value_id']; ?>" />
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="option_value[<?php echo $option_value_row; ?>][option_value_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($option_value['option_value_description'][$language['language_id']]) ? $option_value['option_value_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_option_value; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_option_value[$option_value_row][$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_option_value[$option_value_row][$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?></td>
                <td class="text-left"><a href="" id="thumb-image<?php echo $option_value_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $option_value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="option_value[<?php echo $option_value_row; ?>][image]" value="<?php echo $option_value['image']; ?>" id="input-image<?php echo $option_value_row; ?>" /></td>
                <td class="text-right"><input type="text" name="option_value[<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" class="form-control" /></td>
                
			<td data-toggle="tooltip" data-placement="left" data-original-title="<?=($option_value['count_assigned'] > 0) ? 'It is assigned to ' . $option_value['count_assigned'] . ' product(s).<br/>CANNOT BE REMOVED.' : 'It is not yet assigned to any.<br/>CAN BE REMOVED safely.' ?>" class="text-left"><button type="button" onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" <?=($option_value['count_assigned'] > 0) ? 'disabled style="filter:grayscale(1)"' : '' ?> class="btn btn-danger" ><i class="fa fa-minus-circle"></i></button></td>
			
              </tr>
              <?php $option_value_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="text-left"><button type="button" onclick="addOptionValue();" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#option-value').show();
	} else {
		$('#option-value').hide();
	}
});

$('select[name=\'type\']').trigger('change');

var option_value_row = <?php echo $option_value_row; ?>;


			function addOptionValue(ozone = '', ostate = '') {
			
	html  = '<tr id="option-value-row' + option_value_row + '">';
    html += '  <td class="text-left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
	<?php foreach ($languages as $language) { ?>
	html += '    <div class="input-group">';
	
			html += '      <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="option_value[' + option_value_row + '][option_value_description][<?php echo $language['language_id']; ?>][name]" value="' + ozone + '" placeholder="<?php echo $entry_option_value; ?>" class="form-control" ' + ((ozone != '') ? 'readonly' : '') + ' />';
			
    html += '    </div>';
	<?php } ?>
	html += '  </td>';
    
			html += '  <td class="text-left"><a href="" id="thumb-image' + option_value_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="option_value[' + option_value_row + '][image]" value="' + ostate + '" id="input-image' + option_value_row + '" /></td>';
			
	html += '  <td class="text-right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#option-value tbody').append(html);

	option_value_row++;
}
//--></script></div>

			<script>
				var sval = '';
				$('html').on('change', '.option-alt select', function(){
					if ($(this).val() == 0) {
						$('.form-group:not(.option-alt):not(.required)').slideDown();
						$('.form-group.required:not(.option-alt) input').val('');
						$('#option-value tbody').empty();
						$('#option-value td:not(:first-of-type):not(:last-of-type), #option-value tfoot').show();
						$('#option-value td:first-of-type input').prop('readonly', false);
					}
					else {
						$('.form-group:not(.option-alt):not(.required)').slideUp();
						$('.form-group.required:not(.option-alt) input').val($(this).find('option:selected').text());
						sval = $('select[name=type]').val();

						if ($(this).val() == 1) {
							$('select[name=type]').val('select');
							$('#option-value tbody').empty();
							<?php foreach ($units as $base_unit) : ?>
								<?php if ($base_unit['unit_type'] < 1) : ?>
									addOptionValue("<?= $base_unit['unit'] ?>", "<?= $base_unit['value'] ?>");
								<?php endif; ?>
							<?php endforeach; ?>
							$('#option-value td:not(:first-of-type):not(:last-of-type), #option-value tfoot').hide();
							$('#option-value td:first-of-type input').prop('readonly', true);
						}
						else if ($(this).val() == 2) {
							$('select[name=type]').val('select');
							$('#option-value tbody').empty();
							<?php foreach ($units as $unit) : ?>
								<?php if ($unit['unit_type'] > 0) : ?>
									addOptionValue("<?= $unit['unit'] ?>");
								<?php endif; ?>
							<?php endforeach; ?>
							$('#option-value td:not(:first-of-type):not(:last-of-type), #option-value tfoot').hide();
							$('#option-value td:first-of-type input').prop('readonly', true);
						}
						else if ($(this).val() == 3) $('select[name=type]').val('text');
						else if ($(this).val() == 4) {
							$('select[name=type]').val('select');
							$('#option-value tbody').empty();
							<?php foreach ($zones as $zone) : ?>
							addOptionValue("<?= $zone['code'] . ' (' . $zone['name'] . ')' ?>", "<?= $zone['country'] ?>");
							<?php endforeach; ?>
							$('#option-value td:not(:first-of-type):not(:last-of-type), #option-value tfoot').hide();
							$('#option-value td:first-of-type input').prop('readonly', true);
						}
					}
					$('select[name=type]').trigger('change');
				});

				<?php if ($option_alt > 0) : ?>
					$('#option-value td:first-of-type input').prop('readonly', true);
				<?php endif; ?>

				<?php if($option_alt == 4 && count($new_zones) > 0) : ?>
					$('html').on('click', '.z-refresh', function() {
						<?php foreach($new_zones as $nzone) : ?>
							addOptionValue("<?= $nzone['code'] . ' (' . $nzone['name'] . ')' ?>", "<?= $nzone['country'] ?>");
						<?php endforeach; ?>
						setTimeout(function(){
							$('.z-refresh').prop('disabled', true);
							$('.z-refresh').closest('td').attr('data-original-title', 'You\'ve just updated it. Save now.<br/>If you\'ve already removed the updates somehow, refresh the page to re-enable this button.');
						}, 500);
					});
				<?php elseif($option_alt == 2 && count($new_units) > 0) : ?>
					$('html').on('click', '.z-refresh', function() {
						<?php foreach($new_units as $nunit) : ?>
							addOptionValue("<?= $nunit['unit'] ?>", "<?= $nunit['value'] ?>");
						<?php endforeach; ?>
						setTimeout(function(){
							$('.z-refresh').prop('disabled', true);
							$('.z-refresh').closest('td').attr('data-original-title', 'You\'ve just updated it. Save now.<br/>If you\'ve already removed the updates somehow, refresh the page to re-enable this button.');
						}, 500);
					});
				<?php elseif($option_alt == 1 && count($new_base_units) > 0) : ?>
					$('html').on('click', '.z-refresh', function() {
						<?php foreach($new_base_units as $nbunit) : ?>
							addOptionValue("<?= $nbunit['unit'] ?>", "<?= $nbunit['value'] ?>");
						<?php endforeach; ?>
						setTimeout(function(){
							$('.z-refresh').prop('disabled', true);
							$('.z-refresh').closest('td').attr('data-original-title', 'You\'ve just updated it. Save now.<br/>If you\'ve already removed the updates somehow, refresh the page to re-enable this button.');
						}, 500);
					});
				<?php else : ?>
					$('.z-refresh').prop('disabled', true);
				<?php endif; ?>
			</script>

			<style>
				<?php if ($option_alt > 0) : ?>
					.form-group:not(.required), .option-alt, #option-value td:not(:first-of-type):not(:last-of-type), #option-value tfoot { display: none }
					.form-group.required { border: none }
				<?php endif; ?>
				#option-value thead td { border-bottom: none }
				#option-value td:last-of-type { width: 5% }
			</style>
			
<?php echo $footer; ?>
