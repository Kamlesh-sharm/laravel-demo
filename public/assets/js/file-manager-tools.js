"use strict";

var $image = null,
    options = {};

(function ($) {
    var $dataX = $('#dataX'),
        $dataY = $('#dataY'),
        $dataHeight = $('#dataHeight'),
        $dataWidth = $('#dataWidth');

    $image = $('.crop-container > img');
    options = {
        aspectRatio: 16 / 9,
        preview: ".img-preview",
        strict: false,
        crop: function (data) {
            // Output the result data for cropping image.
            $dataX.val(Math.round(data.x));
            $dataY.val(Math.round(data.y));
            $dataHeight.val(Math.round(data.height));
            $dataWidth.val(Math.round(data.width));
        }
    };
    $image.cropper(options);
    
    renderResizedValues($("#width_display").val(), $("#height_display").val());

    $("#resize").resizable({
    aspectRatio: true,
    containment: "#containment",
    handles: "n, e, s, w, se, sw, ne, nw",
    resize: function (event, ui) {
        renderResizedValues(ui.size.width, ui.size.height);
    }
    });

  $('#width_display, #height_display').on('change', function () {
    var newWidth = $("#width_display").val();
    var newHeight = $("#height_display").val();

    renderResizedValues(newWidth, newHeight);
    $("#containment > .ui-wrapper").width(newWidth).height(newHeight);
    $("#resize").width(newWidth).height(newHeight);
  });

  function renderResizedValues(newWidth, newHeight) {
    $("#width").val(newWidth);
    $("#height").val(newHeight);
    $("#width_display").val(newWidth);
    $("#height_display").val(newHeight);

    $('#resize_mobile').css('background-size', '100% 100%');

    if (newWidth < newHeight) {
      $('#resize_mobile').css('width', (newWidth / newHeight * 100) + '%').css('padding-bottom', '100%');
    } else if (newWidth > newHeight) {
      $('#resize_mobile').css('width', '100%').css('padding-bottom', (newHeight / newWidth * 100) + '%');
    } else { // newWidth === newHeight
      $('#resize_mobile').css('width', '100%').css('padding-bottom', '100%');
    }
  }
   
  })(jQuery);
  
  function changeAspectRatio(_this, aspectRatio) {
    options.aspectRatio = aspectRatio;
    $('.btn-aspectRatio.active').removeClass('active');
    $(_this).addClass('active');
    $('.img-preview').removeAttr('style');
    $image.cropper('destroy').cropper(options);
    return false;
}
function performCrop() {
  performLfmRequest('cropimage', {
    img: $("#img").val(),
    working_dir: $("#working_dir").val(),
    dataX: $("#dataX").val(),
    dataY: $("#dataY").val(),
    dataHeight: $("#dataHeight").val(),
    dataWidth: $("#dataWidth").val(),
    type: $('#type').val()
  }).done(loadItems);
}

function performCropNew() {
  performLfmRequest('cropnewimage', {
    img: $("#img").val(),
    working_dir: $("#working_dir").val(),
    dataX: $("#dataX").val(),
    dataY: $("#dataY").val(),
    dataHeight: $("#dataHeight").val(),
    dataWidth: $("#dataWidth").val(),
    type: $('#type').val()
  }).done(loadItems);
}

function moveToNewFolder($folder) {
  $("#notify").modal('hide');
  var items =[];
  $("#items").find("input").each(function() {items.push(this.id)});
  performLfmRequest('domove', {
    items: items,
    goToFolder: $folder
  }).done(refreshFoldersAndItems);
}

function doResize() {
  performLfmRequest('doresize', {
    img: $("#img").val(),
    dataHeight: $("#height").val(),
    dataWidth: $("#width").val()
  }).done(loadItems);
}