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

use Exception;
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
     * Available levels.
     *
     * @var string
     */
    private $levels;

    /**
     * Chain size.
     *
     * @var int
     */
    private $chainSize;

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
        $this->chainSize = 0;

        $this->sessionKey = config('gsmeira.multialerts.session_key', 'multialerts');
        $this->viewKey = config('gsmeira.multialerts.view_key', 'multialerts');
        $this->levels = config('gsmeira.multialerts.levels', [ 'success', 'warning', 'error', 'info' ]);

        $this->sessionAlerts = session()->get($this->sessionKey) ? session()->get($this->sessionKey) : [];
        $this->viewAlerts = view()->shared($this->viewKey) ? view()->shared($this->viewKey) : [];
    }

    /**
     * Magic method that creates the fields to be used in the alert.
     *
     * @param $key
     * @param $args
     * @return $this
     * @throws Exception
     */
    public function __call($key, $args)
    {
        $placeholders = [];

        if (sizeof($args) > 1) {
            list($message, $placeholders) = $args;
        } else {
            list($message) = $args;
        }

        if (in_array($key, $this->levels) && $this->chainSize === 0) {
            $this->level = $key;

            $this->fields += [ 'message' => trans($message, $placeholders) ];
        } else {
            if ($this->chainSize > 0) {
                $this->fields += [ $key => trans($message, $placeholders) ];
            } else {
                throw new Exception('Invalid level exception.');
            }
        }

        $this->chainSize++;

        return $this;
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

        // Intersects, returning only the unique alerts.
        $alerts[$this->type][$this->level] = array_intersect_key($alerts[$this->type][$this->level], $uniqueAlerts);

        return $alerts;
    }

    /**
     * Adds in the session the message and the custom fields.
     *
     * @param bool $sessionStore
     */
    public function put($sessionStore = true)
    {
        if ($sessionStore) {
            Session::flash($this->sessionKey, $this->addAlert($this->sessionAlerts));
        } else {
            view()->share($this->viewKey, $this->addAlert($this->viewAlerts));
        }
    }

    /**
     * Returns all alerts.
     *
     * @param bool $sessionStore
     * @return mixed
     */
    public function all($sessionStore = true)
    {
        if ($sessionStore) {
            $alerts = $this->sessionAlerts;
        } else {
            $alerts = $this->viewAlerts;
        }

        if (isset($alerts[$this->type])) {
            return $alerts[$this->type];
        } else {
            return [];
        }
    }
}
