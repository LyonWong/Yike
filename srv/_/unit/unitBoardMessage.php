<?php


namespace _;


class unitBoardMessage
{
    public $text;

    public $file;

    public $image;

    public $audio;

    public static function inst()
    {
        return new self;
    }


    public function encode()
    {
        $data = [
            'text' => $this->text,
            'file' => $this->file,
            'image' => $this->image,
            'audio' => $this->audio,
        ];
        return json_encode(array_filter($data), JSON_FORCE_OBJECT);
    }

}