<?php

namespace app\base;

final class Application
{
    private Router       $router;
    private Request      $request;
    private Response     $response;
    private static ?self $application = null;
    private array        $dbConfig;

    private function __construct()
    {
        $this->loadEnv();
        $this->request  = new Request();
        $this->response = new Response();
        $this->router   = new Router();

        $this->dbConfig = require base_path('config/db.php');
    }

    public static function getInstance(): self
    {
        if (!self::$application) {
            self::$application = new self();
        }

        return self::$application;
    }

    public function getDbConfig(): array
    {
        $driver = $this->dbConfig['default'];
        return $this->dbConfig['drivers'][$driver] + ['connection' => $driver];
    }

    public function run(): void
    {
        $this->router->run($this->request);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    private function loadEnv(): void
    {
        $envContents = file_get_contents(dirname(__DIR__, 2).'/.env');
        $envList     = explode("\n", $envContents);
        foreach ($envList as $envItem) {
            if (!$envItem) {
                continue;
            }

            putenv($envItem);
        }
    }
}
