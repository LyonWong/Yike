<?php


namespace _;


use Core\unitHttp;

class ctrlPromote extends ctrlSess
{
    use unitHttp;

    public function _GET_invite()
    {
        $token = \input::get('token')->value();
        if ($sn = servPromote::sole($this->platform)->assign($this->uid, $token)) {
            $domain = \config::load('boot', 'public', 'domain', null, 'Student');
            $this->httpLocation("$_SERVER[REQUEST_SCHEME]://$domain/promote-receive?sn=$sn");
        } else {
            echo "链接已失效";
        }
    }

    public function _GET_card()
    {
        $targetSn = \input::get('target_sn')->value();
        $domain = config::load('boot', 'public', 'domain');
        $this->httpLocation("$_SERVER[REQUEST_SCHEME]://$domain/promote/invite?sn=$targetSn");
    }

}