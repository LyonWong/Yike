<?php


namespace Teacher;


use _\servFeedback;

class ctrlFeedback extends ctrlSess
{
    public function _DO_()
    {}


    public function _POST_submit()
    {
        $text = $this->apiPOST('text');
        $image = $this->apiPOST('image', '');
        servFeedback::sole($this->platform)->submit($this->uid, $text, $image);
        $this->apiSuccess();
    }

}