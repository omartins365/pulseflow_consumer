<?php

namespace GenioForge\Consumer\Data\Traits;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

trait Logger
{
    public \Monolog\Logger $log;
    public function log(Level $level = Level::Debug): \Monolog\Logger
    {
        if (!isset($this->log)) {
            // create a log channel
            $label = str(static::class)->afterLast('\\');
            $this->log = new \Monolog\Logger($label . "_log");
            $formatter = new LineFormatter(
                null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
                null, // Datetime format
                true, // allowInlineLineBreaks option, default false
                true  // discard empty Square brackets in the end, default false
            );

            // Debug level handler
            $debugHandler =new StreamHandler(storage_path('logs' . '/consumer/' . $label . '.log'), $level,true,0774);
            $debugHandler->setFormatter($formatter);

            $this->log->pushHandler($debugHandler);

            $this->log->alert('----------------------------------------------');
        }
// add records to the log
//        $this->log->warning('Foo');
        return $this->log;
    }
}
