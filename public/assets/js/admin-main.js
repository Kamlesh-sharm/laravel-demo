$(function ($) {
  "use strict";

  WebFont.load({
    google: { "families": ["Lato:300,400,700,900"] },
    custom: { "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: [mainURL + '/assets/css/fonts.min.css'] },
    active: function () {
      sessionStorage.fonts = true;
    }
  });

  /*****************************************************
    ==========Bootstrap Notify start==========
    ******************************************************/
  function bootnotify(message, title, type) {
    var content = {};

    content.message = message;
    content.title = title;
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: type,
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000
    });
  }
  /*****************************************************
  ==========Bootstrap Notify end==========
  ******************************************************/

  //account status check
  if (account_status == 1 || secret_login == 1) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  } else {
    $.ajaxSetup({
      beforeSend: function (jqXHR, settings) {
        if (settings.type == 'POST' && status == 0) {
          if ($(".request-loader").length > 0) {
            $(".request-loader").removeClass('show');
          }
          if ($(".modal").length > 0) {
            $(".modal").modal('hide');
          }
          if ($("button[disabled='disabled']").length > 0) {
            $("button[disabled='disabled']").removeAttr('disabled');
          }

          let content = {};

          content.message = 'Your account needs Admin approval!';
          content.title = 'Warning!';
          content.icon = 'fa fa-bell';

          $.notify(content, {
            type: 'warning',
            placement: {
              from: 'top',
              align: 'right'
            },
            showProgressbar: true,
            time: 1000,
            delay: 4000
          });

          jqXHR.abort(event);
        }
      },
      complete: function () {
        // hide progress spinner
        console.log('after ajax sent');
      }
    });
  }
  //account status check end

  /* ***************************************************
  ==========datatables start==========
  ******************************************************/
  $('#basic-datatables').DataTable();
  /* ***************************************************
  ==========datatables end==========
  ******************************************************/

  // Sidebar Search

  $(".sidebar-search").on('input', function () {
    let term = $(this).val().toLowerCase();

    if (term.length > 0) {
      $(".sidebar ul li.nav-item").each(function (i) {
        let menuName = $(this).find("p").text().toLowerCase();
        let $mainMenu = $(this);

        // if any main menu is matched
        if (menuName.indexOf(term) > -1) {
          $mainMenu.removeClass('d-none');
          $mainMenu.addClass('d-block');
        } else {
          let matched = 0;
          let count = 0;
          // search sub-items of the current main menu (which is not matched)
          $mainMenu.find('span.sub-item').each(function (i) {
            // if any sub-item is matched  of the current main menu, set the flag
            if ($(this).text().toLowerCase().indexOf(term) > -1) {
              count++;
              matched = 1;
            }
          });


          // if any sub-item is matched  of the current main menu (which is not matched)
          if (matched == 1) {
            $mainMenu.removeClass('d-none');
            $mainMenu.addClass('d-block');
          } else {
            $mainMenu.removeClass('d-block');
            $mainMenu.addClass('d-none');
          }
        }
      });
    } else {
      $(".sidebar ul li.nav-item").addClass('d-block');
    }
  });

  /*****************************************************************
  ==========disabling default behave of form submits start==========
  *****************************************************************/
  $("#ajaxEditForm").attr('onsubmit', 'return false');
  $("#ajaxForm").attr('onsubmit', 'return false');
  /***************************************************************
  ==========disabling default behave of form submits end==========
  ***************************************************************/


  /******************************************************
  ==========bootstrap datepicker start==========
  ******************************************************/
  $('.datepicker').datepicker({
    autoclose: true
  });
  $('.timepicker').each(function () {
    let interval = $(this).data('interval') ? $(this).data('interval') : 60;
    let start = $(this).data('start') ? $(this).data('start') : 60;

    $(this).timepicker({
      timeFormat: 'h:mm p',
      interval: interval,
      startTime: start
    });
  });
  /*****************************************************
  ==========bootstrap datepicker end==========
  ******************************************************/


  /******************************************************
  ==========dm uploader single file upload start=========
  ******************************************************/
  function ui_single_update_active(element, active) {
    element.find('div.progress').toggleClass('d-none', !active);
    element.find('.progressbar').toggleClass('d-none', active);

    element.find('input[type="file"]').prop('disabled', active);
    element.find('.btn').toggleClass('disabled', active);

    element.find('.btn i').toggleClass('fa-circle-o-notch fa-spin', active);
    element.find('.btn i').toggleClass('fa-folder-o', !active);
  }

  function ui_single_update_progress(element, percent, active) {
    active = (typeof active === 'undefined' ? true : active);

    var bar = element.find('div.progress-bar');

    bar.width(percent + '%').attr('aria-valuenow', percent);
    bar.toggleClass('progress-bar-striped progress-bar-animated', active);

    if (percent === 0) {
      bar.html('');
    } else {
      bar.html(percent + '%');
    }
  }

  function ui_single_update_status(element, message, color) {
    color = (typeof color === 'undefined' ? 'muted' : color);

    element.find('small.status').prop('class', 'status text-' + color).html(message);
  }


  $('.drag-and-drop-zone').each(function (i) {
    let $this = $(this);

    $this.dmUploader({
      url: $this.attr('action'),
      multiple: false,
      allowedTypes: 'image/*',
      extFilter: ['jpg', 'jpeg', 'png'],
      onDragEnter: function () {
        // Happens when dragging something over the DnD area
        this.addClass('active');
      },
      onDragLeave: function () {
        // Happens when dragging something OUT of the DnD area
        this.removeClass('active');
      },
      onInit: function () {
        // Plugin is ready to use
        this.find('.progressbar').val('');
      },
      onComplete: function () {
        // All files in the queue are processed (success or error)
      },
      onNewFile: function (id, file) {
        // When a new file is added using the file selector or the DnD area
        if (typeof FileReader !== "undefined") {
          var reader = new FileReader();
          var img = this.find('img');

          reader.onload = function (e) {
            img.attr('src', e.target.result);
          }
          reader.readAsDataURL(file);
        }
      },
      onBeforeUpload: function (id) {
        // about to start uploading a file
        ui_single_update_progress(this, 0, true);
        ui_single_update_active(this, true);
        ui_single_update_status(this, 'Uploading...');
      },
      onUploadProgress: function (id, percent) {
        // Updating file progress
        ui_single_update_progress(this, percent);
      },
      onUploadSuccess: function (id, data) {
        var response = JSON.stringify(data);

        let ems = document.getElementsByClassName('em');
        for (let i = 0; i < ems.length; i++) {
          ems[i].innerHTML = '';
        }

        // if only the image is being stored
        if (data.status == "success") {
          bootnotify(data.image + " updated successfully!", 'Success!', 'success');
          ui_single_update_active(this, false);
          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded Successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload Completed.', 'success');
        }


        // if the image is being stored along with other form fields
        else if (data.status == "session_image") {
          $("#image").attr('name', data.image);
          $("#image").val(data.filename);

          $("#editImage").attr('name', data.image);
          $("#editImage").val(data.filename);
          ui_single_update_active(this, false);

          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded Successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload Completed.', 'success');
        }

        // if you need a reload after image store
        else if (data.status == "reload") {
          ui_single_update_active(this, false);
          // You should probably do something with the response data, we just show it
          this.find('.progressbar').val("Uploaded Successfully");
          this.find('.form-control[readonly]').attr('style', 'background-color: #28a745 !important; text-alignment: center !important; opacity: 1 !important;border: none !important;');
          ui_single_update_status(this, 'Upload Completed.', 'success');
          location.reload();
        }

        // if error is returned while storing image
        else if (typeof data.errors.error != 'undefined') {
          if (typeof data.errors.file != 'undefined') {
            document.getElementById('err_' + data.id).innerHTML = data.errors.file[0];
          }
        }
      },
      onUploadError: function (id, xhr, status, message) {
        // Happens when an upload error happens
        ui_single_update_active(this, false);
        ui_single_update_status(this, 'Error: ' + message, 'danger');
      },
      onFallbackMode: function () {
        // When the browser doesn't support this plugin :(
      },
      onFileSizeError: function (file) {
        ui_single_update_status(this, 'File excess the size limit', 'danger');
      },
      onFileTypeError: function (file) {
        ui_single_update_status(this, 'File type is not an image', 'danger');
      },
      onFileExtError: function (file) {
        ui_single_update_status(this, 'File extension not allowed', 'danger');
      }
    });
  })
  /*****************************************************
  ==========dm uploader single file upload end==========
  ******************************************************/


  /*****************************************************
  ==========fontawesome icon picker start==========
  ******************************************************/
  $('.icp-dd').iconpicker();
  /* ***************************************************
  ==========fontawesome icon picker end============
  ******************************************************/

  /*****************************************************
  ==========lfm image icon for summernote start=========
  ******************************************************/
  var ImageButton = function (context) {
    var ui = $.summernote.ui;
    var button = ui.button({
      contents: '<i class="far fa-images"></i>',
      tooltip: 'File Manager',
      click: function () {
        let id = context.$note[0].id;
        $('#lfmModalSummernote').find('iframe').attr('src', '');
        $('#lfmModalSummernote').find('iframe').attr('src', mainURL + '/laravel-filemanager?summernote=' + id);
        $('#lfmModalSummernote').modal('show');
      }
    });

    return button.render();
  }
  /*****************************************************
  ==========lfm image icon for summernote end=========
  ******************************************************/


  /*****************************************************
  ==========tinymce initialization start==========
  ******************************************************/
  // summernote initialization start
  $(".summernote").each(function (i) {

    tinymce.init({
      selector: '.summernote',
      plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      promotion: false,
      mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
      ]
    });

  });


  $(document).on('click', ".note-video-btn", function () {
    let i = $(this).index();

    if ($(".summernote").eq(i).parents(".modal").length > 0) {
      setTimeout(() => {
        $("body").addClass('modal-open');
      }, 500);
    }
  });
  /*****************************************************
  ==========Summernote initialization end==========
  ******************************************************/

  // Change Input Direction Start
  $('select[name="language_id"]').on('change', function () {
    $('.request-loader').addClass('show');

    let rtlURL = baseUrl + "/admin/rtlcheck/" + $(this).val();

    // send ajax request to check whether the selected language is 'rtl' or not
    $.get(rtlURL, function (response) {
      $('.request-loader').removeClass('show');

      if (response == 1) {
        $('form.create input').each(function () {
          if (!$(this).hasClass('ltr')) {
            $(this).addClass('rtl');
          }
        });

        $('form.create select').each(function () {
          if (!$(this).hasClass('ltr')) {
            $(this).addClass('rtl');
          }
        });

        $('form.create textarea').each(function () {
          if (!$(this).hasClass('ltr')) {
            $(this).addClass('rtl');
          }
        });

        $('form.create .note-editor.note-frame .note-editing-area .note-editable').each(function () {
          if (!$(this).hasClass('ltr')) {
            $(this).addClass('rtl');
          }
        });
      } else {
        $('form.create input, form.create select, form.create textarea, form.create .note-editor.note-frame .note-editing-area .note-editable').removeClass('rtl');
      }
    });
  });
  // Change Input Direction End

  // select2 start
  $('.select2').select2();
  // select2 end


  /******************************************************
  ==========Form Submit with AJAX Request Start==========
  ******************************************************/
  $("#submitBtn").on('click', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxForm = document.getElementById('ajaxForm');
    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm").attr('action');
    let method = $("#ajaxForm").attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      let index = i;

      let $toInput = $('.form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });


    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        })

        if (data == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#submitBtn2").on('click', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxForm = document.getElementById('ajaxForm2');
    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm2").attr('action');
    let method = $("#ajaxForm2").attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      let index = i;

      let $toInput = $('.form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });


    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        })

        if (data == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#permissionBtn").on('click', function () {
    $("#permissionsForm").trigger("submit");
  });
  /******************************************************
  ==========Form Submit with AJAX Request End==========
  ******************************************************/



  /********************************************************************
  ==========Form Prepopulate After Clicking Edit Button Start=========
  ********************************************************************/
  $(".editBtn").on('click', function () {
    let datas = $(this).data();
    delete datas['toggle'];

    for (let x in datas) {
      if ($("#in_" + x).hasClass('summernote')) {
        tinyMCE.get("in_" + x).setContent(datas[x]);
      } else if ($("#in_" + x).data('role') == 'tagsinput') {
        if (datas[x].length > 0) {
          let arr = datas[x].split(" ");
          for (let i = 0; i < arr.length; i++) {
            $("#in_" + x).tagsinput('add', arr[i]);
          }
        } else {
          $("#in_" + x).tagsinput('removeAll');
        }
      } else if ($("input[name='" + x + "']").attr('type') == 'radio') {
        $("input[name='" + x + "']").each(function (i) {
          if ($(this).val() == datas[x]) {
            $(this).prop('checked', true);
          }
        });
      } else if ($("#in_" + x).hasClass('select2')) {
        $("#in_" + x).val(datas[x]);
        $("#in_" + x).trigger('change');
      } else {
        $("#in_" + x).val(datas[x]);
        $('.brand-img').attr('src', datas['brand_img']);
        $('.gallery-img').attr('src', datas['gallery_img']);
      }
    }


    // focus & blur colorpicker inputs
    setTimeout(() => {
      $(".jscolor").each(function () {
        $(this).focus();
        $(this).blur();
      });
    }, 300);
  });
  /******************************************************************
  ==========Form Prepopulate After Clicking Edit Button End==========
  ******************************************************************/


  /******************************************************************
  ==========Form Prepopulate After Clicking Location Button Start====
  ******************************************************************/
  $('.locationBtn').on('click', function () {
    let info = $(this).data();

    $('#package-id-location').val(info.id);
  });
  /******************************************************************
  ==========Form Prepopulate After Clicking Location Button End======
  ******************************************************************/


  /******************************************************************
  ==========Form Prepopulate After Clicking Plan Button Start========
  ******************************************************************/
  $('.planBtn').on('click', function () {
    let info = $(this).data();

    if (info.plan_type == 'daywise') {
      $('#addDaywisePlanModal').modal('show');
      $('#package-id-daywise-plan').val(info.id);
    } else if (info.plan_type == 'timewise') {
      $('#addTimewisePlanModal').modal('show');
      $('#package-id-timewise-plan').val(info.id);
    }
  });
  /******************************************************************
  ==========Form Prepopulate After Clicking Plan Button End==========
  ******************************************************************/


  /**************************************************************
  ==========Form Prepopulate After Clicking Mail Button Start====
  **************************************************************/
  $('.mailBtn').on('click', function () {
    let info = $(this).data();

    $('#mail-id').val(info.customer_email);
  });
  /**************************************************************
  ==========Form Prepopulate After Clicking Mail Button End======
  **************************************************************/


  /***********************************************************************
  ==========Form Submit with AJAX Request For Daywise Plan Start==========
  ***********************************************************************/
  $('#daywise-plan-submit-btn').on('click', function (e) {
    $(e.target).attr('disabled', true);
    $('.request-loader').addClass('show');

    let ajaxForm = document.getElementById('daywise-plan-ajax-form');
    let fd = new FormData(ajaxForm);
    let url = $('#daywise-plan-ajax-form').attr('action');
    let method = $('#daywise-plan-ajax-form').attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      if ($(this).hasClass('summernote')) {
        let content = tinyMCE.activeEditor.getContent();

        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        })

        location.reload();
      },
      error: function (error) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }
      }
    });
  });
  /*********************************************************************
  ==========Form Submit with AJAX Request For Daywise Plan End==========
  *********************************************************************/


  /***********************************************************************
  ==========Form Submit with AJAX Request For Timewise Plan Start=========
  ***********************************************************************/
  $('#timewise-plan-submit-btn').on('click', function (e) {
    $(e.target).attr('disabled', true);
    $('.request-loader').addClass('show');

    let ajaxForm = document.getElementById('timewise-plan-ajax-form');
    let fd = new FormData(ajaxForm);
    let url = $('#timewise-plan-ajax-form').attr('action');
    let method = $('#timewise-plan-ajax-form').attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      if ($(this).hasClass('summernote')) {
        let content = tinyMCE.activeEditor.getContent();

        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        })

        location.reload();
      },
      error: function (error) {
        $(e.target).attr('disabled', false);
        $('.request-loader').removeClass('show');

        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('err_' + x).innerHTML = error.responseJSON.errors[x][0];
        }
      }
    });
  });
  /***********************************************************************
  ==========Form Submit with AJAX Request For Timewise Plan End===========
  ***********************************************************************/


  /******************************************************
  ==========Form Update with AJAX Request Start==========
  ******************************************************/
  $("#updateBtn").on('click', function (e) {
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);
    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      let index = i;

      let $toInput = $('.form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);

        $('.em').each(function () {
          $(this).html('');
        })

        if (data == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('editErr_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });


  $("#updateBtn2").on('click', function (e) {
    $(".request-loader").addClass("show");

    if ($(".iconpicker-component").length > 0) {
      $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
    }

    let ajaxEditForm2 = document.getElementById('ajaxEditForm2');
    let fd = new FormData(ajaxEditForm2);
    let url = $("#ajaxEditForm2").attr('action');
    let method = $("#ajaxEditForm2").attr('method');

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      let index = i;

      let $toInput = $('.form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();

        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);

        $('.em').each(function () {
          $(this).html('');
        })

        if (data == 'success') {
          location.reload();
        }
      },
      error: function (error) {
        $('.em').each(function () {
          $(this).html('');
        });

        for (let x in error.responseJSON.errors) {
          document.getElementById('editErr_' + x).innerHTML = error.responseJSON.errors[x][0];
        }

        $('.request-loader').removeClass('show');
        $(e.target).attr('disabled', false);
      }
    });
  });
  /******************************************************
  ==========Form Update with AJAX Request End==========
  ******************************************************/


  /******************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $('.deleteBtn').on('click', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteForm").trigger('submit');
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /******************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/

  // update payment status Using AJAX Request Start
  $('.paymentStatusBtn').on('change', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, Change Status',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".paymentStatusForm").submit();
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
        window.location.reload();
      }
    });
  });
  // update payment status Using AJAX Request End

  // withdraw payment status
  $('.withdrawStatusBtn').on('click', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        var url = $(this).attr('href');
        window.location.href = url;
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  // withdraw payment status end


  /*****************************************************
  ==========Close Ticket Using AJAX Request Start======
  ******************************************************/
  $('.close-ticket').on('click', function (e) {
    e.preventDefault();
    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You want to close this ticket",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, close it',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $("#closeForm").submit();
        $(".request-loader").removeClass("show");
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /******************************************************
  ==========Close Ticket Using AJAX Request End==========
  ******************************************************/


  /*****************************************************
  ==========Bulk Delete Using AJAX Request Start========
  ******************************************************/
  $(".bulk-check").on('change', function () {
    let val = $(this).data('val');
    let checked = $(this).prop('checked');

    // if selected checkbox is 'all' then check all the checkboxes
    if (val == 'all') {
      if (checked) {
        $(".bulk-check").each(function () {
          $(this).prop('checked', true);
        });
      } else {
        $(".bulk-check").each(function () {
          $(this).prop('checked', false);
        });
      }
    }


    // if any checkbox is checked then flag = 1, otherwise flag = 0
    let flag = 0;

    $(".bulk-check").each(function () {
      let status = $(this).prop('checked');

      if (status) {
        flag = 1;
      }
    });

    // if any checkbox is checked then show the delete button
    if (flag == 1) {
      $(".bulk-delete").addClass('d-inline-block');
      $(".bulk-delete").removeClass('d-none');
    } else {
      // if no checkbox is checked then hide the delete button
      $(".bulk-delete").removeClass('d-inline-block');
      $(".bulk-delete").addClass('d-none');
    }
  });

  $('.bulk-delete').on('click', function () {
    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(".request-loader").addClass('show');
        let href = $(this).data('href');
        let ids = [];

        // take ids of checked one's
        $(".bulk-check:checked").each(function () {
          if ($(this).data('val') != 'all') {
            ids.push($(this).data('val'));
          }
        });

        let fd = new FormData();
        for (let i = 0; i < ids.length; i++) {
          fd.append('ids[]', ids[i]);
        }

        $.ajax({
          url: href,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {
            $(".request-loader").removeClass('show');
            if (data == "success") {
              location.reload();
            }
          }
        });
      } else {
        swal.close();
      }
    });
  });
  /*****************************************************
  ==========Bulk Delete Using AJAX Request End==========
  *****************************************************/


  // LFM scripts START
  window.lfmSliders = [];
  window.closeLfmModal = function (serial) {
    $('#lfmModal' + serial).modal('hide');
    // if any modal is open, then add 'modal-open' class to body
    if ($(".modal.show").length > 0) {
      setTimeout(function () {
        $('body').addClass('modal-open');
      }, 500);
    }
  };
  window.closeLfmModalSummernote = function () {
    $('#lfmModalSummernote').modal('hide');
    // if any modal is open, then add 'modal-open' class to body
    setTimeout(function () {
      if ($(".modal.show").length > 0) {
        $('body').addClass('modal-open');
      }
    }, 500);
  };

  $(`.lfm-modal .fas.fa-times-circle`).on('click', function () {
    $(this).parents('.lfm-modal').modal('hide');
    // if any modal is open, then add 'modal-open' class to body
    setTimeout(function () {
      if ($(".modal.show", parent.document).length > 0) {
        $('body', parent.document).addClass('modal-open');
      }
    }, 500);
  });

  $(`.lfm-modal`).on('click', function (e) {
    if (!$(e.target).hasClass('modal-dialog') && !$(e.target).parents('.modal-dialog').length) {
      // if any modal is open, then add 'modal-open' class to body
      setTimeout(function () {
        if ($(".modal.show", parent.document).length > 0) {
          $('body', parent.document).addClass('modal-open');
        }
      }, 500);
    }
  });

  window.insertImage = function (id, items) {
    items.forEach(function (item) {
      $("#" + id).summernote('insertImage', item);
    });
  };

  $(document).on('click', ".rmvLfmSliderImgs", function () {
    let index = $(this).data('index');
    let serial = $(this).data('serial');

    window.lfmSliders.splice(index, 1);
    window.prevLfmSliderImgs(serial);
  });


  window.prevLfmSliderImgs = function (serial) {
    let imagesDiv = ``;
    let sliderValues = [];

    if (window.lfmSliders.length > 0) {
      window.lfmSliders.forEach(function (slider, index) {

        imagesDiv += `<div class="thumb-preview mr-2 mb-2">
                <i class="fas fa-times-circle rmvLfmSliderImgs" data-index="${index}" data-serial="${serial}"></i>
                <img src="${slider}" alt="Slider Image">
            </div>`;

        sliderValues.push(slider.replace(mainURL + '/', ""));

      });
    }

    $("#sliderThumbs" + serial).html(imagesDiv);

    $("#fileInput" + serial).val(sliderValues);
  };
  // LFM scripts END


  // Uploaded Image Preview Start
  $('.img-input').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });
  $('.img-input2').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-img2').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });
  $('.img-input3').on('change', function (event) {
    let file = event.target.files[0];
    let reader = new FileReader();

    reader.onload = function (e) {
      $('.uploaded-img3').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });
  // Uploaded Image Preview End
});

$(window).on('load', function () {
  $(".summernote").each(function (i) {
    let $this = $(this);
    if ($this.parents(".form-group.rtl").length > 0) {
      $("#" + $this.attr('id') + "_ifr").contents().find('html').attr('dir', 'rtl');
      $("#" + $this.attr('id') + "_ifr").contents().find('body').css('text-align', 'right');
      $("#" + $this.attr('id') + "_ifr").contents().find('body *').css('text-align', 'right');
    }
  });
});

//
let elem = document.querySelector(".messages-container")
if (elem) {
  elem.scrollTop = elem.scrollHeight;
}

//

$('#room_booking_number').on('keypress', function (e) {
  if (e.which === 13) {
    $('#booking_form').submit();
  }
});
$('#room_booking_title').on('keypress', function (e) {
  if (e.which === 13) {
    $('#booking_form').submit();
  }
});

$('#p_booking_id').on('keypress', function (e) {
  if (e.which === 13) {
    $('#BookingForm').submit();
  }
});
$('#p_booking_title').on('keypress', function (e) {
  if (e.which === 13) {
    $('#BookingForm').submit();
  }
});

$(document).ready(function () {
  $("body").on('click', '#vendor_admin_approval', function () {
    if ($('#vendor_admin_approval').is(":checked")) {
      $('.admin_approval_notice').removeClass('d-none');
    } else {
      $('.admin_approval_notice').addClass('d-none');
    }
  });
})

