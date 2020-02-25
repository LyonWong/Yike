<?php


namespace Core;


trait unitHttp
{
    public function httpLocation($URL)
    {
        header("Location: $URL");
    }

    public function httpError($code)
    {
        header("HTTP/1.1 $code");
        header("status: $code");
    }

}