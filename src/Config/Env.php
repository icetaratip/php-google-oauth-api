<?php

declare(strict_types=1);

namespace App\Config;

class Env
{
    private static array $data = [];

    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
                    $value = $matches[1];
                }

                self::$data[$key] = $value;
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }

    public static function get(string $key, $default = null)
    {
        if (isset(self::$data[$key])) {
            $val = self::$data[$key];
            if ($val === 'true') return true;
            if ($val === 'false') return false;
            return $val;
        }

        $val = getenv($key);
        if ($val !== false) {
            if ($val === 'true') return true;
            if ($val === 'false') return false;
            return $val;
        }

        return $default;
    }
}
