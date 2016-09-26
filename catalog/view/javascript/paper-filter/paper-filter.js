function addToCartMultiple() {
    $.ajax({
    		url: 'index.php?route=checkout/cart/add_multiple',
    		type: 'post',
    		data: $('#addmultiple').serialize(),
    		dataType: 'json',
        beforeSend: function() {
            $('.btn-buy').button('loading');
        },
        complete: function() {
            $('.btn-buy').button('reset');
        },
    		success: function(json) {
      			$('.alert, .alert-danger').remove();
      			$('.form-group').removeClass('has-error');

      			if (json['success']) {
        				$('#cart').after('<div class="alert alert-success text-center" style="line-height:1">' + json['success'] + '</div>');

                setTimeout(function () {
        					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
        				}, 100);

        				$('#cart > ul').load('index.php?route=common/cart/info ul li');

                $('.qinput input[name="quantity[]"], .ainput input').val('');

                setTimeout(function () {
        					$('.alert').fadeOut('slow');
        				}, 12000);
      			}
    		}
    });
}

function mboxFill(){
    var mnar = [],
        uniq = [];

    $('.product-row').each(function(){
        mnar.push( $(this).find('.mnfctr').text() );
    });

    mnar.forEach(function(i){
        if($.inArray(i, uniq) === -1 && i && i != 'private') uniq.push(i);
    });

    if ($.inArray('private', mnar) != -1 ? uniq.length < 1 : uniq.length <= 1) {
        $('.mnfc-filter').remove();
        // return;
    }

    uniq.reverse().forEach(function(i, k){
        if (k > (uniq.length - 4))
        $('.mnfc-filter').find('.filt-head').after('<div class="checkbox"><label><input type="checkbox" name="filter[]" value="' + i + '" />' + i + '</label></div>');
        else
        $('.mnfc-filter').find('.filt-head').after('<div class="checkbox collapse mnfc-rest"><label><input type="checkbox" name="filter[]" value="' + i + '" />' + i + '</label></div>');
    });

    if (uniq.length > 3) $('.mnfc-filter').find('.show-rest').show();
    else $('.mnfc-filter').find('.show-rest').hide();
}

function lboxFill(){
    var mnar = [],
        cntr = [],
        uniq = {},
        whrv = $('.product-row .location select').length;

    $('.product-row').each(function(){
        mnar.push( $(this).find('.location small').text() );
    });

    mnar.forEach(function(i){
        !uniq[i] ? uniq[i] = 1 : uniq[i]++;
    });

    $('.loc-filter .checkbox:not(.inner-cbox)').each(function(){
        var count = 0,
            city = $(this).find('> label > input'),
            zone = $(this).find('div.inner-cbox > label > input');

        zone.each(function(){
            if (uniq[$(this).val()]) count += uniq[$(this).val()];
            $(this).parent().append('<span>(' + (uniq[$(this).val()] ? uniq[$(this).val()] : '0') + ')</span>');
        });

        if (uniq[city.val()]) count += uniq[city.val()];

        cntr.push(count);

        $(this).find('> label').append('<span>(' + count + ')</span>');

        if (Math.max.apply(null, cntr) == count) $(this).insertBefore('.loc-filter .checkbox:not(.inner-cbox):first-of-type');

        if ($(this).index() > 2)  $(this).addClass('collapse loc-rest');
    });
}

function cboxChange(){
    $('html').on('change', '.checkbox input', function(){
        var filt = $(this).closest('.cbox-filter'),
            cbox = $(this).closest('.checkbox'),
            arbo = [];

        if ($(this).is(':checked')) {
            $(this).closest('.checkbox').addClass('m');
            if (filt.hasClass('loc-filter')) {
                if (cbox.find('.collapse').length > 0) {
                    cbox.find('.collapse:not(.show-subcat)').find('input').prop('checked', true);
                    // cbox.find('.show-subcat').trigger('click');
                }
            }
        } else {
            $(this).closest('.checkbox').removeClass('m');
            if (filt.hasClass('loc-filter')) {
                if (cbox.find('.collapse').length > 0) {
                    cbox.find('.collapse:not(.show-subcat)').find('input').prop('checked', false);
                    // cbox.find('.show-subcat').trigger('click');
                }
            }
        }

        if ( !filt.hasClass('grain-filter') && !filt.hasClass('slide-filter') ) {
            $.each(filt.find('input'), function(){
                if ($(this).is(':checked')) {
                    arbo.push($(this).val().toLowerCase());
                }
            });
        }

        $('.product-row').each(function(){
            if (filt.hasClass('loc-filter')) {
                var text = $(this).find('.location select').length > 0 ? $(this).find('.location select').val() : $(this).find('.location small').text(),
                    klas = 'locbox';
                    text = text ? text.toString() : '';
            } else if (filt.hasClass('mnfc-filter')) {
                var text = $(this).find('.mnfctr').text(),
                    klas = 'manbox';
            } else if (filt.hasClass('roll-filter')) {
                var text = $(this).find('.roll').text(),
                    klas = 'rolbox';
            }

            console.log(text);

            if ( !filt.hasClass('grain-filter') && !filt.hasClass('slide-filter') ) {
                if ( ($.inArray(text.toLowerCase(), arbo) > -1 && arbo.length > 0) || (arbo.length == 0) ) {
                    $(this).removeClass(klas);
                    if (!$(this).hasClass('cutbox') && !$(this).hasClass('manbox') && !$(this).hasClass('locbox') && !$(this).hasClass('rolbox')) {
                        $(this).fadeIn('slow');
                    }
                }
                else if ( $.inArray(text.toLowerCase(), arbo) === -1 && arbo.length > 0 ) {
                    $(this).addClass(klas);
                    $(this).find('input[name="quantity[]"]').val('');
                    $(this).fadeOut('slow');
                }
            }

            // if (filt.hasClass('grain-filter') && arbo.length > 0) applySliders();
            if (filt.hasClass('grain-filter')) applySliders();
        });
    });
}

function sliderInputs(){
    $('.filter input')
        .on('focus', function(){
            $(this).val('');
        })
        .keydown(function (e) {
            // backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Ctrl+C
                (e.keyCode == 67 && e.ctrlKey === true) ||
                 // Ctrl+X
                (e.keyCode == 88 && e.ctrlKey === true) ||
                 // home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        })
        .on('input', function(){
            if ( $(this).parent().hasClass('pars-up') && $(this).hasClass('pull-right') ) {
                var toval = $(this).val();
                if (slide) $(this).parent().parent().find('.filter-label').slider('values', 1, toval*10);
                $(this).parent().parent().find('.pars-dw input.pull-right').val((toval/2.54).toFixed(1) + ' in');
            }

            if ( $(this).parent().hasClass('pars-up') && $(this).hasClass('pull-left') ) {
                var toval = $(this).val();
                if (slide) $(this).parent().parent().find('.filter-label').slider('values', 0, toval*10);
                else $(this).parent().parent().find('.filter-label').slider('value', toval*10);
                $(this).parent().parent().find('.pars-dw input.pull-left').val((toval/2.54).toFixed(1) + ' in');
            }

            if ( $(this).parent().hasClass('pars-dw') && $(this).hasClass('pull-right') ) {
                var toval = $(this).val();
                if (slide) $(this).parent().parent().find('.filter-label').slider('values', 1, toval*10*2.54);
                $(this).parent().parent().find('.pars-up input.pull-right').val((toval*2.54).toFixed(1) + ' cm');
            }

            if ( $(this).parent().hasClass('pars-dw') && $(this).hasClass('pull-left') ) {
                var toval = $(this).val();
                if (slide) $(this).parent().parent().find('.filter-label').slider('values', 0, toval*10*2.54);
                else $(this).parent().parent().find('.filter-label').slider('value', toval*10*2.54);
                $(this).parent().parent().find('.pars-up input.pull-left').val((toval*2.54).toFixed(1) + ' cm');
            }

            applySliders();

        })
        .on('blur', function(){
            parseVals();
        });

}

function parseVals(){

    $('.size-filter').each(function(){
        var uprg = $(this).find('.pars-up input.pull-right'),
            uplf = $(this).find('.pars-up input.pull-left'),
            dwrg = $(this).find('.pars-dw input.pull-right'),
            dwlf = $(this).find('.pars-dw input.pull-left'),
            prsr = parseFloat($(this).find('.ui-slider-handle:nth-of-type(2) .ui-slider-tip').text()),
            prsl = parseFloat($(this).find('.ui-slider-handle:first-of-type .ui-slider-tip').text());

        uprg.val(prsr.toFixed(1) + ' cm');
        uplf.val(prsl.toFixed(1) + ' cm');
        dwrg.val(parseFloat(prsr/2.54).toFixed(1) + ' in');
        dwlf.val(parseFloat(prsl/2.54).toFixed(1) + ' in');
    });

    $('.gsm-filter').each(function(){
        var uprg = $(this).find('.pars-up input.pull-right'),
            uplf = $(this).find('.pars-up input.pull-left'),
            dwin = $(this).find('.pars-dw input.pull-center'),
            prsr = parseFloat($(this).find('.ui-slider-handle:nth-of-type(2) .ui-slider-tip').text()),
            prsl = parseFloat($(this).find('.ui-slider-handle:first-of-type .ui-slider-tip').text()),
            plen = parseFloat($('#length-slider').find('.ui-slider-handle:nth-of-type(2) .ui-slider-tip').text()),
            pwid = parseFloat($('#width-slider').find('.ui-slider-handle:nth-of-type(2) .ui-slider-tip').text()),
            shee = parseFloat($(this).find('.ui-slider-handle:nth-of-type(2) .ui-slider-tip').text()) < 180 ? 500 : 100;

        var weig = plen*pwid*prsr*shee/1e7;

        uprg.val(prsr.toFixed(1));
        uplf.val(prsl.toFixed(1));
        dwin.val('~ ' + parseFloat(weig).toFixed(1) + ' kg' + ' / ' + shee + ' sheets');
    });
}

function applySliders(){
    if (!$('.filter input').is(':focus')) {
        parseVals();
    }

    var lengthRange = [],
        widthRange = [],
        gsmRange = [];

    $('#length-slider .ui-slider-tip').each(function(){
        lengthRange.push($(this).text());
    });
    $('#width-slider .ui-slider-tip').each(function(){
        widthRange.push($(this).text());
    });
    $('#gsm-slider .ui-slider-tip').each(function(){
        gsmRange.push($(this).text());
    });

    var ifReset = !slide && $('#length-slider').slider('option', 'value') == 0 && $('#width-slider').slider('option', 'value') == 0 && $('#gsm-slider').slider('option', 'value') == 0;

    grain = $('.grain-filter input').is(':checked');
    slide = $('.slide-filter input').is(':checked');

    $('.product-row').each(function(){

        var length = parseFloat($(this).find('.length').text()).toFixed(1),
            width = parseFloat($(this).find('.width').text()).toFixed(1),
            gsmth = parseFloat($(this).find('.gsm').text()).toFixed(1);

        var ifLenRoll = width == 0 && length > 0;
        var ifWidRoll = length == 0 && width > 0;

        var lenDoGrain = slide ? (length >= parseFloat(lengthRange[0]) && length <= parseFloat(lengthRange[1])) : (length == parseFloat(lengthRange[0]));
        var widDoGrain = slide ? (width >= parseFloat(widthRange[0]) && width <= parseFloat(widthRange[1])) : (width == parseFloat(widthRange[0]));

        var lenNoGrain = slide ? (width >= parseFloat(lengthRange[0]) && width <= parseFloat(lengthRange[1])) : (width == parseFloat(lengthRange[0]));
        var widNoGrain = slide ? (length >= parseFloat(widthRange[0]) && length <= parseFloat(widthRange[1])) : (length == parseFloat(widthRange[0]));

        var doGsm = slide ? (gsmth >= parseFloat(gsmRange[0]) && gsmth <= parseFloat(gsmRange[1])) : (gsmth == parseFloat(gsmRange[0]));

        var doGrain = (ifLenRoll ? lenDoGrain : (ifWidRoll ? widDoGrain : (lenDoGrain && widDoGrain))) && doGsm;
        var noGrain = (ifLenRoll ? widNoGrain : (ifWidRoll ? lenNoGrain : (lenNoGrain && widNoGrain))) && doGsm;

        if( doGrain || (!grain ? noGrain : '') || ifReset ){
            $(this).removeClass('cutbox');
            if (!$(this).hasClass('cutbox') && !$(this).hasClass('manbox') && !$(this).hasClass('locbox') && !$(this).hasClass('rolbox')) {
                $(this).fadeIn('slow');
            }
        } else {
            $(this).addClass('cutbox');
            $(this).fadeOut('slow');
            $(this).find('input[name="quantity[]"]').val('');
        }

    });
}

function initSliders(){

    var mets = {

        singleXrange: function () {
            $('#length-slider')
                .slider({
                    min: lenmin,
                    max: lenmax,
                    range: slide,
                    values: slide ? [lenmin, lenmax] : '',
                    value: slide ? '' : 0
                })
                .slider('pips')
                .slider('float');

            $('#width-slider')
                .slider({
                    min: widmin,
                    max: widmax,
                    range: slide,
                    values: slide ? [widmin, widmax] : '',
                    value: slide ? '' : 0
                })
                .slider('pips')
                .slider('float');

            $('#gsm-slider')
                .slider({
                    min: gsmmin,
                    max: gsmmax,
                    range: slide,
                    values: slide ? [gsmmin, gsmmax] : '',
                    value: slide ? '' : 0
                })
                .slider('pips')
                .slider('float');

        },

        reInit: function () {
            slide = $('.slide-filter input').is(':checked');

            slide ? $('.filter').removeClass('x') : $('.filter').addClass('x');

            mets.singleXrange();

            parseVals();

            $('.product-row').each(function(){
                $(this).removeClass('cutbox');
                if (!$(this).hasClass('manbox') && !$(this).hasClass('locbox') && !$(this).hasClass('rolbox')) {
                    $(this).fadeIn('slow');
                }
                $('.table-wrap').hide().fadeIn('slow');
            });
        }
    };

    var len = [],
        wid = [],
        gsm = [],
        rls = [],
        nrl = [];

    $('.product-row').each(function(){
        var k = $(this).find('.roll').text().toLowerCase();
        k == 'roll' ? rls.push(k) : nrl.push(k);
    });

    if (rls.length == 0 || nrl.length == 0) $('.roll-filter').remove();

    $('.length').each(function(){
        len.push(parseFloat($(this).text()));
    });
    $('.width').each(function(){
        wid.push(parseFloat($(this).text()));
    });
    $('.gsm').each(function(){
        gsm.push(parseFloat($(this).text()));
    });

    var lenmin = Math.min.apply( null, len ) * 10,
        lenmax = Math.max.apply( null, len ) * 10,
        widmin = Math.min.apply( null, wid ) * 10,
        widmax = Math.max.apply( null, wid ) * 10,
        gsmmin = Math.min.apply( null, gsm ) * 10,
        gsmmax = Math.max.apply( null, gsm ) * 10;

    // if ($('#addmultiple').length > 0) {
    lenmax > 0 && lenmax != lenmin ? '' : $('.length-filter').addClass('mute cutted');
    widmax > 0 && widmax != widmin ? '' : $('.width-filter').addClass('mute cutted');
    gsmmax > 0 && gsmmax != gsmmin ? '' : $('.gsm-filter').addClass('mute cutted');
    // }

    lenmax > 0 && lenmax != lenmin ? '' : ([lenmax, lenmin] = [1, 0]);
    widmax > 0 && widmax != widmin ? '' : ([widmax, widmin] = [1, 0]);
    gsmmax > 0 && gsmmax != gsmmin ? '' : ([gsmmax, gsmmin] = [1, 0]);

    maxlen = Math.max.apply( null, len ).toFixed(1);

    mets.singleXrange();

    $('html').on('click', '.slide-filter input, .reset-vals', function(){
        mets.reInit();
    });

}

function commonActs(){

    var mets = {

        fixCart: function () {

            var fired = false;
            var cart = $('.cart').offset().top;
            var temp = $('<div />').css({
                    float: 'left',
                    width: '100%',
                    display: 'block',
                    height: $('.cart').outerHeight(),
                    margin: $('.cart').css('margin')
                });

            $('html').on('click', '.btn-navbar', function(){
                setTimeout(function(){
                    cart = $('.cart').offset().top;
                }, 500);
            });

            window.addEventListener('scroll', function(){
                if (document.body.scrollTop >= cart && fired === false) {
                    $('.cart').before(temp);
                    $('.cart').addClass('m');
                    fired = true;
                }
                if (document.body.scrollTop < cart) {
                    temp.remove();
                    $('.cart').removeClass('m');
                    fired = false;
                }
            }, true);

            $('.btn-buy').on('click', function(){
                setTimeout(function(){
                    if ($('.alert').length > 0)
                    $('.alert').hide();
                }, 4000);
            });
        },

        mobCommons: function () {
            $('.slide-filter').insertAfter('.grain-filter');
            $('.side-cat .show-subcat').trigger('click');
        },

        rowClick: function (row) {
            var length = parseFloat(row.find('.length').text()).toFixed(1) * 10,
                width = parseFloat(row.find('.width').text()).toFixed(1) * 10,
                gsmth = parseFloat(row.find('.gsm').text()).toFixed(1) * 10;

            if (slide) {
                $('#length-slider').slider({values: [length - 1, length]});
                $('#width-slider').slider({values: [width - 1, width]});
                $('#gsm-slider').slider({values: [gsmth - 1, gsmth]});
                // applySliders();
            }
            else {
                $('#length-slider').slider('value', length);
                $('#width-slider').slider('value', width);
                $('#gsm-slider').slider('value', gsmth);
                applySliders();
            }
        },

        expandShow: function () {
            $('.show-subcat').on('click', function(){
                var idm = $(this).attr('href');
                if (!$(idm).hasClass('in')) $(this).html('&#8211;');
                else $(this).html('+');
            });

            $('.show-rest').on('click', function(){
                if ($(this).parent().find('.collapse.in').length > 0) $(this).html('show all <small>+</small>');
                else $(this).html('show less <small>-</small>');
            });
        },

        moreNav: function () {
            var lislen = 0;
            var navlen = $('#menu .nav').outerWidth() - $('.menu-more').outerWidth();

            $('#menu .nav > li:not(.menu-extra):not(.menu-more)').each(function(){
                lislen += $(this).outerWidth();
                if (lislen > navlen) {
                    $(this).find('.dropdown-menu').remove();
                    $(this).appendTo('.menu-more .list-unstyled');
                }
            });
        },

        penHover: function () {
            $('.filter .fa-pencil')
                .on('mouseover', function(){
                    $(this).parent().parent().find('input').addClass('m');
                })
                .on('mouseleave', function(){
                    $(this).parent().parent().find('input').removeClass('m');
                });
        },

        optionChange: function () {
            $('html').on('change', 'td select', function(){
                var parent = $(this).parent(),
                    hidden = parent.find('[type=hidden]'),
                    uname = $(this).val(),
                    uval = $(this).find(':selected').attr('data-val'),
                    alt = $(this).closest('tr').find('td.ainput input'),
                    qnt = $(this).closest('tr').find('td.qinput input[type=text]'),
                    aval = alt.val(),
                    qval = qnt.val();

                // hidden.val(uname);

                if (parent.hasClass('aunit')) {
                    // hidden.attr('data-unit', uval);
                    if (qval > 0 || aval > 0) {
                        alt.val(parseFloat(qval / uval).toFixed(2));
                    }

                    // $.ajax({
                	// 	url: 'index.php?route=module/paper_filter/baseUnit',
                	// 	type: 'post',
                	// 	data: {bunit: uname}
                    // });
                }
            });

            $('html').on('input', 'td.qinput input[type=text]', function(){
                var alt = $(this).closest('tr').find('td.ainput input'),
                    ratio = $(this).closest('tr').find('td.aunit select').find(':selected').attr('data-val'),
                    qval = $(this).val();

                alt.val(qval > 0 ? parseFloat(qval / ratio).toFixed(2) : '');
            });

            $('html').on('input', 'td.ainput input[type=text]', function(){
                var qnt = $(this).closest('tr').find('td.qinput input[type=text]'),
                    ratio = $(this).closest('tr').find('td.aunit select').find(':selected').attr('data-val'),
                    aval = $(this).val();

                qnt.val(aval > 0 ? parseFloat(aval * ratio).toFixed(2) : '');
            });

            $('td select').trigger('change');
        },

        locationVoid: function () {
            $('.location select optgroup').each(function(){
                if ($(this).children().length < 1) $(this).remove();
            });
        }
    };

    mets.optionChange();
    mets.locationVoid();
    mets.expandShow();
    mets.penHover();

    var mql = window.matchMedia('only screen and (min-width : 0) and (max-width : 767px) and (orientation : landscape), only screen and (min-width : 0) and (max-width : 767px) and (orientation : portrait)');

    if (mql.matches) {
        mets.mobCart();
        mets.mobCommons();
    } else {
        mets.moreNav();
    }

    slide ? $('.filter').removeClass('x') : $('.filter').addClass('x');

    $('.filter-label').draggable();

}

var maxlen,
    slide = $('.slide-filter input').is(':checked');

$(document).ready(function(){

    slide = $('.slide-filter input').is(':checked');

    initSliders();
    parseVals();
    sliderInputs();
    mboxFill();
    lboxFill();
    cboxChange();
    commonActs();
    // applySliders();

});

// EXTENDING JQUERY UI SLIDER

(function($) {

    "use strict";

    var extensionMethods = {

        pips: function( settings ) {

            var slider = this, i, j, p, collection = "",
                mousedownHandlers = $._data( slider.element.get(0), "events").mousedown,
                originalMousedown,
                min = slider._valueMin(),
                max = slider._valueMax(),
                value = slider._value(),
                values = slider._values(),
                pips = ( max - min ) / slider.options.step,
                $handles = slider.element.find(".ui-slider-handle"),
                $pips;

            var options = {

                first: "false",
                // "label", "pip", false

                last: "false",
                // "label", "pip", false

                rest: "false",
                // "label", "pip", false

                labels: false,
                // [array], { first: "string", rest: [array], last: "string" }, false

                prefix: "",
                // "", string

                suffix: "",
                // "", string

                step: ( pips > 100 ) ? Math.floor( pips * 0.05 ) : 5,
                // number

                formatLabel: function(value) {
                    return this.prefix + value + this.suffix;
                }
                // function
                // must return a value to display in the pip labels

            };

            $.extend( options, settings );

            slider.options.pipStep = options.step;

            // get rid of all pips that might already exist.
            slider.element
                .addClass("ui-slider-pips")
                .find(".ui-slider-pip")
                .remove();

            // small object with functions for marking pips as selected.

            var selectPip = {

                single: function(value) {

                    this.resetClasses();

                    $pips
                        .filter(".ui-slider-pip-" + this.classLabel(value) )
                        .addClass("ui-slider-pip-selected");

                },

                range: function(values) {

                    this.resetClasses();

                    for( i = 0; i < values.length; i++ ) {

                        $pips
                            .filter(".ui-slider-pip-" + this.classLabel(values[i]) )
                            .addClass("ui-slider-pip-selected-" + (i+1) );

                    }

                    if ( slider.options.range ) {

                        $pips.each(function(k,v) {

                            var pipVal = $(v).children(".ui-slider-label").data("value");

                            if( pipVal > values[0] && pipVal < values[1] ) {

                                $(v).addClass("ui-slider-pip-inrange");

                            }

                        });

                    }

                },

                classLabel: function(value) {

                    return value.toString().replace(".","-");

                },

                resetClasses: function() {

                    var regex = /(^|\s*)(ui-slider-pip-selected|ui-slider-pip-inrange)(-{1,2}\d+|\s|$)/gi;

                    $pips.removeClass( function (index, css) {
                        return ( css.match(regex) || [] ).join(" ");
                    });

                }

            };

            function getClosestHandle( val ) {

                var h, k,
                    sliderVals,
                    comparedVals,
                    closestVal,
                    tempHandles = [],
                    closestHandle = 0;

                if( values && values.length ) {

                    // get the current values of the slider handles
                    sliderVals = slider.element.slider("values");

                    // find the offset value from the `val` for each
                    // handle, and store it in a new array
                    comparedVals = $.map( sliderVals, function(v) {
                        return Math.abs( v - val );
                    });

                    // figure out the closest handles to the value
                    closestVal = Math.min.apply( Math, comparedVals );

                    // If a comparedVal is the closestVal, then
                    // set the value accordingly, and set the closest handle.
                    for( h = 0; h < comparedVals.length; h++ ) {
                        if( comparedVals[h] === closestVal ) {
                            tempHandles.push(h);
                        }
                    }

                    // set the closest handle to the first handle in array,
                    // just incase we have no _lastChangedValue to compare to.
                    closestHandle = tempHandles[0];

                    // now we want to find out if any of the closest handles were
                    // the last changed handle, if so we specify that handle to change
                    for( k = 0; k < tempHandles.length; k++ ) {
                        if( slider._lastChangedValue === tempHandles[k] ) {
                            closestHandle = tempHandles[k];
                        }
                    }

                }

                return closestHandle;

            }

            // when we click on a label, we want to make sure the
            // slider's handle actually goes to that label!
            // so we check all the handles and see which one is closest
            // to the label we clicked. If 2 handles are equidistant then
            // we move both of them. We also want to trigger focus on the
            // handle.

            // without this method the label is just treated like a part
            // of the slider and there's no accuracy in the selected value

            function labelClick( label ) {
                if (slider.option("disabled")) {
                    return;
                }

                var val = $(label).data("value"),
                    indexToChange = getClosestHandle( val );

                if ( values && values.length ) {
                    slider.element.slider("values", indexToChange, val );
                } else {
                    slider.element.slider("value", val );
                }

                slider._lastChangedValue = indexToChange;
            }

            // method for creating a pip. We loop this for creating all
            // the pips.

            function createPip( which ) {

                var label,
                    percent,
                    number = which,
                    classes = "ui-slider-pip",
                    css = "";

                if ( "first" === which ) { number = 0; }
                else if ( "last" === which ) { number = pips; }

                // labelValue is the actual value of the pip based on the min/step
                var labelValue = min + ( slider.options.step * number );

                // classLabel replaces any decimals with hyphens
                var classLabel = labelValue.toString().replace(".","-");

                // We need to set the human-readable label to either the
                // corresponding element in the array, or the appropriate
                // item in the object... or an empty string.

                if( $.type(options.labels) === "array" ) {
                    label = options.labels[number] || "";
                }

                else if( $.type( options.labels ) === "object" ) {

                    // set first label
                    if( "first" === which ) {
                        label = options.labels.first || "";
                    }

                    // set last label
                    else if( "last" === which ) {
                        label = options.labels.last || "";
                    }

                    // set other labels, but our index should start at -1
                    // because of the first pip.
                    else if( $.type( options.labels.rest ) === "array" ) {
                        label = options.labels.rest[ number - 1 ] || "";
                    }

                    // urrggh, the options must be f**ked, just show nothing.
                    else {
                        label = labelValue;
                    }
                }
                else {
                    label = labelValue;
                }

                // First Pip on the Slider
                if ( "first" === which ) {
                    percent = "0%";

                    classes += " ui-slider-pip-first";
                    classes += ( "label" === options.first ) ? " ui-slider-pip-label" : "";
                    classes += ( false === options.first ) ? " ui-slider-pip-hide" : "";

                // Last Pip on the Slider
                } else if ( "last" === which ) {
                    percent = "100%";

                    classes += " ui-slider-pip-last";
                    classes += ( "label" === options.last ) ? " ui-slider-pip-label" : "";
                    classes += ( false === options.last ) ? " ui-slider-pip-hide" : "";

                // All other Pips
                } else {
                    percent = ((100/pips) * which).toFixed(4) + "%";

                    classes += ( "label" === options.rest ) ? " ui-slider-pip-label" : "";
                    classes += ( false === options.rest ) ? " ui-slider-pip-hide" : "";

                }

                classes += " ui-slider-pip-" + classLabel;

                // add classes for the initial-selected values.
                if ( values && values.length ) {
                    for( i = 0; i < values.length; i++ ) {
                        if ( labelValue === values[i] ) {
                            classes += " ui-slider-pip-initial-" + (i+1);
                            classes += " ui-slider-pip-selected-" + (i+1);
                        }
                    }

                    if ( slider.options.range ) {
                        if( labelValue > values[0] &&
                            labelValue < values[1] ) {
                            classes += " ui-slider-pip-inrange";
                        }
                    }
                } else {
                    if ( labelValue === value ) {
                        classes += " ui-slider-pip-initial";
                        classes += " ui-slider-pip-selected";
                    }
                }

                css = ( slider.options.orientation === "horizontal" ) ?
                    "left: "+ percent :
                    "bottom: "+ percent;

                // add this current pip to the collection
                return  "<span class=\""+classes+"\" style=\""+css+"\">"+
                            "<span class=\"ui-slider-line\"></span>"+
                            "<span class=\"ui-slider-label\" data-value=\""+labelValue+"\">"+ options.formatLabel(label) +"</span>"+
                        "</span>";

            }

            // we don't want the step ever to be a floating point.
            slider.options.pipStep = Math.round( slider.options.pipStep );

            // create our first pip
            collection += createPip("first");

            // for every stop in the slider; we create a pip.
            for( p = 1; p < pips; p++ ) {
                if( p % slider.options.pipStep === 0 ) {
                    collection += createPip( p );
                }
            }

            // create our last pip
            collection += createPip("last");

            // append the collection of pips.
            slider.element.append( collection );

            // store the pips for setting classes later.
            $pips = slider.element.find(".ui-slider-pip");

            // loop through all the mousedown handlers on the slider,
            // and store the original namespaced (.slider) event handler so
            // we can trigger it later.
            for( j = 0; j < mousedownHandlers.length; j++ ) {
                if( mousedownHandlers[j].namespace === "slider" ) {
                    originalMousedown = mousedownHandlers[j].handler;
                }
            }

            // unbind the mousedown.slider event, because it interferes with
            // the labelClick() method (stops smooth animation), and decide
            // if we want to trigger the original event based on which element
            // was clicked.
            slider.element
                .off("mousedown.slider")
                .on("mousedown.selectPip", function(e) {
                    var $target = $(e.target),
                        closest = getClosestHandle( $target.data("value") ),
                        $handle = $handles.eq( closest );

                    $handle.addClass("ui-state-active");

                    if( $target.is(".ui-slider-label") ) {
                        labelClick( $target );

                        slider.element
                            .one("mouseup.selectPip", function() {
                                $handle
                                    .removeClass("ui-state-active")
                                    .focus();
                            });
                    } else {
                        originalMousedown(e);
                    }
                });

            slider.element
                .on( "slide.selectPip slidechange.selectPip", function(e,ui) {
                    applySliders();

                    var $slider = $(this),
                        value = $slider.slider("value"),
                        values = $slider.slider("values");

                    if ( ui ) {
                        value = ui.value;
                        values = ui.values;
                    }

                    if ( values && values.length ) {
                        selectPip.range( values );
                    } else {
                        selectPip.single( value );
                    }
                });
        },




        float: function( settings ) {

            var i,
                slider = this,
                min = slider._valueMin(),
                max = slider._valueMax(),
                value = slider._value(),
                values = slider._values(),
                tipValues = [],
                $handles = slider.element.find(".ui-slider-handle");

            var options = {

                handle: true,
                // false

                pips: false,
                // true

                labels: false,
                // [array], { first: "string", rest: [array], last: "string" }, false

                prefix: "",
                // "", string

                suffix: "",
                // "", string

                event: "slidechange slide",
                // "slidechange", "slide", "slidechange slide"

                formatLabel: function(value) {
                    return this.prefix + value + this.suffix;
                }
                // function
                // must return a value to display in the floats

            };

            $.extend( options, settings );

            if ( value < min ) {
                value = min;
            }

            if ( value > max ) {
                value = max;
            }

            if ( values && values.length ) {

                for( i = 0; i < values.length; i++ ) {

                    if ( values[i] < min ) {
                        values[i] = min;
                    }

                    if ( values[i] > max ) {
                        values[i] = max;
                    }

                }

            }

            // add a class for the CSS
            slider.element
                .addClass("ui-slider-float")
                .find(".ui-slider-tip, .ui-slider-tip-label")
                .remove();

            function getPipLabels( values ) {

                // when checking the array we need to divide
                // by the step option, so we store those values here.

                var vals = [],
                    steppedVals = $.map( values, function(v) {
                        return Math.ceil(( v - min ) / slider.options.step);
                    });

                // now we just get the values we need to return
                // by looping through the values array and assigning the
                // label if it exists.

                if( $.type( options.labels ) === "array" ) {
                    for( i = 0; i < values.length; i++ ) {
                        vals[i] = options.labels[ steppedVals[i] ] || values[i];
                    }
                }

                else if( $.type( options.labels ) === "object" ) {
                    for( i = 0; i < values.length; i++ ) {
                        if( values[i] === min ) {
                            vals[i] = options.labels.first || min;
                        }

                        else if( values[i] === max ) {
                            vals[i] = options.labels.last || max;
                        }

                        else if( $.type( options.labels.rest ) === "array" ) {
                            vals[i] = options.labels.rest[ steppedVals[i] - 1 ] || values[i];
                        }

                        else {
                            vals[i] = values[i];
                        }
                    }
                }

                else {
                    for( i = 0; i < values.length; i++ ) {
                        vals[i] = values[i];
                    }
                }

                return vals;

            }

            // apply handle tip if settings allows.
            if ( options.handle ) {

                // We need to set the human-readable label to either the
                // corresponding element in the array, or the appropriate
                // item in the object... or an empty string.

                tipValues = ( values && values.length ) ?
                    getPipLabels( values ) :
                    getPipLabels( [ value ] );

                for( i = 0; i < tipValues.length; i++ ) {

                    $handles
                        .eq( i )
                        .append( $("<span class=\"ui-slider-tip\">"+ (options.formatLabel(tipValues[i])/10).toFixed(1) +"</span>") );

                }

            }

            if ( options.pips ) {

                // if this slider also has pip-labels, we make those into tips, too.
                slider.element.find(".ui-slider-label").each(function(k,v) {

                    var $this = $(v),
                        val = [ $this.data("value") ],
                        label,
                        $tip;


                    label = options.formatLabel( getPipLabels( val )[0] );

                    // create a tip element
                    $tip =
                        $("<span class=\"ui-slider-tip-label\">" + label + "</span>")
                            .insertAfter( $this );

                });

            }

            // check that the event option is actually valid against our
            // own list of the slider's events.
            if (options.event !== "slide" &&
                options.event !== "slidechange" &&
                options.event !== "slide slidechange" &&
                options.event !== "slidechange slide" ) {

                options.event = "slidechange slide";

            }

            // when slider changes, update handle tip label.
            slider.element.on( options.event , function( e, ui ) {

                var uiValue = ( $.type( ui.value ) === "array" ) ? ui.value : [ ui.value ],
                    val = options.formatLabel( getPipLabels( uiValue )[0] );

                $(ui.handle)
                    .find(".ui-slider-tip")
                    .html( (val/10).toFixed(1) );

            });

        }

    };

    $.extend(true, $.ui.slider.prototype, extensionMethods);

})(jQuery);
