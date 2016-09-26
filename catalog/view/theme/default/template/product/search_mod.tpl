<?php echo $header; ?>
<style> [id|="column"] { width: 16.5%; padding: 9px 20px; } .filter.x { margin-bottom: 9px; } </style>

<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul><br/>

    <div class="cart pull-right col-xs-1">
        <div>
            <button type="button" onclick="addToCartMultiple();" data-loading-text="Processing..." class="btn btn-primary btn-buy" /><?php echo $button_cart; ?></button><br/><br/>
            <div><?php echo $cart; ?><br/></div>
        </div>
    </div>

    <div class="row"><?php echo $column_left; ?><?php echo $content_top; ?>
        <div class="col-xs-12 search-wrap" style="width:72.5%;padding: 7px 15px 20px">
            <div id="search-mod" class="input-group">
                <input type="text" name="search" value placeholder="Another Query" class="form-control" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                </span>
            </div>
            <?php if (count($products) < 1) { ?>
                <br/><br/><h4 class="text-center"><a>Please try another query. <?php echo $text_empty; ?></a></h4>
            <?php } ?>
        </div>

        <div id="content" class="pull-left table-wrap">
            <?php if ($products) { ?>
                <form action="" method="post" enctype="multipart/form-data" id="addmultiple">
                    <div class="table-responsive">
                        <table class="table text-left">
                            <thead>
                                <tr>
                                    <td width="4.5%" class="head-model"><small>No.</small></td>
                                    <td width="38%" class="head-name"><small>Description</small></td>
                                    <td width="6%" class="head-location"><small>Loc.</small></td>
                                    <td width="10%" class="head-stock"><small>Qty.</small></td>
                                    <td width="10%" class="head-price">
                                        <small>Price</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Price of 1 (kg) stock unit"></i>
                                    </td>
                                    <td width="10%" class="head-qinput">
                                        <small>Req. qty.</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Required quantity in stock units"></i>
                                    </td>
                                    <td width="3%" class="head-unit text-center"><small>Unit</small></td>
                                    <!-- <td width="3%" class="head-munit"><a>&#8644;</a></td> -->
                                    <td width="10%" class="head-ainput text-center">
                                        <small>Alt. qty.</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Quantity of package (alt.) units"></i>
                                    </td>
                                    <td width="5.5%" class="head-aunit"><small>Alt. unit</small></td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($products as $product) { ?>
                                <tr class="product-row">
                                    <td class="model"><small><?= $product['model'] ?></small></td>
                                    <td class="name">
                                        <small><?= $product['description'] ?></small>
                                        <div class="hidden">
                                            <small class="gsm"><?= $product['gsm'] ?> / </small>
                                            <?php if ($product['length'] < 1 && $product['width'] > 0) : ?>
                                                <small class="length roll">0</small>
                                                <small class="width"><?= $product['width'] ?></small>
                                            <?php elseif ($product['width'] < 1 && $product['length'] > 0) : ?>
                                                <small class="length"><?= $product['length'] ?></small>
                                                <small class="width roll">0</small>
                                            <?php else: ?>
                                                <small class="length"><?= $product['length'] ?></small>
                                                <small class="width"><?= $product['width'] ?></small>
                                                <small class="roll">noroll</small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="location">
                                        <?php if($product['alt_loc']): ?>
                                            <?= $product['location'] ? '<small class="hidden">' . $product['location'] . '</small>' : '<small class="hidden">unspec</small>' ?>
                                            <select name="option[<?php echo $product['alt_loc']['product_option_id']; ?>]" id="input-option<?php echo $product['alt_loc']['product_option_id']; ?>">
                                                <?php foreach($countries as $key => $country) : ?>
                                                    <optgroup label="<?= $country['name'] ?>">
                                                        <?php foreach ($product['alt_loc']['product_option_value'] as $option_value) : ?>
                                                            <?php if ($country['name'] == $option_value['image']) : ?>
                                                                <option title="<?= explode(')', explode('(', $option_value['name'], 2)[1], 2)[0] ?>" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if (explode(' (', $option_value['name'], 2)[0] == $product['location']) echo 'selected'; ?> >
                                                                    &nbsp;<?= explode(' (', $option_value['name'], 2)[0] ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php else: ?>
                                            <?= $product['location'] ? '<small>' . $product['location'] . '</small>' : '<small class="hidden">unspec</small>' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="stock"><small><?= $product['stock'] ?></small></td>
                                    <td class="price">
                                        <?php if ($product['price']) { ?>
                                            <?php if (!$product['special']) { ?>
                                                <small class="actual"><?= $product['price'] ?></small>
                                            <?php } else { ?>
                                                <small class="actual"><?= $product['special'] ?></small><br/>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="index.php?route=account/login"><small>sign in</small></a>
                                        <?php } ?>
                                    </td>
                                    <td class="qinput">
                                        <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>" />
                                        <input type="text" name="quantity[]" class="form-control input-sm" />
                                    </td>
                                    <td class="unit text-center"><small><?= $product['weight_unit'] ?></small></td>
                                    <!-- <td class="munit">
                                        <?php if($product['base_unit'] && $product['alt_qty']): ?>
                                            <select name="option[<?= $product['base_unit']['product_option_id'] ?>]" id="input-option[<?= $product['base_unit']['product_option_id'] ?>]">
                                                <?php foreach($product['base_unit']['product_option_value'] as $option_value): ?>
                                                    <option value="<?php echo $option_value['product_option_value_id']; ?>" data-val="<?=$option_value['image']?>">
                                                        &nbsp;<?= $option_value['name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </td> -->
                                    <td class="ainput">
                                        <?php if($product['alt_qty']): ?>
                                            <input type="text" name="option[<?= $product['alt_qty']['product_option_id'] ?>]" class="form-control input-sm" />
                                        <?php endif; ?>
                                    </td>
                                    <td class="aunit">
                                        <?php if($product['alt_unit']): ?>
                                            <select name="option[<?= $product['alt_unit']['product_option_id'] ?>]" id="input-option[<?= $product['alt_unit']['product_option_id'] ?>]">
                                                <?php foreach($product['alt_unit']['product_option_value'] as $option_value): ?>
                                                        <option title="<?= 'Weight: ' . number_format($option_value['weight'], 2) . 'kg' ?>" value="<?php echo $option_value['product_option_value_id']; ?>"  data-val="<?= number_format($option_value['weight'], 2) ?>" <?= $product['gsm'] > 180 ? ($option_value['name'] == 'pkt' ? 'selected' : '') : ($option_value['name'] == 'ream' ? 'selected' : '') ?> >
                                                            &nbsp;<?= $option_value['name'] ?>
                                                        </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            <?php } ?>
        </div>
        <?php echo $column_right; ?>
        <?php echo $content_bottom; ?>
    </div>
</div>

<script type="text/javascript"><!--

$('#search-mod input[name=\'search\']').parent().find('button').on('click', function() {
    var url = $('base').attr('href') + 'index.php?route=product/search',
        value = $('#search-mod input[name=\'search\']').val();

    if (value) url += '&search=' + encodeURIComponent(value);
    location = url;
});
$('#search-mod input[name=\'search\']').on('keydown', function(e) {
    if (e.keyCode == 13) {
        $('#search-mod input[name=\'search\']').parent().find('button').trigger('click');
    }
});

$('.slide-filter input').click();

//--></script>

<?php echo $footer; ?>
