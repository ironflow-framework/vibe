<?php

namespace Vibe\Exceptions;

class UploadException extends \Exception
{
   public function __construct(string $message = "Erreur lors de l'upload.", int $code = 0, \Throwable $previous = null)
   {
      parent::__construct($message, $code, $previous);
   }
}
