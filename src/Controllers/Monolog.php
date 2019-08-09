<?php
/**
 * Class Monolog
 *
 * @author Terry Lin
 * @link https://terryl.in/
 *
 * @package Githuber
 * @since 1.6.0
 * @version 1.6.0
 */

namespace Githuber\Controller;

class Monolog
{

    public static $instance;

    public static function get_instance()
    {

        if (!isset(self::$instance)) {

            $settings = array(
                'name' => 'githuber-md',
                'path' => GITHUBER_PLUGIN_DIR . 'logs/markdown.log',
                'level' => \Monolog\Logger::DEBUG,
            );

            self::$instance = new \Monolog\Logger($settings['name']);
            self::$instance->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

        }
        return self::$instance;
    }

    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function logger($message, $data = array())
    {
        self::info($message, $data);
    }

    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param int $SEVERITY
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function log($SEVERITY, $message, $data = array())
    {

        if (GITHUBER_DEBUG_MODE) {
            $trace = debug_backtrace();

            $caller_class = $trace[1]['class'];
            $caller_method = $trace[1]['function'];

            $caller_class = str_replace('Githuber\\', '', $caller_class);
            $caller_class = str_replace('\\', '/', $caller_class);

            $caller_info = array(
                'class' => $caller_class,
                'method' => $caller_method,
                //'track'  => end( $track_file ) . '(' . __LINE__ . ')',
            );

            $info_data['caller'] = $caller_info;

            if (!empty($data)) {
                $info_data['info'] = $data;
            }

            self::get_instance()->addRecord($SEVERITY, $message . "\n", $info_data);
        }
    }

    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function info($message, $data = array())
    {
        if (GITHUBER_DEBUG_MODE)
            self::log(Logger::INFO, $message, $data);
    }

    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function debug($message, $data = array())
    {
        if (GITHUBER_DEBUG_MODE)
            self::log(Logger::DEBUG, $message, $data);
    }

    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function warn($message, $data = array())
    {
        if (GITHUBER_DEBUG_MODE)
            self::log(Logger::WARNING, $message, $data);
    }


    /**
     * Record Markdown processing logs for debug propose.
     *
     * @param string $message
     * @param array $data
     * @return void
     */
    public static function error($message, $data = array())
    {
        if (GITHUBER_DEBUG_MODE)
            self::log(Logger::ERROR, $message, $data);
    }
}
