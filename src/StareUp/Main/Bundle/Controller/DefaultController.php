<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as config;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use StareUp\Main\Bundle\Util\CommonValidator;

/**
 * @config\Route("/api/v1")
 */
class DefaultController extends BaseController
{
    public function indexAction()
    {
	      $path = realpath($this->get('kernel')->getRootDir() . '/../web/css/main.css');
        return $this->render('StareUpMainBundle:Default:index.html.twig', array('path'=>$path));
    }

    /**
     * @config\Route("/upload", name="image_upload")
     * detail - This will upload the image to temporary folder
     * author - Rohit Sharma
     * date - 4 June 2016
     */
    public function uploadFile(Request $request)
    {
          $token = $this->getPostRequestParam('_token');
          $fileData = $this->getFiles('file');
          $fileName = $fileData[0]->getClientOriginalName();
          $rules = array(
              'file' => 'image',
              'size' => '20000',
              'type' => ['image/jpeg', 'image/jpg', 'image/png']
          );

          $commonValidator = new CommonValidator();
          $validation = $commonValidator->validate($fileData, $rules);
          if ($validation['status'] != true)
          {
              return new Response($validation['error'], 415);
          }

          $directory = $_SERVER['DOCUMENT_ROOT'].'/temp';//.date("dmY");
          $fileId = md5($token.time());
          $destination = $directory . '/' . $fileId;
          try {
          $upload_success = move_uploaded_file( $fileName, $destination);
          }catch(Exception $e) {
            return new Response('Could not upload ! Please try again.', 417);
          }
          if( $upload_success ) {
              $res['status'] = '1';
              $res['fileName'] = $fileName;
              $res['fileId'] = $fileName;
              return new Response(json_encode($res), 200);
          } else {
              return new Response('Could not upload ! Please try again.', 422);
          }
     }
}
