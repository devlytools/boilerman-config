<?php
namespace Devly\BoilermanConfig;

class Config
{
    /**
     * @var array
     */
    private static $container = [];

    /**
     * @var mixed
     */
    private static $applied = false;

    /**
     * Assign a value to the specified offset.
     *
     * @param $offset
     * @param $value
     */
    public static function define($offset, $value)
    {
        $offset                     = strtoupper($offset);
        static::$container[$offset] = $value;
    }

    /**
     * Assign a value to the specified offset.
     *
     * @param $offset
     * @param $value
     */
    protected static function defined($offset)
    {
        if (defined($offset)) {
            $message = "Aborted trying to redefine constant '$offset'. `define('$offset', ...)` has already been occurred elsewhere.";
            throw new \RuntimeException($message);
        }

        return false;
    }

    /**
     * @param string $offset
     */
    public static function retrive($offset)
    {
        $offset = strtoupper($offset);

        return isset(static::$container[$offset]) ? static::$container[$offset] : null;
    }

    public static function apply()
    {
        if (true === static::$applied) {
            $message = 'Boilerman configurations can be applied only once.';
            throw new \RuntimeException($message);
        }

        foreach (static::$container as $key => $value) {
            try {
                self::defined($key);
            } catch (\RuntimeException$e) {
                if (constant($key) !== $value) {
                    throw $e;
                }
            }
        }

        foreach (static::$container as $key => $value) {
            defined($key) || define($key, $value);
        }

        static::$applied = true;
    }

}
