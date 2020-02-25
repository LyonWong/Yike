<?php


namespace Teacher;


use _\config;
use _\dataPromote;
use _\servOrigin;
use _\servPromote;
use _\unitLessonPromote;
use Core\library\Http;

class ctrlPromote extends ctrlSess
{
    public function _DO_()
    {}

    public function _POST_create()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $originId = servOrigin::sole($this->platform)->key2id("teascher-$this->usn");
        $unitPromote = unitLessonPromote::inst(dataPromote::TYPE_COUPON);
        $unitPromote->remark = $this->apiPOST('remark');
        $unitPromote->discount = $this->apiPOST('discount');
        $unitPromote->commission = 0;
        $srvPromote = servPromote::sole($this->platform);
        $sn = $srvPromote->create($this->uid, $lessonSn, $originId, $unitPromote);
        $data = $srvPromote->info($sn);
        $this->apiSuccess($data);
    }

    public function _POST_invite()
    {
        $lessonSn = $this->apiPOST('lesson_sn');
        $originId = servOrigin::sole($this->platform)->key2id("teascher-$this->usn");
        $unitPromote = unitLessonPromote::inst(dataPromote::TYPE_COUPON);
        $unitPromote->remark = $this->apiPOST('remark');
        $unitPromote->discount = $this->apiPOST('discount');
        $unitPromote->commission = $this->apiPOST('commission');
        $srvPromote = servPromote::sole($this->platform);
        $psn = $srvPromote->create(0, $lessonSn, $originId, $unitPromote);
        $token = $srvPromote->convey($psn);
        $data = $srvPromote->info($psn);
        $domain = config::load('boot', 'public', 'domain');
        $data['token'] = $token;
        $data['invite_url'] = Http::makeURL("https://$domain/promote-invite", [
            'lesson_sn' => $lessonSn,
            'token' => $token
        ]);
        $this->apiSuccess($data);
    }

    public function _POST_quota()
    {
        $psn = $this->apiPOST('promote_sn');
        $quantity = $this->apiPOST('quantity');
        $expire = $this->apiPOST('expire');
        $qsn = servPromote::sole($this->platform)->setQuota($psn, $quantity, $expire);
        $data = [
            'quota_sn' => $qsn,
            'url' => '',
            'qrcode' => '',
        ];
        $this->apiSuccess($data);
    }




}