<?php

namespace Vibe;

use Vibe\Exceptions\UploadException;
use Vibe\Storage\LocalStorage;
use Vibe\ImageProcessor;

class Vibe
{
    protected $storage;
    protected $processor;

    public function __construct(string $storageType = 'local', array $config = [])
    {
        $this->storage = $this->initializeStorage($storageType, $config);
        $this->processor = new ImageProcessor();
    }

    private function initializeStorage(string $storageType, array $config)
    {
        return match ($storageType) {
            'local' => new LocalStorage($config),
            // 'cloud' => new CloudStorage($config),
            default => throw new \InvalidArgumentException("Type de stockage non supporté."),
        };
    }

    public function upload(array $file, string $directory): string
    {
        // Valider et traiter le fichier
        $this->validateFile($file);
        return $this->storage->store($file, $directory);
    }

   public function validateFile(array $file): void
   {
      if ($file['error'] !== UPLOAD_ERR_OK) {
         throw new UploadException("Erreur lors de l'upload : " . $this->getUploadErrorMessage($file['error']));
      }

      $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
      if (!in_array($file['type'], $allowedTypes)) {
         throw new UploadException("Type de fichier non autorisé.");
      }
   }

   private function getUploadErrorMessage(int $errorCode): string
   {
      return match ($errorCode) {
         UPLOAD_ERR_INI_SIZE => "Le fichier dépasse la taille maximale autorisée par PHP.",
         UPLOAD_ERR_FORM_SIZE => "Le fichier dépasse la taille maximale autorisée par le formulaire.",
         UPLOAD_ERR_PARTIAL => "Le fichier n'a été que partiellement téléchargé.",
         UPLOAD_ERR_NO_FILE => "Aucun fichier n'a été téléchargé.",
         UPLOAD_ERR_NO_TMP_DIR => "Le dossier temporaire est manquant.",
         UPLOAD_ERR_CANT_WRITE => "Impossible d'écrire le fichier sur le disque.",
         UPLOAD_ERR_EXTENSION => "Une extension PHP a stoppé le téléchargement.",
         default => "Erreur inconnue.",
      };
   }

    public function resizeImage(string $filePath, int $width, int $height): string
    {
        return $this->processor->resize($filePath, $width, $height);
    }
}
