<?php


namespace Core;


class unitResult
{
    public $error;

    public $message;

    public $data;

    public static function inst()
    {
        return new self;
    }

    public function set($error=0, $message='', $data=[])
    {
        $this->error = $error;
        $this->message = $message;
        $this->data = $data;
        return $this;
    }

    public function err($message)
    {
        return $this->set(1, $message);
    }

    public function ok($data=null)
    {
        return $this->set(0, 'ok', $data);
    }
}