<?php


namespace Core\Entities;


use Core\Interfaces\Jsonable;

class Entity implements Jsonable
{

    protected $datamap = [];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [];

    protected $attributes = [];

    protected $original = [];

    private $_cast = true;

    public function __construct(array $data = null)
    {
        $this->syncOriginal();

        $this->fill($data);
    }

    public function syncOriginal()
    {
        $this->original = $this->attributes;

        return $this;
    }

    public function fill(array $data)
    {
        if (!is_array($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    public function toArray(bool $onlyChanged = false, bool $cast = true): array
    {
        $this->_cast = $cast;
        $return      = [];

        // we need to loop over our properties so that we
        // allow our magic methods a chance to do their thing.
        foreach ($this->attributes as $key => $value)
        {
            if (strpos($key, '_') === 0)
            {
                continue;
            }

            if ($onlyChanged && ! $this->hasChanged($key))
            {
                continue;
            }

            $return[$key] = $this->__get($key);
        }

        // Loop over our mapped properties and add them to the list...
        if (is_array($this->datamap))
        {
            foreach ($this->datamap as $from => $to)
            {
                if (array_key_exists($to, $return))
                {
                    $return[$from] = $this->__get($to);
                }
            }
        }

        $this->_cast = true;
        return $return;
    }

    public function toRawArray(bool $onlyChanged = false): array
    {
        $return = [];

        if (! $onlyChanged)
        {
            return $this->attributes;
        }

        foreach ($this->attributes as $key => $value)
        {
            if (! $this->hasChanged($key))
            {
                continue;
            }

            $return[$key] = $this->attributes[$key];
        }

        return $return;
    }

    public function hasChanged(string $key = null): bool
    {
        // If no parameter was given then check all attributes
        if ($key === null)
        {
            return     $this->original !== $this->attributes;
        }

        // Key doesn't exist in either
        if (! array_key_exists($key, $this->original) && ! array_key_exists($key, $this->attributes))
        {
            return false;
        }

        // It's a new element
        if (! array_key_exists($key, $this->original) && array_key_exists($key, $this->attributes))
        {
            return true;
        }

        return $this->original[$key] !== $this->attributes[$key];
    }


    public function __get(string $key)
    {
        $key    = $this->mapProperty($key);
        $result = null;

        // Convert to CamelCase for the method
        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

        // if a set* method exists for this key,
        // use that method to insert this value.
        if (method_exists($this, $method))
        {
            $result = $this->$method();
        }

        // Otherwise return the protected property
        // if it exists.
        else if (array_key_exists($key, $this->attributes))
        {
            $result = $this->attributes[$key];
        }

        // Do we need to mutate this into a date?
        if (in_array($key, $this->dates))
        {
            $result = $this->mutateDate($result);
        }
        // Or cast it as something?
        else if ($this->_cast && ! empty($this->casts[$key]))
        {
            $result = $this->castAs($result, $this->casts[$key]);
        }

        return $result;
    }

    //--------------------------------------------------------------------

    /**
     * Magic method to all protected/private class properties to be easily set,
     * either through a direct access or a `setCamelCasedProperty()` method.
     *
     * Examples:
     *
     *      $this->my_property = $p;
     *      $this->setMyProperty() = $p;
     *
     * @param string $key
     * @param null   $value
     *
     * @return $this
     * @throws \Exception
     */
    public function __set(string $key, $value = null)
    {
        $key = $this->mapProperty($key);

        // Check if the field should be mutated into a date
        if (in_array($key, $this->dates))
        {
            $value = $this->mutateDate($value);
        }

        $isNullable = false;
        $castTo     = false;

        if (array_key_exists($key, $this->casts))
        {
            $isNullable = strpos($this->casts[$key], '?') === 0;
            $castTo     = $isNullable ? substr($this->casts[$key], 1) : $this->casts[$key];
        }

        if (! $isNullable || ! is_null($value))
        {
            // Array casting requires that we serialize the value
            // when setting it so that it can easily be stored
            // back to the database.
            if ($castTo === 'array')
            {
                $value = serialize($value);
            }

            // JSON casting requires that we JSONize the value
            // when setting it so that it can easily be stored
            // back to the database.
            if (($castTo === 'json' || $castTo === 'json-array') && function_exists('json_encode'))
            {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);

                if (json_last_error() !== JSON_ERROR_NONE)
                {
                    throw CastException::forInvalidJsonFormatException(json_last_error());
                }
            }
        }

        // if a set* method exists for this key,
        // use that method to insert this value.
        // *) should be outside $isNullable check - SO maybe wants to do sth with null value automatically
        $method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
        if (method_exists($this, $method))
        {
            $this->$method($value);

            return $this;
        }

        // Otherwise, just the value.
        // This allows for creation of new class
        // properties that are undefined, though
        // they cannot be saved. Useful for
        // grabbing values through joins,
        // assigning relationships, etc.
        $this->attributes[$key] = $value;

        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Unsets an attribute property.
     *
     * @param string $key
     *
     * @throws \ReflectionException
     */
    public function __unset(string $key)
    {
        unset($this->attributes[$key]);
    }

    //--------------------------------------------------------------------

    /**
     * Returns true if a property exists names $key, or a getter method
     * exists named like for __get().
     *
     * @param string $key
     *
     * @return boolean
     */
    public function __isset(string $key): bool
    {
        $key = $this->mapProperty($key);

        $method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

        if (method_exists($this, $method))
        {
            return true;
        }

        return isset($this->attributes[$key]);
    }

    /**
     * Set raw data array without any mutations
     *
     * @param  array $data
     * @return $this
     */
    public function setAttributes(array $data)
    {
        $this->attributes = $data;
        $this->syncOriginal();
        return $this;
    }

    //--------------------------------------------------------------------

    /**
     * Checks the datamap to see if this column name is being mapped,
     * and returns the mapped name, if any, or the original name.
     *
     * @param string $key
     *
     * @return mixed|string
     */
    protected function mapProperty(string $key)
    {
        if (empty($this->datamap))
        {
            return $key;
        }

        if (! empty($this->datamap[$key]))
        {
            return $this->datamap[$key];
        }

        return $key;
    }

    //--------------------------------------------------------------------

    /**
     * Converts the given string|timestamp|DateTime|Time instance
     *
     * @param $value
     *
     * @return Time
     * @throws \Exception
     */
    protected function mutateDate($value)
    {
        if ($value instanceof Time)
        {
            return $value;
        }

        if ($value instanceof \DateTime)
        {
            return Time::instance($value);
        }

        if (is_numeric($value))
        {
            return Time::createFromTimestamp($value);
        }

        if (is_string($value))
        {
            return Time::parse($value);
        }

        return $value;
    }

    protected function castAs($value, string $type)
    {
        if (strpos($type, '?') === 0)
        {
            if ($value === null)
            {
                return null;
            }
            $type = substr($type, 1);
        }

        switch($type)
        {
            case 'int':
            case 'integer': //alias for 'integer'
                $value = (int)$value;
                break;
            case 'float':
                $value = (float)$value;
                break;
            case 'double':
                $value = (double)$value;
                break;
            case 'string':
                $value = (string)$value;
                break;
            case 'bool':
            case 'boolean': //alias for 'boolean'
                $value = (bool)$value;
                break;
            case 'object':
                $value = (object)$value;
                break;
            case 'array':
                if (is_string($value) && (strpos($value, 'a:') === 0 || strpos($value, 's:') === 0))
                {
                    $value = unserialize($value);
                }

                $value = (array)$value;
                break;
            case 'json':
                $value = $this->castAsJson($value);
                break;
            case 'json-array':
                $value = $this->castAsJson($value, true);
                break;
            case 'datetime':
                return $this->mutateDate($value);
            case 'timestamp':
                return strtotime($value);
        }

        return $value;
    }

    private function castAsJson($value, bool $asArray = false)
    {
        $tmp = ! is_null($value) ? ($asArray ? [] : new \stdClass) : null;
        if (function_exists('json_decode'))
        {
            if ((is_string($value) && strlen($value) > 1 && in_array($value[0], ['[', '{', '"'])) || is_numeric($value))
            {
                $tmp = json_decode($value, $asArray);

                if (json_last_error() !== JSON_ERROR_NONE)
                {
                    throw new \Exception(json_last_error());
                }
            }
        }
        return $tmp;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }
}