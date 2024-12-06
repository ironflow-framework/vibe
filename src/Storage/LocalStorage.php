<?php

namespace Vibe\Storage;

class LocalStorage
{
   protected $basePath;

   public function __construct(array $config = [])
   {
      $this->basePath = $config['base_path'] ?? __DIR__ . '/../../uploads';
   }

   public function store(array $file, string $directory): string
   {
      $targetDir = rtrim($this->basePath, '/') . '/' . trim($directory, '/');
      if (!is_dir($targetDir)) {
         mkdir($targetDir, 0777, true);
      }

      $fileName = uniqid() . '_' . basename($file['name']);
      $targetFile = $targetDir . '/' . $fileName;

      if (move_uploaded_file($file['tmp_name'], $targetFile)) {
         return $targetFile;
      }

      throw new \RuntimeException("Impossible de déplacer le fichier.");
   }
}
