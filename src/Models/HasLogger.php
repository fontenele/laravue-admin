<?php

namespace App;

use Spatie\Activitylog\Traits\LogsActivity;
use \ReflectionClass;
use \ReflectionException;

trait HasLogger
{
    use LogsActivity;
    protected static $logOnlyDirty = true;
    protected static $logName = '';

    /**
     * @param $eventName
     * @return string
     * @throws ReflectionException
     */
    public function getDescriptionForEvent($eventName): string
    {
        $className = (new ReflectionClass($this))->getShortName();
        self::$logName = strtolower($className);
        switch ($eventName) {
            case 'updated':
                return "{$className} updated";
            case 'created':
                return "{$className} created";
            case 'deleted':
                return "{$className} deleted";
            default:
                return '';
        }
    }
}
