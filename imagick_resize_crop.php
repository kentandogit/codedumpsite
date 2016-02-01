<?php
/****
- Read images from resource directory and resize them
- Crop an image from the resized images
- From the crop images, create an image with exact dimensions putting the crop image at the center
  if it is smaller than the defined dimensions. (this is what I needed)
*****/
$resource_dir = '/Library/WebServer/Documents/testdir/';

$files = scandir($resource_dir);

foreach($files AS $file)
{
  $image_promo = new Imagick();
  try
  {
    $image_promo->readimage($resource_dir.$file);
    $image_promo->setImageCompressionQuality(100);
    $image_promo->resizeImage(334,0,Imagick::FILTER_LANCZOS,0.9);
    $image_promo->writeImage($resource_dir."resize/".$file);
    $image_promo->destroy();

    $image_promo = new Imagick();
    $image_promo->readimage($resource_dir."resize/".$file);
    $image_promo->cropImage ( 334 , 181 , 0 , 0 );
    $image_promo->setImageCompressionQuality(100);
    $image_promo->writeImage($resource_dir."crop/".$file);
    $image_promo->destroy();

    echo exec('/opt/local/bin/convert '.$resource_dir.'crop/'.$file.' -background white -gravity center -extent 334x181 '.$resource_dir.'crop2/'.$file);
  }
  catch(Exception $e)
  {
    echo $e->getMessage().'<br/>';
  }
}
