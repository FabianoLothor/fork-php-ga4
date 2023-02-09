<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Facade;
use AlexWestergaard\PhpGa4\Helper\Helper;

class UserProperty implements Facade\Export
{
    protected $name;
    protected $value;

    public function setName(string $name)
    {
        if (
            in_array($name, Helper::RESERVED_USER_PROPERTY_NAMES)
            || substr($name, 0, 9) == 'firebase_'
            || substr($name, 0, 7) == 'google_'
            || substr($name, 0, 4) == 'ga_'
        ) {
            throw new GA4Exception("Name '{$name}' is reserved or restricted");
        } elseif (mb_strlen($name) > 24) {
            throw new GA4Exception("Name '{$name}' is longer than 24 characters");
        }

        $this->name = $name;
        return $this;
    }

    public function setValue(int|float|string $value)
    {
        $this->value = $value;
        return $this;
    }

    public function getParams(): array
    {
        return ['name', 'value'];
    }

    public function getRequiredParams(): array
    {
        return ['name', 'value'];
    }

    public function toArray(): array
    {
        $return = [
            'name' => $this->name,
            'value' => $this->value,
        ];

        if (!is_array($this->value)) {
            $return['value'] = ['value' => $return['value']];
        }

        return $return;
    }

    public static function new()
    {
        return new static();
    }
}
