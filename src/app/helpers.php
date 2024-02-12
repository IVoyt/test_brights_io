<?php

if (!function_exists('env')) {
    function env(string $key, $defaultValue = null)
    {
        $envValue = getenv($key);

        switch (strtolower($envValue)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (substr($envValue, 0, 1) == '"' && substr($envValue, -1) == '"') {
            return substr($envValue, 1, -1);
        }

        return $envValue ?? $defaultValue;
    }
}

if (!function_exists('base_path')) {
    function base_path(?string $extra = null): string
    {
        $path = dirname(__DIR__);
        if ($extra) {
            $path .= "/{$extra}";
        }

        return $path;
    }
}

if (!function_exists('view_path')) {
    function view_path(?string $extra = null): string
    {
        $path = base_path('app/views');
        if ($extra) {
            $path .= "/{$extra}";
        }

        return $path;
    }
}

if (!function_exists('escape_string')) {
    function escape_string(string $string): string
    {
        return preg_replace(['/(\/)/', '/(\\\)/'], ['\/', '\\\\'], $string);
    }
}
