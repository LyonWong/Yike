<?php


namespace Teacher;


class ctrlUser extends ctrlSess
{
    public function _GET_profile()
    {
        $usn = $this->apiRequest('usn', $this->usn);
        $info = servUser::sole($this->platform)->usn2profile($usn);
        $this->apiSuccess($info);
    }

    public function _GET_status()
    {

    }

    public function _DO_profile()
    {
        $usn = $this->apiRequest('usn', $this->usn);
        $info = servUser::sole($this->platform)->usn2profile($usn);
        $this->apiSuccess($info);
    }

    public function _DO_certificate()
    {
        \view::tpl('page', [
            'page' => 'certificate'
        ]);
    }

    public function _POST_datum()
    {
        // 初期采用邀请制，后期开放申请

        //todo 邀请token验证
        //todo 资料更新
        $unitTeacherDatum = new \_\unitTeacherDatum();
        $unitTeacherDatum->name = $this->apiPOST('name');
        $unitTeacherDatum->about = $this->apiPOST('resume');
        $res = \_\servTeacher::sole($this->platform)->apply($this->uid, $unitTeacherDatum);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
        }
    }


}