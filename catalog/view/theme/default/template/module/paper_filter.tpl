<?php if($slider_filter == 1): ?>
<div class="slider-wrap col-xs-10 container">

  <div class="filter size-filter length-filter pull-left text-center">
    <div class="filter-head"><b>Length</b></div>
    <div class="pars-up">
      <input class="pull-left" />
      <input class="pull-right" />
    </div>
	<div id="length-slider" class="filter-label"></div>
    <div class="pars-dw">
      <input class="pull-left" />
      <i class="fa fa-pencil" data-toggle="tooltip" data-placement="bottom" title="To configure, use inputs as well"></i>
      <input class="pull-right" />
    </div>
    <i class="fa fa-ban"></i>
  </div>

  <div class="filter size-filter width-filter pull-left text-center">
    <div class="filter-head"><b>Width</b></div>
    <div class="pars-up">
      <input class="pull-left" />
      <input class="pull-right" />
    </div>
	<div id="width-slider" class="filter-label"></div>
    <div class="pars-dw">
      <input class="pull-left" />
      <i class="fa fa-pencil" data-toggle="tooltip" data-placement="bottom" title="To configure, use inputs as well"></i>
      <input class="pull-right" />
    </div>
    <i class="fa fa-ban"></i>
  </div>

  <div class="filter gsm-filter pull-left text-center">
    <div class="filter-head"><b>GSM</b></div>
    <div class="pars-up">
      <input class="pull-left" />
      <input class="pull-right" />
    </div>
  	<div id="gsm-slider" class="filter-label"></div>
    <div class="pars-dw">
      <input class="pull-center" readonly />
    </div>
    <i class="fa fa-ban"></i>
  </div>

</div>
<?php endif; ?>
