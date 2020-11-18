<?php

function upload_file($foto)
{
  // upload file
  $fotoData = base64_decode($foto);
  $f = finfo_open();
  $mime_type = finfo_buffer($f, $fotoData, FILEINFO_MIME_TYPE);
  finfo_close($f);
  $acceptType = ['image/jpeg', 'image/png', 'image/jpg'];
  if (in_array($mime_type, $acceptType)) {
    [$image, $imageType] = explode('/', $mime_type);
    $fileName = uniqid('img_') . time() . "_" . rand(1, 100) . ".$imageType";
    $filePath = FCPATH . 'uploads/' . $fileName;
    $image = file_put_contents($filePath, $fotoData);
    $ImageSize = filesize($filePath) / 1024;
    // Ukuruan maximum file 2MB
    $maxFotoSize = 2;
    if ($ImageSize > $maxFotoSize * 1024) {
      unlink($filePath);
      throw new Exception("Foto harus kurang dari " . $maxFotoSize . "MB");
    } else {
      return $fileName;
    }
  } else {
    throw new Exception("tipe foto yang diperbolehkan adalah " . implode(", ", $acceptType));
  }
}
