<?php


namespace _;


class unitIntroduce
{
    public $type;

    public $content;

    public $cover;

    const TYPE_TEXT = 'text';

    public static function inst($type, $content,$cover)
    {
        $inst = new self($type, $content,$cover);
        return$inst;
    }

    public function __construct($type, $content,$cover)
    {
        $this->type = $type;
        $this->content = $content;
        $this->cover = $cover;
    }

    public function encode()
    {
        return json_encode([
            'type' => $this->type,
            'content' => $this->content,
            'cover' => $this->cover,
        ]);
    }

}