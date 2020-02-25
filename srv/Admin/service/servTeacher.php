<?php


namespace Admin;


use Core\unitInstance;
use Core\library\Email;

class servTeacher extends \_\servTeacher
{
    use unitInstance;

    const STATUS_MAP = [
        dataTeacher::STATUS_APPLYING => 'applying',
        dataTeacher::STATUS_ACCEPTED => 'accepted',
        dataTeacher::STATUS_CREDIBLE => 'credible',
        dataTeacher::STATUS_REJECTED => 'rejected',
    ];

    protected $data;

    protected $cache = [];

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->data = dataTeacher::sole($this->platform);
    }

    public function list()
    {
        $res = $this->data->fetchAll(null, '*');
        foreach ($res as &$item) {
            $item['datum'] = json_decode($item['datum'], true);
            $item['profile'] = servUser::sole($this->platform)->uid2profile($item['tuid']);
        }
        return $res;
    }

    public function datum($tuid)
    {
        $ckey = "datum-$tuid";
        if (empty($this->cache[$ckey])) {
            $res = $this->data->fetchOne(['tuid' => $tuid], ['datum'], 0);
            $this->cache[$ckey] = json_decode($res, true);
        }
        return $this->cache[$ckey];
    }

    public function status($tuid, $status = null)
    {
        $where = ['tuid' => $tuid];
        if ($status) {
            $res = $this->data->update([
                'i_status' => $status,
            ], $where);
        } else {
            $res = $this->data->fetchOne($where, 'i_status', 0);
        }
        return $res;
    }

    public function sendInviteEmail($email)
    {
        $mailer = Email::SMTP('noreply');
        $token = $this->genToken($email);
        $domain = \config::load('boot', 'public', 'domain', null, 'Teacher');
        $link = "$_SERVER[REQUEST_SCHEME]://$domain/apply?token=$token";
        $mailer->Subject = "易灵微课讲师邀请师函";
        $mailer->Body =
            \view::tpl('/sign/email-invite')
                ->with('link', $link)
                ->with('domain', $domain)
                ->res();
        $mailer->addAddress($email);
        return $mailer->send();

    }

    public function detail($tuid)
    {
        $teacher = dataTeacher::sole($this->platform)->fetchOne(['tuid' => $tuid], ['datum', 'i_status', 'rate_count', 'rate_score']);
        $teacher['datum'] = json_decode($teacher['datum'], true);
        $teacher['i_status'] = self::STATUS_MAP[$teacher['i_status']];
        $user = dataUser::sole($this->platform)->fetchOne(['id' => $tuid], ['sn', 'name', 'origin_id', 'info', 'setting', 'tms_create', 'tms_update']);
        $user['info'] = json_decode($user['info'], true);
        $user['setting'] = json_decode($user['setting'], true);

        return array_merge($teacher,$user);
    }

    public function updateAbout($tuid,$about)
    {
        $datum = dataTeacher::sole($this->platform)->fetchOne(['tuid' => $tuid], 'datum',0);
        $datum = json_decode($datum,true);
        $datum['about'] = $about;

        return dataTeacher::sole($this->platform)->update(['datum'=>json_encode($datum)],['tuid'=>$tuid])->rowCount();

    }


}