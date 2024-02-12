<?php

namespace app\controllers;

use app\base\Application;
use app\base\Collection;
use app\base\Model;
use app\base\Request;
use app\base\Response;
use app\base\View;
use Exception;

abstract class Controller
{
    protected Response $response;
    protected string   $layout = 'main';

    public function __construct()
    {
        $this->response = Application::getInstance()->getResponse();
    }

    /**
     * @throws Exception
     */
    protected function render(array $data = []): Response
    {
        $application = Application::getInstance();

        $contentType = $application->getRequest()->getContentType();

        switch ($contentType) {
            case Request::CONTENT_TYPE_HTML:
                $viewDir  = str_replace([__NAMESPACE__, 'controller', '\\'], '', strtolower(static::class));
                $viewName = Application::getInstance()->getRouter()->getActionName();
                $this->response
                    ->setContent((new View($this->layout, "{$viewDir}/{$viewName}", $data))->render());
                break;

            case Request::CONTENT_TYPE_JSON:
                $this->response->setHeaders(['Content-Type' => $contentType]);
                foreach ($data as &$item) {
                    if ($item instanceof Collection || $item instanceof Model) {
                        $item = $item->toArray();
                    }
                }
                $this->response->setContent(json_encode($data));
                break;

            default:
                throw new Exception("The server currently does not support {$contentType}!");
        }

        return $this->response;
    }
}
