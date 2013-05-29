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
  echo '<div class="image"><div class="inner"><a href="' . $image->filename .'"><img src="' . $image->thumbnail . '" /></a></div></div>';
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
<script>
$(function () {
  
  $('#fileupload').fileupload({
    dataType: 'json',
    done: function (e, data) {
      $.each(data.result.files, function (index, file) {
        $('<p/>').text(file.name).appendTo(document.body);
      });
    }
  });
});
</script>
</body> 
</html>