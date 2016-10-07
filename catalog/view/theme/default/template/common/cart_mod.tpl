<div id="cart" class="btn-group btn-block">

    <button type="button" data-toggle="dropdown" data-loading-text="<?= $text_loading ?>" class="btn btn-success btn-block dropdown-toggle">
        <i class="fa fa-shopping-cart"></i> <span id="cart-total"><?= $text_items ?></span>
    </button>

    <ul class="dropdown-menu pull-right">
        <?php if ($products || $vouchers) { ?>
            <li>
                <table class="table table-striped">
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td class="text-left"><a><?= $product['description'] ?></a></td>
                                <td class="col-xs-6">
                                    <?php if ($product['option']) { ?>
                                        <?php $unit = ''; ?>
                                        <?php foreach ($product['option'] as $option) ?>
                                        <?php if (strpos(strtolower($option['name']), 'unit')) ?>
                                        <?php $unit = $option['value']; ?>

                                        <?php foreach ($product['option'] as $option) { ?>
                                            <?php if (!strpos(strtolower($option['name']), 'unit')) { ?>
                                                <div class="col-xs-6"><a><?= $option['name'] ?></a></div>
                                                <div class="col-xs-6">
                                                    <?= $option['value'] ?>
                                                    <?php if (strpos(strtolower($option['name']), 'quant')) echo $unit; ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <div class="col-xs-6"><a>Weight</a></div>
                                    <div class="col-xs-6"><?= $product['quantity'] . $product['stock_unit'] ?></div>
                                </td>
                                <td class="text-left"><?= $product['total'] ?></td>
                                <td class="text-center">
                                    <button type="button" onclick="cart.remove('<?= $product['cart_id'] ?>');" title="<?= $button_remove ?>" data-toggle="tooltip" data-placement="left" class="btn btn-danger btn-xs"><span>&#10539;</span><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($vouchers as $voucher) { ?>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-left"><?= $voucher['description'] ?></td>
                                <td class="text-right"><?= $voucher['amount'] ?> x 1</td>
                                <td class="text-center text-danger">
                                    <button type="button" onclick="voucher.remove('<?= $voucher['key'] ?>');" title="<?= $button_remove ?>" data-toggle="tooltip" data-placement="left" class="btn btn-danger btn-xs"><span>&#10539;</span><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($totals as $i => $total) { ?>
                            <tr <?php if ($total == end($totals)) echo 'class="row-last"'; ?>>
                                <td>
                                    <?php if ($total == end($totals)) { ?>
                                        <a href="<?= $cart ?>"><i class="fa fa-shopping-cart"></i> &nbsp; <?= $text_cart ?></a>
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <div class="col-xs-6">
                                        <?php if ($total == end($totals)) { ?>
                                            <a href="<?= $checkout ?>"><i class="fa fa-share"></i> &nbsp;<?= $text_checkout ?></a>
                                        <?php } ?>
                                    </div>
                                    <div class="col-xs-6"><?= $total['title'] ?>:</div>
                                </td>
                                <td class="text-left"><?= $total['text'] ?></td>
                                <td></td>
                            </tr>
                        <?php } ?>

                </table>

                <div></div>

            </li>

        <?php } else { ?>
            <li><p class="text-center"><?= $text_empty ?></p></li>
        <?php } ?>

    </ul>

</div>
