<?php


namespace _\cli;


use Core\library\Http;

class ctrlZsxq extends ctrl_
{
    public function _DO_bind()
    {
        $tsn = \input::cli('tsn')->value(true);
        $gid = \input::cli('gid')->value(true);
        $cid = \input::cli('cid', 'yiling')->value();
        $conf = \config::load('zsxq', $cid);
        $data = [
            'channelid' => $cid,
            'itemid' => $tsn,
            'groupid' => $gid,
            'secret' => $conf['secret'],
            'timestamp' => time(),
        ];
        $data['signature'] = $this->sign($data);
        unset($data['secret']);
        $res = Http::inst()->post($conf['entryUrl'].'/bind', $data);
        print_r($res);
    }

    public function sign($data)
    {
        ksort($data);
        $_sign = http_build_query($data);
        return sha1($_sign);
    }

}