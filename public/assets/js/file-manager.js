(function ($) {
  "use strict";

  const urlParams = new URLSearchParams(window.location.search);
  const serial = urlParams.get('serial');
  const summernote = urlParams.get('summernote') ? true : false;

  var lfmSlidersChecker = setInterval(function() {
      if(window.parent.lfmSliders) {
        if (urlParams.get('room')) {
          let id = urlParams.get('room');
    
          $.get(mainURL + "/admin/rooms_management/slider_images/" + id, function(datas) {
            datas.forEach(function(data) {
              window.parent.lfmSliders.push(data);
            });
    
            window.parent.prevLfmSliderImgs(serial);
          });
        } else if (urlParams.get('package')) {
          let id = urlParams.get('package');
    
          $.get(mainURL + "/admin/packages_management/slider_images/" + id, function(datas) {
            datas.forEach(function(data) {
              window.parent.lfmSliders.push(data);
            });
    
            window.parent.prevLfmSliderImgs(serial);
          });
        }
        clearInterval(lfmSlidersChecker);
      }
  }, 500);


  // After clicking on 'Confirm' Button in LFM
  $(document).on('click', 'a[data-action="use"]', function(e) {
    e.preventDefault();

    if (!summernote) {
      let multiple = $('#chooseImage' + serial, parent.document).data('multiple');
      let video = $('#chooseVideo' + serial, parent.document).length > 0 ? $('#chooseVideo' + serial, parent.document).data('video') : false;

      let item = !multiple ? getOneSelectedElement() : getSelectedItems();
      window.parent.closeLfmModal(serial);

      if (!multiple) {
        let modItemUrl = item.url.replace(mainURL + '/', "");
        if (!video) {
          $("#thumbPreview" + serial, parent.document).find('img').attr('src', item.url);
          $("#fileInput" + serial, parent.document).val(modItemUrl);
        } else {
          $("#videoPreview" + serial, parent.document).find('source').attr('src', item.url);
          $("#videoPreview" + serial + " video", parent.document)[0].load();
          $("#fileInput" + serial, parent.document).val(modItemUrl);
        }
      } else if (multiple) {
        if (item.length > 0) {
          item.forEach(function(it) {
            window.parent.lfmSliders.push(it.url);
          });
        }

        window.parent.prevLfmSliderImgs(serial);
      }
    } else {
      window.parent.closeLfmModalSummernote();
      let id = urlParams.get('summernote');
      let items = getSelectedItems();
      let fd = new FormData();
      items.forEach(function(item) {
        let modItemUrl = item.url.replace(mainURL + '/', ""); 
        fd.append('items[]', modItemUrl);
      });

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: summernoteUpload,
        type: "POST",
        data: fd,
        contentType: false,
        processData: false,
        success: function(data) {
          if (data.status == 'success') {
            window.parent.insertImage(id, data.urls);
          } else if (data.status == 'error') {
            alert(data.message);
          }
        }
      });
    }
  }); 
 
  Dropzone.options.uploadForm = {
      paramName: "upload[]", // The name that will be used to transfer the file
      uploadMultiple: false,
      parallelUploads: 5,
      timeout:0,
      clickable: '#upload-button',
      dictDefaultMessage: lang['message-drop'],
      init: function() {
          var _this = this; // For the closure
          this.on('success', function(file, response) {
          if (response == 'OK') {
              loadFolders();
          } else {
              this.defaultOptions.error(file, response.join('\n'));
          }
          });
      },
      headers: {
          'Authorization': 'Bearer ' + getUrlParam('token')
      },
      acceptedFiles: validMimes,
      maxFilesize: maxFileSize
  }

})(jQuery);
