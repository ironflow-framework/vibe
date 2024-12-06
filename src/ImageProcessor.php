<?php

namespace Vibe;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessor
{
   protected $manager;

   public function __construct()
   {
      $this->manager = new ImageManager(new Driver());
   }

   public function resize(string $filePath, int $width, int $height): string
   {
      // read image from file system
      $image = $this->manager->read($filePath);

      // resize image proportionally to 300px width
      $image->scale(width: $width, height: $height);

      // insert watermark
      $image->place('images/watermark.png');
      $resizedPath = $this->getResizedPath($filePath, $width, $height);
      // save modified image in new format 
      $image->toPng()->save($resizedPath);

      return $resizedPath;
   }

   private function getResizedPath(string $filePath, int $width, int $height): string
   {
      $info = pathinfo($filePath);
      return $info['dirname'] . '/' . $info['filename'] . "_{$width}x{$height}." . $info['extension'];
   }
}
