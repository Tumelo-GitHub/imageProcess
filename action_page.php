<?php

class imageProcess{

  // private property
  private $width;
  private $height;
  private $image;
  private $image_type;

  public function __construct(){

  }

  // form submit
  public function create(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // sanitize post input
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      // get input
      $image_name = $_FILES['image']['name'];
      // load image
      $this->loadImage($image_name);
      // crop image squre
      $this->cropImageSquare($image_name);
      // save image
      $this->save();
    }else{

    }
  }

  private function loadImage($file){
    // get image info
    $image_info = getimagesize($file);

    // get image size
    list($width, $height) = $image_info;
    $this->width = $width;
    $this->height = $height;

    // get file type
    $this->image_type = $image_info[2];

    // check image type ONLY JPG and PNG
    if($this->image_type == IMAGETYPE_JPEG){
      $this->image = imagecreatefromjpeg($file);
    }elseif($this->image_type == IMAGETYPE_PNG){
      $this->image = imagecreatefrompng($file);
    }
    
  }

  function resize($width,$height) {
    // create resized image
    $new_image = imagecreatetruecolor($width, $height);
    // Copy and resize part of an image
    imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
    // load image in variable
    $this->image = $new_image;
 }

  // crop to max width or hight
  private function cropImage($image, $w, $h){
    // check file type
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

  private function save($path, $file){
    // 
    if(move_uploaded_file($path, $file)){
      return true;
    }else{
      return false;
    }
  }
}