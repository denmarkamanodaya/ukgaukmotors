var show_list;
var sort_type = 'time';

$(document).ready(function () {
    loadItems();
});


$(document).on('click', '.file-item', function (e) {
    useFile($(this).data('id'));
});


function performLfmRequest(url, parameter, type) {
    var data = defaultParameters();

    if (parameter != null) {
        $.each(parameter, function (key, value) {
            data[key] = value;
        });
    }

    return $.ajax({
        type: 'GET',
        dataType: type || 'text',
        url: baseurl + '/' + url,
        data: data,
        cache: false
    }).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
}

function displayErrorResponse(jqXHR) {
    notify('<div style="max-height:50vh;overflow: scroll;">' + jqXHR.responseText + '</div>');
}

function loadItems() {
  performLfmRequest('jsonitems', {show_list: show_list, sort_type: 'time'}, 'html')
    .done(function (data) {
        var response = JSON.parse(data);
        $('#content').html(response.html);
    });
}


// ==================================
// ==  Ckeditor, Bootbox, preview  ==
// ==================================

function useFile(file_url) {

  function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    var match = window.location.search.match(reParam);
    return ( match && match.length > 1 ) ? match[1] : null;
  }

  function useTinymce3(url) {
    var win = tinyMCEPopup.getWindowArg("window");
    win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
    if (typeof(win.ImageDialog) != "undefined") {
      // Update image dimensions
      if (win.ImageDialog.getImageData) {
        win.ImageDialog.getImageData();
      }

      // Preview if necessary
      if (win.ImageDialog.showPreviewImage) {
        win.ImageDialog.showPreviewImage(url);
      }
    }
    tinyMCEPopup.close();
  }

  function useTinymce4AndColorbox(url, field_name) {
    parent.document.getElementById(field_name).value = url;

    if(typeof parent.tinyMCE !== "undefined") {
      parent.tinyMCE.activeEditor.windowManager.close();
    }
    if(typeof parent.$.fn.colorbox !== "undefined") {
      parent.$.fn.colorbox.close();
    }
  }

  function useCkeditor3(url) {
    if (window.opener) {
      // Popup
      window.opener.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
    } else {
      // Modal (in iframe)
      parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
      parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorCleanUpFuncNum'));
    }
  }

  function useFckeditor2(url) {
    var p = url;
    var w = data['Properties']['Width'];
    var h = data['Properties']['Height'];
    window.opener.SetUrl(p,w,h);
  }

  var url = file_url;
  var field_name = getUrlParam('field_name');
  var is_ckeditor = getUrlParam('CKEditor');
  var is_fcke = typeof data != 'undefined' && data['Properties']['Width'] != '';
  var file_path = url.replace(route_prefix, '');

  if (window.opener || window.tinyMCEPopup || field_name || getUrlParam('CKEditorCleanUpFuncNum') || is_ckeditor) {
    if (window.tinyMCEPopup) { // use TinyMCE > 3.0 integration method
      useTinymce3(url);
    } else if (field_name) {   // tinymce 4 and colorbox
      useTinymce4AndColorbox(url, field_name);
    } else if(is_ckeditor) {   // use CKEditor 3.0 + integration method
      useCkeditor3(url);
    } else if (is_fcke) {      // use FCKEditor 2.0 integration method
      useFckeditor2(url);
    } else {                   // standalone button or other situations
      window.opener.SetUrl(url, file_path);
    }

    if (window.opener) {
      window.close();
    }
  } else {
    // No WYSIWYG editor found, use custom method.
    window.opener.SetUrl(url, file_path);
  }
}
//end useFile

function defaultParameters() {
  return {
    working_dir: $('#working_dir').val(),
    type: $('#type').val()
  };
}

function notImp() {
  bootbox.alert('Not yet implemented!');;
}

function notify(message) {
  bootbox.alert(message);
}

function fileView(file_url, timestamp) {
  var rnd = makeRandom();
  bootbox.dialog({
    title: lang['title-view'],
    message: $('<img>')
      .addClass('img img-responsive center-block')
      .attr('src', file_url + '?timestamp=' + timestamp),
    size: 'large',
    onEscape: true,
    backdrop: true
  });
}

function makeRandom() {
  var text = '';
  var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

  for (var i = 0; i < 20; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
}

$('#upload2').click(function () {
    $('#uploadModal').modal('show');
});

$('#upload-btn').click(function () {
    $(this).html('')
        .append($('<i>').addClass('fa fa-refresh fa-spin'))
        .append(" Uploading" )
        .addClass('disabled');

    function resetUploadForm() {
        $('#uploadModal').modal('hide');
        $('#upload-btn').html('Upload').removeClass('disabled');
        $('input#upload').val('');
    }

    $('#uploadForm').ajaxSubmit({
        success: function (data, statusText, xhr, $form) {
            resetUploadForm();
            loadItems();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            displayErrorResponse(jqXHR);
            resetUploadForm();
        }
    });
});
