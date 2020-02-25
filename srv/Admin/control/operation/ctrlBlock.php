<?php


namespace Admin\operation;


use Admin\servBlock;

class ctrlBlock extends ctrl_
{
    public $scopeKey = 'operation-block';

    public function _DO_()
    {
        $data = servBlock::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'operation/block',
            'types' => servBlock::TYPE_MAP,
        ])
            ->with('data', $data);
    }

    public function _POST_create()
    {
        $data = [
            'key' => \input::post('key')->value(),
            'name' => \input::post('name')->value(),
            'extra' => \input::post('extra', '{}')->value(),
            'type' => \input::post('type', 'default')->value(),
            'weight' => \input::post('weight')->toInt(),
        ];
        servBlock::sole($this->platform)->create($data);
        $this->httpLocation('./block');
    }

    public function _POST_modify()
    {

        $key = \input::post('key')->value();
        $data = [
            'name' => \input::post('name')->value(),
            'extra' => \input::post('extra')->value(),
            'type' => \input::post('type')->value(),
            'weight' => \input::post('weight')->toInt(),
        ];
        servBlock::sole($this->platform)->modify($key, array_filter($data));
        $this->httpLocation('./block');
    }

}