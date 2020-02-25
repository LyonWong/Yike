<?php


namespace Teacher;

use Core\unitAPI;


class ctrlApply extends ctrlSess
{
    use unitAPI;

    const ERROR_SUB_SCRIBE = ['1.1.1', '请先关注公众号'];
    const ERROR_TOKEN = ['1.1.2', '邀请token不存在或已过期'];

    public function runBefore()
    {
        $sess = self::checkSess();
        if (servSession::sole($this->platform, $sess['usn'])->check($this->flag, $sess['token']) === false) {
            if ($this->_EXT_ == 'api') {
                $this->apiFailure(self::ERR_UNDEFINED, ['illegal session']);
            } else {
                header("Location: /sign-in?callbackURI=$this->_URI_");
            }
            return false;
        }
        $this->uid = servUser::sole($this->platform)->usn2uid($sess['usn']);
        if ($this->uid) {
            $this->usn = $sess['usn']; //拥有合法的uid才设置usn
        }

        return true;
    }

    public function _DO_()
    {
        $token = $this->apiGET('token', '');
        $email = \_\servTeacher::sole($this->platform)->verToken($token);
        $tokenStatus = true;
        if (!$email) {
            $tokenStatus = false;
        }
        if (servApply::sole($this->platform)->killCookies() && $_COOKIE[self::SESS_COOKIE]) {
            header('location:/sign-in?callbackURI=/apply?token=' . $token);
        }
        $show = $this->apiGET('show', '');
        if (!$show) {
            $status = \_\servTeacher::sole($this->platform)->uid2status($this->uid);
            if ($status) {
                header("Location: /");
                exit;
            }
        }
        $datum = \_\servTeacher::sole($this->platform)->uid2datum($this->uid);
        $profile = servUser::sole($this->platform)->uid2profile($this->uid);
        $subscribe = servUser::sole($this->platform)->uid2info($this->uid, 'subscribe')['subscribe'];
        \view::tpl('apply/apply')
            ->with('email', $email)
            ->with('profile', $profile)
            ->with('subscribe', $subscribe)
            ->with('datum', json_decode($datum, true))
            ->with('tokenStatus', $tokenStatus)
            ->with('token', $token);
    }

    // 提交申请资料
    public function _POST_submit()
    {
        $unitTeacherDatum = new \_\unitTeacherDatum();
        $token = $this->apiPOST('token');
        $email = \_\servTeacher::sole($this->platform)->verToken($token);
        if (!$email) {
            $this->apiFailure(self::ERROR_TOKEN);
        }
        $name = $this->apiPOST('name');
        $email = $this->apiPOST('email');
        $unitTeacherDatum->about = $this->apiPOST('about');
        $telephone = $this->apiPOST('telephone');
        $subScribe = servUser::sole($this->platform)->uid2info($this->uid, 'subscribe')['subscribe'];
        if (!$subScribe) {
            $this->apiFailure(self::ERROR_SUB_SCRIBE);
        }
        servApply::sole($this->platform)->apply($this->uid, $unitTeacherDatum);
        servApply::sole($this->platform)->submit($this->uid, $name, $email, $telephone);
        if (isset($_FILES['cover']['tmp_name']) && $_FILES['cover']['tmp_name'] != null) {
            servApply::sole($this->platform)->putFile($this->uid, $_FILES['cover']['tmp_name']);
        }
        $this->apiSuccess();
    }

    public function _DO_subscribe()
    {
        $ret = servUser::sole($this->platform)->uid2info($this->uid, 'subscribe')['subscribe'];
        $this->apiSuccess(['subscribe' => $ret]);
    }

}