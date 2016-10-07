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
                                <td class="text-left col-xs-5"><?= $product['description'] ?><br/><?= $product['model'] ?></td>
                                <td class="col-xs-6">
                                    <?php if ($product['option']) { ?>
                                        <?php $unit = ''; ?>
                                        <?php foreach ($product['option'] as $option) ?>
                                        <?php if (strpos(strtolower($option['name']), 'unit')) ?>
                                        <?php $unit = $option['value']; ?>

                                        <?php foreach ($product['option'] as $option) { ?>
                                            <?php if (!strpos(strtolower($option['name']), 'unit')) { ?>
                                                <div class="col-xs-5 text-right"><?= $option['name'] ?>:</div>
                                                <div class="col-xs-7">
                                                    <?= $option['value'] ?>
                                                    <?php if (strpos(strtolower($option['name']), 'quant') !== false) echo $unit; ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <div class="col-xs-5 text-right">Weight:</div>
                                    <div class="col-xs-7"><?= $product['quantity'] . $product['stock_unit'] ?></div>
                                </td>
                                <td class="text-left"><?= $product['total'] ?></td>
                                <td class="text-center">
                                    <button type="button" onclick="cart.remove('<?= $product['cart_id'] ?>');" class="btn btn-danger btn-xs"><span>&#10539;</span><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($vouchers as $voucher) { ?>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-left"><?= $voucher['description'] ?></td>
                                <td class="text-right"><?= $voucher['amount'] ?> x 1</td>
                                <td class="text-center text-danger">
                                    <button type="button" onclick="voucher.remove('<?= $voucher['key'] ?>');" class="btn btn-danger btn-xs"><span>&#10539;</span><i class="fa fa-remove"></i></button>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php foreach ($totals as $i => $total) { ?>
                            <tr <?php if ($total == end($totals)) echo 'class="row-last"'; ?>>
                                <td colspan="2">
                                    <?php if ($total == end($totals)) { ?>
                                        <div class="col-xs-4 text-left">
                                            <i class="fa fa-shopping-cart"></i> &nbsp; <a href="<?= $cart ?>"><?= $text_cart ?></a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <i class="fa fa-share"></i> &nbsp;<a href="<?= $checkout ?>"><?= $text_checkout ?></a>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-4 pull-right text-right"><?= $total['title'] ?>:</div>
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
