<?php

class imageProcess{

  // private property
  private $width;
  private $hight;

  public function __construct(){

  }

  // form submit
  public function create(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // sanitize post input
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // get input
      $image_name = $_FILES['image']['name'];
      $image_type = $_FILES['image']['type'];
      $image_size = $_FILES['image']['size'];

      // check file type
      if($image_type == 'image/jpg' || $image_type == 'image/png'){
        // process image
      }else{
        // throw an error
        die('Image not file type required');
      }
      
    }else{

    }
  }

  // resize image propotionately
  private function resizeImage($image, $width, $height){
    $info = getimagesize($image);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw new Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
            unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");
  }

  // crop to max width or hight
  private function cropImage($image, $w, $h){
    // check file type


    $im = imagecreatefrompng('example.png');
    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $w, 'height' => $h]);
    if ($im2 !== FALSE) {
        imagepng($im2, 'example-cropped.png');
        imagedestroy($im2);
    }
    imagedestroy($im);
  }

  // crop to square
  private function cropImageSquare($image){
    // check file type

    if($image['type'] == 'image/png'){
      $im = imagecreatefrompng('example.png');
      $size = min(imagesx($im), imagesy($im));
      $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
      if ($im2 !== FALSE) {
          imagepng($im2, 'example-cropped.png');
          imagedestroy($im2);
      }
      imagedestroy($im);
    }elseif($image['type'] == 'image/jpg'){
      $im = imagecreatefromjpeg('example.png');
      $size = min(imagesx($im), imagesy($im));
      $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
      if ($im2 !== FALSE) {
          imagejpeg($im2, 'example-cropped.png');
          imagedestroy($im2);
      }
      imagedestroy($im);
    }
  }

  private function checkImageType($image){

  }
}

// list($width, $height) = getimagesize($_FILES['image']['name']);

// echo 'width : '. $width; 