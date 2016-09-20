<div class="side-cat">
<?php foreach ($categories as $category) { ?>
    <div>
    <?php if ($category['category_id'] == $category_id) { ?>
        <?php if ($category['children']) { ?>
        <i class="show-subcat" data-toggle="collapse" href="#<?=$category['category_id']?>">&#8211;</i>
        <?php } ?>
        <a href="<?php echo $category['href']; ?>" class="active">
            <b><?php echo $category['name']; ?></b>
        </a><br/>
        <?php if ($category['children']) { ?>
            <div id="<?=$category['category_id']?>" class="collapse <?=$category['category_id'] == $category_id ? 'in' : ''?>">
            <?php foreach ($category['children'] as $child) { ?>
                <?php if ($child['category_id'] == $child_id) { ?>
                    <a href="<?php echo $child['href']; ?>" class="active"><b>&middot; &nbsp; <?php echo $child['name']; ?></b></a><br/>
                <?php } else { ?>
                    <a href="<?php echo $child['href']; ?>">&middot; &nbsp; <?php echo $child['name']; ?></a><br/>
                <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <?php if ($category['children']) { ?>
        <i class="show-subcat" data-toggle="collapse" href="#<?=$category['category_id']?>">+</i>
        <?php } ?>
        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a><br/>
        <?php if ($category['children']) { ?>
            <div id="<?=$category['category_id']?>" class="collapse">
            <?php foreach ($category['children'] as $child) { ?>
                <a href="<?php echo $child['href']; ?>">&middot; &nbsp; <?php echo $child['name']; ?></a><br/>
            <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
    </div>
<?php } ?>
<br/>
</div>

<div class="side-fbox">

    <?php if($range_filter == 1): ?>
    <div class="slide-filter cbox-filter">
        <a class="filt-head"><b>Size (precise/range)</b></a>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="filter[]" value="size" />
                enable slider
            </label>
        </div>
        <!-- <small class="fbox-icon fbox-icon-pointer"><i class="fa fa-mouse-pointer"></i>&nbsp; click a row to find alikes</small> -->
        <a style="cursor:pointer" class="reset-vals">
            <small class="fbox-icon"><i class="fa fa-refresh"></i>&nbsp; reset values</small>
        </a><br>
        <br/>
    </div>
    <?php endif; ?>

    <?php if($manufacturer_filter == 1): ?>
    <div class="mnfc-filter cbox-filter">
        <a class="filt-head"><b>Manufacturer</b></a>
        <div class="checkbox collapse mnfc-rest">
            <label>
                <input type="checkbox" name="filter[]" value="private" />
                <small>privates</small>
            </label>
        </div>
        <a class="show-rest" data-toggle="collapse" href=".mnfc-rest">show all <small>+</small></a>
        <br/><br/>
    </div>
    <?php endif; ?>

    <?php if($location_filter == 1): ?>
    <div class="loc-filter cbox-filter">
        <a class="filt-head"><b>Location</b></a>
        <?php foreach($countries as $key => $country): ?>
            <div class="checkbox">
                <?php if($country['zones']): ?>
                    <i class="show-subcat" data-toggle="collapse" href=".<?='inner-'.$country['name']?>">+</i>
                <?php endif; ?>
                <label>
                    <input type="checkbox" name="filter[]" value="<?=$country['code']?>" />
                    <?=$country['name']?>
                </label>
                <?php foreach($country['zones'] as $zone): ?>
                    <div class="checkbox inner-cbox <?='inner-'.$country['name']?> collapse">
                        <label>
                            &nbsp; &nbsp; &nbsp;
                            <input type="checkbox" name="filter[]" value="<?=$zone['code']?>" />
                            <?=$zone['name']?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="filter[]" value="unspec" />
                Unspec.
            </label>
        </div>
        <a class="show-rest" data-toggle="collapse" href=".loc-rest">show all <small>+</small></a>
        <br/><br/>
    </div>
    <?php endif; ?>

    <?php if($reel_filter == 1): ?>
    <div class="roll-filter cbox-filter">
        <a class="filt-head"><b>Reels</b></a>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="filter[]" value="roll" />
                show reels
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="filter[]" value="noroll" onchange="$('.length-filter').hasClass('cutted') ? '' : $('.length-filter').toggleClass('mute')" />
                <small>non-reels</small>
            </label>
        </div><br/>
    </div>
    <?php endif; ?>

    <?php if($grain_filter == 1): ?>
    <div class="grain-filter cbox-filter">
        <a class="filt-head"><b>Grain direction</b></a>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="filter[]" value="grain" />
                matters <i class="fa fa-info-circle" style="font-size:10px" data-toggle="tooltip" data-placement="bottom" title="If checked, vice-versa paper orientation is not acceptable"></i>
            </label>
        </div><br/>
    </div>
    <?php endif; ?>
</div>
