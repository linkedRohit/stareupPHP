<?php

namespace StareUp\Main\Bundle\Util;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("common.validator", parent="base.validator")
 */
class CommonValidator {

    public function validate($input, $rule) {
        if($rule['file'] == 'image') {
            return $this->validateImage($input, $rule);
        }
    }

    public function validateImage($input, $rule) {

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
    
    public function validateItem($itemArray) {
	return true;
        $success = true;
        $error = $this->validateTitle($itemArray['title']);
        if($error !== true) {
            return $error;
        }
        
        $error = $this->validateDescription($itemArray['description']);
        if($error !== true) {
            return $error;
        }
        
        return $success;
    }
    
    public function validateTitle ($title) {
        if(empty($title) || strlen($title) < 30) {
            return 'Give a title upto 30 characters.';
        }
        return true;
    }
    
    public function validateDescription($desc) {
        if(empty($desc)) {
            return 'Description required';
        }
        if(strlen($desc) < 50) {
            return 'Please describe well with atleast 50 characters.';
        }
        return true;
    }
}
