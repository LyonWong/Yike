<?php


namespace Admin\operation;


use Admin\servLessonHub;

class ctrlLesson extends ctrl_
{
    public $scopeKey = 'operation-lesson';

    public function _DO_()
    {
        $data = servLessonHub::sole($this->platform)->list();
        \view::tpl('page', [
            'page' => 'operation/lesson',
        ])
            ->with('data', $data);
    }

    public function _POST_create()
    {
        $tsn = \input::post('tsn')->value();
        $tag = \input::post('tag')->value();
        $weight = \input::post('weight')->toFloat();
        servLessonHub::sole($this->platform)->create($tsn, ' '. trim($tag) . ' ', $weight);
        $this->httpLocation('./lesson');
    }

    public function _POST_modify()
    {
        $tsn = \input::post('tsn')->value();
        $tag = \input::post('tag')->value();
        $weight = \input::post('weight')->toFloat();
        servLessonHub::sole($this->platform)->modify($tsn, ' ' . trim($tag) . ' ' , $weight);
        $this->httpLocation('./lesson');
    }

}