<?php

namespace GSMeira\LaravelMultialerts;

use Illuminate\Support\Facades\Session;

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
     * All alerts are stored here.
     *
     * @var array
     */
    private $alerts;

    /**
     * The session key used by the alerts.
     *
     * @var string
     */
    private $sessionKey;

    /**
     * Multialerts class constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'default')
    {
        $this->type = $type;
        $this->level = '';
        $this->fields = [];
        $this->sessionKey = config('alerts.session_key', 'multialerts');
        $this->alerts = $this->getAlerts();
    }

    /**
     * Returns the existing alerts.
     *
     * @return array
     */
    private function getAlerts()
    {
        if (Session::has($this->sessionKey))
        {
            return Session::get($this->sessionKey);
        }

        return [];
    }

    /**
     * Makes the alerts visible only in the current request.
     *
     * @return void
     */
    public function toRequest()
    {
        if (Session::has($this->sessionKey))
        {
            view()->share('requestAlerts', $this->alerts);

            Session::flash($this->sessionKey, []);
        }
    }

    /**
     * Adds the message and the fields defined in the session.
     *
     * @return void
     */
    protected function persist()
    {
        // Build the message.
        $this->alerts[$this->type][$this->level][] = $this->fields;

        // Serializes the array and remove duplicate messages.
        $unique = array_unique(array_map('serialize', $this->alerts[$this->type][$this->level]));

        // Intersects, returning the unique alerts.
        $this->alerts[$this->type][$this->level] = array_intersect_key($this->alerts[$this->type][$this->level], $unique);

        // Refresh the session with the new unique alerts.
        Session::flash($this->sessionKey, $this->alerts);
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
     * Persist in the session the alerts after all fields have been defined.
     *
     * @return void
     */
    public function put()
    {
        $this->persist($this->type);
    }

    /**
     * Returns all alerts.
     *
     * @return mixed
     */
    public function all()
    {
        if (isset($this->alerts[$this->type]))
        {
            return $this->alerts[$this->type];
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
     * @return int
     */
    public function sizeof($level = 'error')
    {
        if (isset($this->alerts[$this->type][$level]))
        {
            return sizeof($this->alerts[$this->type][$level]);
        }
        else
        {
            return 0;
        }
    }
}