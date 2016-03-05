<?php

/*
 * This file is part of Laravel Multialerts.
 *
 * (c) Gustavo Meireles <gustavo@gsmeira.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GSMeira\LaravelMultialerts;

use Illuminate\Support\Facades\Session;

/**
 * This is the Laravel Multialerts main class.
 *
 * @author Gustavo Meireles <gustavo@gsmeira.com>
 * @package GSMeira\LaravelMultialerts
 */
class LaravelMultialerts
{
    /**
     * Type of the alert.
     *
     * @var string
     */
    private $type;

    /**
     * Alert level.
     *
     * @var string
     */
    private $level;

    /**
     * Alert fields.
     *
     * @var array
     */
    private $fields;

    /**
     * All session alerts are stored here.
     *
     * @var array
     */
    private $sessionAlerts;

    /**
     * All view alerts are stored here.
     *
     * @var array
     */
    private $viewAlerts;

    /**
     * The session key used by the alerts.
     *
     * @var string
     */
    private $sessionKey;

    /**
     * The view key used by the alerts.
     *
     * @var string
     */
    private $viewKey;

    /**
     * Laravel Multialerts class constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'default')
    {
        $this->type = $type;
        $this->level = '';
        $this->fields = [];
        $this->sessionKey = config('alerts.session_key', 'multialerts');
        $this->viewKey = config('alerts.view_key', 'multialerts');
        $this->sessionAlerts = Session::get($this->sessionKey) ? Session::get($this->sessionKey) : [];
        $this->viewAlerts = view()->shared($this->viewKey) ? view()->shared($this->viewKey) : [];
    }

    /**
     * Magic method that creates the fields to be used in the alert.
     *
     * @param $key
     * @param $args
     * @return $this
     */
    public function __call($key, $args)
    {
        $placeholders = [];

        if (sizeof($args) > 1)
        {
            list($message, $placeholders) = $args;
        }
        else
        {
            list($message) = $args;
        }

        $this->fields += [ $key => trans($message, $placeholders) ];

        return $this;
    }

    /**
     * Build an information alert.
     *
     * @param $message
     * @param array $placeholders
     * @return $this
     */
    public function info($message, $placeholders = [])
    {
        $this->level = 'info';

        $this->fields += [ 'message' => trans($message, $placeholders) ];

        return $this;
    }

    /**
     * Build an success alert.
     *
     * @param $message
     * @param array $placeholders
     * @return $this
     */
    public function success($message, $placeholders = [])
    {
        $this->level = 'success';

        $this->fields += [ 'message' => trans($message, $placeholders) ];

        return $this;
    }

    /**
     * Build an error alert.
     *
     * @param $message
     * @param array $placeholders
     * @return $this
     */
    public function error($message, $placeholders = [])
    {
        $this->level = 'error';

        $this->fields += [ 'message' => trans($message, $placeholders) ];

        return $this;
    }

    /**
     * Build an warning alert.
     *
     * @param $message
     * @param array $placeholders
     * @return $this
     */
    public function warning($message, $placeholders = [])
    {
        $this->level = 'warning';

        $this->fields += [ 'message' => trans($message, $placeholders) ];

        return $this;
    }

    /**
     * Adds in the session the message and the custom fields.
     *
     * @param bool $sessionStore
     */
    public function put($sessionStore = true)
    {
        if ($sessionStore)
        {
            Session::flash($this->sessionKey, $this->addAlert($this->sessionAlerts));
        }
        else
        {
            view()->share($this->viewKey, $this->addAlert($this->viewAlerts));
        }
    }

    /**
     * Adds a new alert.
     *
     * @param $alerts
     * @return mixed
     */
    private function addAlert($alerts)
    {
        // Build the alert.
        $alerts[$this->type][$this->level][] = $this->fields;

        // Serializes the array and remove duplicate alerts.
        $uniqueAlerts = array_unique(array_map('serialize', $alerts[$this->type][$this->level]));

        // Intersects, returning the unique alerts.
        $alerts[$this->type][$this->level] = array_intersect_key($alerts[$this->type][$this->level], $uniqueAlerts);

        return $alerts;
    }

    /**
     * Returns all alerts.
     *
     * @param bool $sessionStore
     * @return mixed
     */
    public function all($sessionStore = true)
    {
        if ($sessionStore)
        {
            $alerts = $this->sessionAlerts;
        }
        else
        {
            $alerts = $this->viewAlerts;
        }

        if (isset($alerts[$this->type]))
        {
            return $alerts[$this->type];
        }
        else
        {
            return [];
        }
    }

    /**
     * Returns the number of messages.
     *
     * @param string $level
     * @param bool $sessionStore
     * @return int
     */
    public function sizeof($level = 'error', $sessionStore = true)
    {
        if ($sessionStore)
        {
            $alerts = $this->sessionAlerts;
        }
        else
        {
            $alerts = $this->viewAlerts;
        }

        if (isset($alerts[$this->type][$level]))
        {
            return sizeof($alerts[$this->type][$level]);
        }
        else
        {
            return 0;
        }
    }
}