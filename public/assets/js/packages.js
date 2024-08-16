(function ($) {
  "use strict";

  var position = currency_info.base_currency_symbol_position;
  var symbol = currency_info.base_currency_symbol;

  // search package by giving input in search field
  $('#searchInput').on('keypress', function (event) {
    // check whether 'enter' key is pressed or not
    if (event.which == 13) {
      var searchVal = $(this).val();

      $('#searchKey').val(searchVal);
      $('#submitBtn').trigger('click');
    }
  });

  // search package by days
  $('#days').on('change', function () {
    var value = $(this).val();

    $('#daysKey').val(value);
    $('#submitBtn').trigger('click');
  });

  // search package by persons
  $('#persons').on('change', function () {
    var value = $(this).val();

    $('#personsKey').val(value);
    $('#submitBtn').trigger('click');
  });

  // search package by sorting
  $('#sortType').on('change', function () {
    var value = $(this).val();

    $('#sortKey').val(value);
    $('#submitBtn').trigger('click');
  });

  // search package by giving location name as input in search field
  $('#locationSearchInput').on('keypress', function (event) {
    // check whether 'enter' key is pressed or not
    if (event.which == 13) {
      var locationVal = $(this).val();

      $('#locationKey').val(locationVal);
      $('#submitBtn').trigger('click');
    }
  });

  // package price range slider
  $('#slider-range').slider({
    range: true,
    min: minprice,
    max: maxprice,
    values: priceValues,
    slide: function (event, ui) {
      //while the slider moves, then this function will show that range value
      $('#amount').val((position == 'left' ? symbol + ' ' : '') + ui.values[0] + (position == 'right' ? ' ' + symbol : '') + ' - ' + (position == 'left' ? symbol + ' ' : '') + ui.values[1] + (position == 'right' ? ' ' + symbol : ''));
    }
  });

  // initially this is showing the price range value
  $('#amount').val((position == 'left' ? symbol + ' ' : '') + $('#slider-range').slider('values', 0) + (position == 'right' ? ' ' + symbol : '') + ' - ' + (position == 'left' ? symbol + ' ' : '') + $('#slider-range').slider('values', 1) + (position == 'right' ? ' ' + symbol : ''));

  // search package by filtering the price
  $('#slider-range').on('slidestop', function () {
    var filterPrice = $('#amount').val();

    filterPrice = filterPrice.split('-');
    var minCost = parseFloat(filterPrice[0].replace('$', ' '));
    var maxCost = parseFloat(filterPrice[1].replace('$', ' '));

    $('#minPriceKey').val(minCost);
    $('#maxPriceKey').val(maxCost);
    $('#submitBtn').trigger('click');
  });
})(jQuery);
