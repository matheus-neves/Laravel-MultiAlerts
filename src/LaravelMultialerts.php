<?php

/*
 * This file is part of Laravel-MultiAlerts.
 *
 * (c) Gustavo Meireles <gustavo@gsmeira.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GSMeira\LaravelMultiAlerts;

use Exception;

/**
 * This is the Laravel-MultiAlerts main class.
 *
 * @author Gustavo Meireles <gustavo@gsmeira.com>
 * @package GSMeira\LaravelMultiAlerts
 */
class LaravelMultiAlerts
{
    /**
     * Type of the alert.
     *
     * @var string
     */
    protected $type = 'default';

    /**
     * Alert level.
     *
     * @var string
     */
    protected $level;

    /**
     * Alert fields.
     *
     * @var array
     */
    protected $fields;

    /**
     * All session alerts are stored here.
     *
     * @var array
     */
    protected $sessionAlerts;

    /**
     * All view alerts are stored here.
     *
     * @var array
     */
    protected $viewAlerts;

    /**
     * The session key used by the alerts.
     *
     * @var string
     */
    protected $sessionKey;

    /**
     * The view key used by the alerts.
     *
     * @var string
     */
    protected $viewKey;

    /**
     * Available levels.
     *
     * @var array
     */
    protected $levels;

    /**
     * Chain size.
     *
     * @var int
     */
    protected $chainSize;

    /**
     * Laravel-MultiAlerts class constructor.
     *
     * @param $sessionKey
     * @param $viewKey
     * @param array $levels
     * @return self
     */
    public function __construct($sessionKey, $viewKey, array $levels)
    {
        $this->level = '';
        $this->fields = [];
        $this->chainSize = 0;
        $this->sessionKey = $sessionKey;
        $this->viewKey = $viewKey;
        $this->levels = $levels;

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
     * Initializes the Laravel-MultiAlerts component.
     *
     * @param $type
     * @return $this
     */
    public function initialize($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Adds in the session the message and the custom fields.
     *
     * @param bool $sessionStore
     * @return void
     */
    public function put($sessionStore = true)
    {
        if ($sessionStore) {
            session()->flash($this->sessionKey, $this->persist($this->sessionAlerts));
        } else {
            view()->share($this->viewKey, $this->persist($this->viewAlerts));
        }
    }

    /**
     * Returns all alerts.
     *
     * @param bool $sessionStore
     * @return array
     */
    public function all($sessionStore = true)
    {
        if ($sessionStore) {
            $alerts = $this->sessionAlerts;
        } else {
            $alerts = $this->viewAlerts;
        }

        if (! isset($alerts[$this->type])) {
            return [];
        }

        return $alerts[$this->type];
    }

    /**
     * Persist a new alert.
     *
     * @param $alerts
     * @return array
     */
    protected function persist($alerts)
    {
        // Build the alert.
        $alerts[$this->type][$this->level][] = $this->fields;

        // Serializes the array and remove duplicate alerts.
        $uniqueAlerts = array_unique(array_map('serialize', $alerts[$this->type][$this->level]));

        // Intersects, returning only the unique alerts.
        $alerts[$this->type][$this->level] = array_intersect_key($alerts[$this->type][$this->level], $uniqueAlerts);

        return $alerts;
    }
}
