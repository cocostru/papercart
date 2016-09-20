<?php echo $header; ?>
<style> [id|="column"] { width: 16.5%; padding: 9px 20px; } </style>

<div class="container">
    <div class="col-xs-12 search-wrap" style="padding: 15px 0 30px 0">
        <div id="search-mod" class="input-group">
            <input type="text" name="search" value placeholder="Quick Find Products" class="form-control" />
            <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </div>
    <div class="cart pull-right col-xs-1">
        <div>
            <button type="button" onclick="addToCartMultiple();" data-loading-text="Processing..." class="btn btn-primary btn-buy" /><?php echo $button_cart; ?></button><br/><br/>
            <div><?php echo $cart; ?><br/></div>
        </div>
    </div>
    <div class="row">
        <?php echo $column_left; ?><?php echo $content_top; ?>
        <div id="content" class="pull-left table-wrap">
            <?php if ($products) { ?>
            <form method="post" enctype="multipart/form-data" id="addmultiple" >
                <div class="table-responsive">
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <td width="4.5%" class="head-model text-left"><small>No.</small></td>
                                <td width="37.75%" class="head-name text-left"><small>Description</small></td>
                                <td width="5.75%" class="head-location text-left"><small>Loc.</small></td>
                                <td width="10%" class="head-stock"><small>Qty.</small></td>
                                <td width="10%" class="head-price text-center">
                                    <small>Price</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Price for 1 stock unit"></i>
                                </td>
                                <td width="3.25%" class="head-unit"><small>Stock</small></td>
                                <td width="10%" class="head-qinput">
                                    <small>Req. qty.</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Required quantity in stock units"></i>
                                </td>
                                <td width="3.25%" class="head-munit text-left"><a>&#8644;</a></td>
                                <td width="10%" class="head-ainput">
                                    <small>Alt. qty.</small> <i class="fa fa-exclamation-circle" data-toggle="tooltip" data-original-title="Auto-conversion"></i>
                                </td>
                                <td width="5.5%" class="head-aunit text-left"><small>Alt. unit</small></td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr class="product-row">
                                <td class="model text-left"><small><?= $product['model'] ?></small></td>
                                <td class="name text-left">
                                    <small class="gsm"><?= number_format($product['gsm'], 0) ?> / </small>

                                    <?= $product['length'] > 0 ? '<small class="length">'.number_format($product['length'], 1).'</small>' : ( $product['length'] < 1 && $product['width'] > 0 ? '<small class="roll"><a>Roll</a></small><small class="length hidden">0</small>' : '<small class="length">0</small>' ) ?><small> x </small><?= $product['width'] > 0 ? '<small class="width">'.number_format($product['width'], 1).'</small>' : ( $product['width'] < 1 && $product['length'] > 0 ? '<small class="roll"><a>Roll</a></small><small class="width hidden">0</small>' : '<small class="width">0</small>' ) ?><small>,</small>

                                    <?php if ( ($product['width'] > 0 && $product['length'] > 0) || ($product['width'] < 1 && $product['length'] < 1) ) { ?><small class="roll hidden">noroll</small><?php } ?>

                                    <small class="gsm"><?= number_format($product['weight'], 2) . $product['weight_unit'] ?> </small>

                                    <small class="mnfctr"><?= $product['manufacturer'] ? $product['manufacturer']['name'] : 'private' ?></small>
                                </td>
                                <td class="location text-left">
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
                                <td class="price text-center">
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
                                <td class="unit"><small><?= $product['weight_unit'] ?></small></td>
                                <td class="qinput">
                                    <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>" />
                                    <input type="text" name="quantity[]" class="form-control input-sm" />
                                </td>
                                <td class="munit">
                                    <!-- <small><?= $product['weight_unit'] ?></small> -->
                                    <?php if($product['base_unit'] && $product['alt_qty']): ?>
                                        <select name="option[<?= $product['base_unit']['product_option_id'] ?>]" id="input-option[<?= $product['base_unit']['product_option_id'] ?>]">
                                            <?php foreach($product['base_unit']['product_option_value'] as $option_value): ?>
                                                <option value="<?php echo $option_value['product_option_value_id']; ?>" data-val="<?=$option_value['image']?>">
                                                    &nbsp;<?= $option_value['name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </td>
                                <td class="ainput">
                                    <?php if($product['alt_qty']): ?>
                                        <input type="text" name="option[<?= $product['alt_qty']['product_option_id'] ?>]" class="form-control input-sm" />
                                    <?php endif; ?>
                                </td>
                                <td class="aunit">
                                    <?php if($product['alt_unit']): ?>
                                        <select name="option[<?= $product['alt_unit']['product_option_id'] ?>]" id="input-option[<?= $product['alt_unit']['product_option_id'] ?>]">
                                            <?php foreach($product['alt_unit']['product_option_value'] as $option_value): ?>
                                                    <option title="<?= $option_value['name'] ?>" value="<?php echo $option_value['product_option_value_id']; ?>">
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
            <?php echo $column_right; ?>
            <?php echo $content_bottom; ?>
        </div>
    </div>
</div>

<script>
function homeFade(){
    $('.container').css('padding-top', 0);
    $('.side-cat').css('float', 'left');

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
}
</script>

<?php echo $footer; ?>
