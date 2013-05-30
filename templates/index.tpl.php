<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <title>Pingur @ deadbeefcafe</title>
  <link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" charset="utf-8">
</head>
<body>
  
<input id="fileupload" type="file" name="files[]" data-url="upload" multiple>

<div id="image-container">
<?php

foreach($images as $image) {
  echo '<div class="image"><a href="' . $image->filename .'"><img src="' . $image->thumbnail . '" /></a></div>';
}

?>

</div>

<div id="drop-zone">
  <div class="inner"><p>DROP FILES TO UPLOAD</p></div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="resources/js/vendor/jquery.ui.widget.js"></script>
<script src="resources/js/jquery.iframe-transport.js"></script>
<script src="resources/js/jquery.fileupload.js"></script>
<script src="resources/js/jquery.knob.js"></script>
<script>
$(function () {
  
  function renderUpload(files) {
    var elems = [];
    $.each(files, function(i, file) {
      var el = $('<div class="image uploading">Loading preview...</div>')
        .prependTo('#image-container');
      var reader = new FileReader();
      reader.onload = function(e) {
        el.html('<img src="' + e.target.result + '" />');
      };
      reader.readAsDataURL(file);
      elems.push(el);
    });
    return elems;
  }
  
  // Begin setting up the drag handlers
  // this is just for the visuals
  
  // on the body
  $(document).on('dragover', function() {
    $('body').addClass('target');
  });
  
  // on the drop zone
  $('#drop-zone').on('dragleave', function() {
    $('body').removeClass('target');
  }).on('drop', function() {
    $('body').removeClass('target');
  })
  
  //
  $('#fileupload').fileupload({
    dataType: 'json',
    dropZone: $('#drop-zone'),
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    add: function(e, data) {
      data.context = renderUpload(data.files);
      data.submit();
    },
    done: function(e, data) {
      $.each(data.result, function(i, val) {
        data.context[i].html('<a href="'+val.filename+'"><img src="'+val.thumbnail+'"></a>').removeClass('uploading');
      });
    }
  });

  
});
</script>
</body> 
</html>