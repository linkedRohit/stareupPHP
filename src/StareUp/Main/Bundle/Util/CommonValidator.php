<?php

namespace StareUp\Main\Bundle\Util;

use JMS\DiExtraBundle\Annotation as DI;

class CommonValidator {

    public function validate($input, $rule) {
        if($rule['file'] == 'image') {
            return $this->validateImage($input, $rule);
        }
    }

    private function validateImage($input, $rule) {

      if (!isset($input[0]))
      {
          $errors['status'] = false;
          $errors['error'] = 'Uploaded file is not an image.';
      }
      else
      {
          $imgSize = $input[0]->getSize();
          $imgMimeType = $input[0]->getMimeType();
          if(($imgSize > $rule['size']) || ($imgSize == 0)) {
              $errors['status'] = false;
              $errors['error'] = 'File size too large. File must be less than 3 megabytes.';
          }

          if(!in_array($imgMimeType, $rule['type']) && (!empty($imgMimeType))) {
              $errors['status'] = false;
              $errors['error'] = 'Invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
          }
      }
      $errors['status'] = true;
      return $errors;
    }
}
