<?php

namespace Kiwilan\Sentinel\Tests\Media;

use Throwable;

class PrintLog
{
    public static function make()
    {
        $e = new \Exception('This is a test exception', 500);

        $content = self::printLogError($e);

        $path = __DIR__.'/LogExample.php';

        file_put_contents($path, $content);

        return $path;
    }

    private function printLogError(Throwable $e): string
    {
        $content[] = '<?php';
        $content[] = '';
        $content[] = 'namespace Kiwilan\Sentinel\Tests\Media;';
        $content[] = '';
        $content[] = 'class LogExample';
        $content[] = '{';
        $content[] = '    public static function make(): array';
        $content[] = '    {';
        $content[] = '        return [';
        $content[] = "            'code' => {$e->getCode()},";
        $content[] = "            'file' => '{$e->getFile()}',";
        $content[] = "            'line' => {$e->getLine()},";
        $content[] = '            "message" => "'.$e->getMessage().'","';

        $traceAsString = $e->getTraceAsString();
        $traceAsString = str_replace("\n", ' ', $traceAsString);
        $content[] = '            "traceAsString" => "'.$traceAsString.'",';

        $traces = [];

        foreach ($e->getTrace() as $key => $trace) {
            $file = $trace['file'] ?? '';
            $line = $trace['line'] ?? '';
            $function = (string) $trace['function'];
            $class = $trace['class'] ?? '';
            $type = $trace['type'] ?? '';
            $args = $trace['args'] ?? [];

            $t = [];
            $t[] = '                [,';
            $t[] = "                    'file' => '{$file}',";
            $t[] = "                    'line' => '{$line}',";
            $t[] = "                    'function' => '{$function}',";
            $t[] = "                    'class' => '{$class}',";
            $t[] = "                    'type' => '{$type}',";
            // $t[] = "                    'args' => ".$this->parseArgs($args).',';

            $t[] = '                ],';

            $traces[] = implode(PHP_EOL, $t);
        }

        $content[] = "            'trace' => [\n".implode(PHP_EOL, $traces);

        $content[] = '            ],';
        $content[] = '        ];';
        $content[] = '    }';
        $content[] = '}';
        $content[] = '';

        return implode(PHP_EOL, $content);
    }

    private function parseArgs(array $args): string
    {
        $content = [];

        foreach ($args as $key => $arg) {
            $t = [];

            $file = $arg['file'] ?? '';
            $line = $arg['line'] ?? '';
            $function = $arg['function'] ?? '';
            $class = $arg['class'] ?? '';
            $type = $arg['type'] ?? '';
            $args = $arg['args'] ?? [];

            $t[] = '                [';
            $t[] = "                    'file' => '{$file}',";
            $t[] = "                    'line' => '{$line}',";
            $t[] = "                    'function' => '{$function}',";
            $t[] = "                    'class' => '{$class}',";
            $t[] = "                    'type' => '{$type}',";
            $t[] = "                    'args' => ".$this->parseArgs($args).',';
            $t[] = '                ],';

            $content[] = implode(PHP_EOL, $t);
        }

        return "[\n".implode(PHP_EOL, $content)."\n            ]";
    }
}
