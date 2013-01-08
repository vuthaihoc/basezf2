<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Console
 */

namespace Zend\Console;

/**
 * An static, utility class for interacting with Console enviroment.
 * Declared abstract to prevent from instantiating.
 *
 * @category   Zend
 * @package    Zend_Console
 */
abstract class Console
{
    /**
     * @var Adapter\AdapterInterface
     */
    protected static $instance;

    /**
     * Create and return Adapter\AdapterInterface instance.
     *
     * @param  null|string  $forceAdapter Optional adapter class name. Ccan be absolute namespace or class name
     *                                    relative to Zend\Console\Adapter\. If not provided, a best matching
     *                                    adapter will be automatically selected.
     * @param  null|string  $forceCharset optional charset name can be absolute namespace or class name relative to
     *                                    Zend\Console\Charset\. If not provided, charset will be detected
     *                                    automatically.
     * @throws Exception\InvalidArgumentException
     * @return Adapter\AdapterInterface
     */
    public static function getInstance($forceAdapter = null, $forceCharset = null)
    {
        if (static::$instance instanceof Adapter\AdapterInterface) {
            return static::$instance;
        }

        // Create instance

        if ($forceAdapter !== null) {
            // Use the supplied adapter class
            if (substr($forceAdapter, 0, 1) == '\\') {
                $className = $forceAdapter;
            } elseif (stristr($forceAdapter, '\\')) {
                $className = __NAMESPACE__ . '\\' . ltrim($forceAdapter, '\\');
            } else {
                $className = __NAMESPACE__ . '\\Adapter\\' . $forceAdapter;
            }

            if (!class_exists($className)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Cannot find Console adapter class "%s"',
                    $className
                ));
            }
        } else {
            // Try to detect best instance for console
            $className = static::detectBestAdapter();
        }

        // Create adapter instance
        static::$instance = new $className();

        // Try to use the supplied charset class
        if ($forceCharset !== null) {
            if (substr($forceCharset, 0, 1) == '\\') {
                $className = $forceCharset;
            } elseif (stristr($forceAdapter, '\\')) {
                $className = __NAMESPACE__ . '\\' . ltrim($forceCharset, '\\');
            } else {
                $className = __NAMESPACE__ . '\\Charset\\' . $forceCharset;
            }

            if (!class_exists($className)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Cannot find Charset class "%s"',
                    $className
                ));
            }

            // Set adapter charset
            static::$instance->setCharset(new $className());
        }

        return static::$instance;
    }

    /**
     * Check if currently running under MS Windows
     *
     * @return bool
     */
    public static function isWindows()
    {
        return class_exists('COM', false);
    }

    /**
     * Check if running under MS Windows Ansicon
     *
     * @return bool
     */
    public static function isAnsicon()
    {
        return getenv('ANSICON') !== false;
    }

    /**
     * Check if running in a console environment (CLI)
     *
     * @return bool
     */
    public static function isConsole()
    {
        return PHP_SAPI == 'cli';
    }

    /**
     * Try to detect best matching adapter
     * @return string|null
     */
    public static function detectBestAdapter()
    {
        // Check if we are in a console environment
        if (!static::isConsole()) {
            return null;
        }

        // Check if we're on windows
        if (static::isWindows()) {
            if (static::isAnsicon()) {
                $className = __NAMESPACE__ . '\Adapter\WindowsAnsicon';
            } else {
                $className = __NAMESPACE__ . '\Adapter\Windows';
            }

            return $className;
        }

        // Default is a Posix console
        $className = __NAMESPACE__ . '\Adapter\Posix';
        return $className;
    }

    /**
     * Pass-thru static call to current AdapterInterface instance.
     *
     * @param $funcName
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($funcName, $arguments)
    {
        $instance = static::getInstance();
        return call_user_func_array(array($instance, $funcName), $arguments);
    }
}
