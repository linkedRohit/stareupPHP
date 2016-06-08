<?php

namespace StareUp\Main\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class BaseController extends Controller
{

    public function getRequestParam($key, $defaultValue = null) {
        return $this->getRequest()->query->get($key, $defaultValue);
    }

    public function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    public function getPostRequestParam($key, $defaultValue = null) {
        return $this->getRequest()->request->get($key, $defaultValue);
    }

    public function getFiles($key, $defaultValue = null) {
        return $this->getRequest()->files->get($key, $defaultValue);
    }

    public function getAllPostParams() {
        return $this->getRequest()->request->all();
    }

    public function getAllParams() {
        $params_arr = array();
        $ROUTE_PARAMS_ARR = $this->getRequest()->attributes->get('_route_params');

        if ($this->getRequest()->getMethod() == "GET") {
            $params_arr = $this->getRequest()->query->all();
            //print_r($ROUTE_PARAMS_ARR);die;
        } else {
            $params_arr = $this->getRequest()->request->all();
        }
        return $ROUTE_PARAMS_ARR;
    }

    public function getCurrentUrl() {
        $route = $this->getRequest()->attributes->get('_route');
        return $route;
    }

    /*public function render($view, array $parameters = array(), Response $response = null) {
        $parameters = $this->preRenderElements($parameters);

        return parent::render($view, $parameters, $response);
    }

    public function renderView($view, array $parameters = array()) {

        $parameters = $this->preRenderElements($parameters);
        return parent::renderView($view, $parameters);
    }

    private function preRenderElements( $parameters ){

        $route = $this->getRequest()->get('_route');

    }
*/
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        // ...

        // the controller can be changed to any PHP callable
        $event->setController($controller);
    }

    protected function checkCsrf($name, $query = '_token')
    {
        $request = $this->getRequest();
        $csrfProvider = $this->get('form.csrf_provider');

        if (!$csrfProvider->isCsrfTokenValid($name, $request->query->get($query))) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('CSRF token is invalid.');
        }

        return true;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $response = new Response();
        $response = new Response(
            'Error',
            404, // this status code will be ignored
            array(
                'X-Status-Code' => 200 // this status code will actually be sent to the client
            )
        );
        // setup the Response object based on the caught exception
        $event->setResponse($response);

        // you can alternatively set a new Exception
        // $exception = new \Exception('Some special exception');
        // $event->setException($exception);
    }
}
