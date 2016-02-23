<?php

// upload.php
// Uploads a zululog export to the data directory


$targetDir      = "data/";
$targetFile     = $targetDir . 'log.csv';
$uploadFileType = pathinfo($targetFile,PATHINFO_EXTENSION);

$uploadOk = 1;

if(isset($_POST['submit'])) {
  if ($_FILES['fileToUpload']['size'] > 5000000) {
    echo "File too large!";
    $uploadOk = 0;
  } else {
    // it's smaller than 500kb, upload it
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
      echo "Done";
    } else {
      echo "Failed to upload<br />".$targetFile;
      print_r($_FILES['fileToUpload']);
    }
  }
} else {
?>

<html>
  <head>
  </head>
  <body>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="fileToUpload" id="fileToUpload" />
      <input type="submit" value="Upload" name="submit" />
    </form>
  </body>
</html>

<?php
}

?>

