<?php

namespace app\base;

final class View
{
    public function __construct(
        private readonly string $layout,
        private readonly string $viewName,
        private array           $data = []
    ) {}

    public function render(): string
    {
        extract($this->data);

        ob_start();
        include view_path("{$this->viewName}.php");
        $view = ob_get_clean();

        include view_path("layout/{$this->layout}.php");
        return ob_get_clean();
    }
}
