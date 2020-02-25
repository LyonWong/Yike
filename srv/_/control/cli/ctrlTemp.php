<?php


namespace _\cli;

use _\data;
use _\dataLesson;
use _\dataLessonAccess;
use _\dataOrder;
use _\dataPayoff;
use _\dataPayoff_;
use _\dataUserAuth;
use _\servCache;
use _\servLesson;
use _\servMpMsg;
use _\servOrder;
use _\servPayoff;
use _\servPromote;
use _\servTrigger;
use _\servUser;
use _\dataUser;
use _\weixin\serv;
use _\weixin\servMp;
use Core\library\Http;
use Core\library\WechatTpl;
use MongoDB\Driver\WriteError;

class ctrlTemp extends ctrl_
{
    public function _DO_reopen()
    {
        $lsn = \input::cli('lsn')->value(true);
        $time = \input::cli('time')->value(true);
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lsn], ['id', 'tuid', 'title', 'plan']);
        $lesson['plan'] = json_decode($lesson['plan'], true);
        $teacherName = dataUser::sole($this->platform)->fetchOne(['id' => $lesson['tuid']], 'name', 0);
        $filter = ["i_status>0"];
        $res = dataOrder::sole($this->platform)->lastOrdersByLesson($lesson['id'], $filter);
        $sender = new WechatTpl('mp');
        $domain = \config::load('boot', 'public', 'domain', '', 'Student');
        $url = 'https://' . $domain . "/live?isOwner=no&lesson_sn=$lsn&teach=$lsn-T&discuss=$lsn-D&teacherEnter=yes#/";
        $data = array(
            'channel' => 'listen_notice',
            'first' => '您报名的课程继续开始授课',
            'keyword1' => $lesson['title'],
            'keyword2' => '易灵微课',
            'keyword3' => $teacherName,
            'keyword4' => $time,
            'url' => $url,
            'remark' => "点击详情进入课堂",
        );
        foreach ($res as $item) {
            $openId = servUser::sole($this->platform)->uid2info($item['uid'], 'openid')['openid'];
            $_res = $sender::send($data, $openId);
            echo "uid:$item[uid], res:$_res" . LF;
        }
    }

    public function _DO_sendopen()
    {
        $lsn = \input::cli('lsn')->value();
        $num = \input::cli('num', 10)->value();
        $gap = \input::cli('gap', 1)->value();
        servMpMsg::sole($this->platform)->sendOpenClass($lsn, $num, $gap);
    }

    public function _DO_openRemind()
    {
        $lsn = \input::cli('lsn')->value();
        servMpMsg::sole($this->platform)->lessonOpenRemind($lsn, 72, 96);
    }


    public function _DO_reconfirm()
    {
        $lsn = \input::cli('lsn')->value(true);
        $orderSn = \input::cli('order_sn')->value();
        $servPayoff = servPayoff::sole($this->platform);
        $daoOrder = dataOrder::sole($this->platform);
        $lessonId = servLesson::sole($this->platform)->sn2id($lsn);
        if ($orderSn) {
            $order = $daoOrder->fetchOne(['sn' => $orderSn], '*');
            $order['extra'] = json_decode($order['extra'], true);
            $payoff = $servPayoff->orderPayoff($order);
            $daoOrder->update(['payoff_amount' => $payoff], ['id' => $order['id']]);
            $diff = $payoff - $order['payoff_amount'];
            \_\stats\servLesson::sole($this->platform)->varPayoff($order['lesson_id'], $order['origin_id'], $diff);
            echo "payoff: $payoff, $diff: $diff" . LF;
        } else {
            $orders = dataOrder::sole($this->platform)->fetchAll([
                'lesson_id' => $lessonId,
                'i_status' => dataOrder::STATUS_FIRM,
            ], '*');
            foreach ($orders as $order) {
                $order['extra'] = json_decode($order['extra'], true);
                $payoff = $servPayoff->orderPayoff($order);
                $daoOrder->update(['payoff_amount' => $payoff], ['id' => $order['id']]);
                $diff = $payoff - $order['payoff_amount'];
                \_\stats\servLesson::sole($this->platform)->varPayoff($order['lesson_id'], $order['origin_id'], $diff);
            }
        }
    }

    public function _DO_relapse()
    {
        $lsn = \input::cli('lsn')->value();
        $lessonId = servLesson::sole($this->platform)->sn2id($lsn);
        $srvTrigger = servTrigger::sole($this->platform);
        $uids = dataLessonAccess::sole($this->platform)->fetchAll(['lesson_id' => $lessonId, 'i_event' => dataLessonAccess::EVENT_ACCESS], 'distinct(uid) as uid', null, 0);
        foreach ($uids as $uid) {
            $srvTrigger->touch(servTrigger::TAG_REFUND_LAPSE, ['uid' => $uid, 'lesson_sn' => $lsn], 1);
        }
    }

    public function _DO_sendScript()
    {
        $lsn = \input::cli('lsn')->value();
        $num = \input::cli('num', 10)->value();
        $gap = \input::cli('gap', 1)->value();
        servMpMsg::sole($this->platform)->scriptNotice($lsn, $num, $gap);
    }

    public function _DO_sendScript2()
    {
        $lsn = \input::cli('lsn')->value();
        $num = \input::cli('num', 10)->value();
        $gap = \input::cli('gap', 1)->value();
        servMpMsg::sole($this->platform)->scriptNotice2($lsn, $num, $gap);
    }

    public function _DO_setting()
    {
        $srv = servUser::sole($this->platform);
        $srv->userSet(1, 'auto_refund', 1);
        $res = $srv->uid2setting(1, 'auto_refund');
        var_dump($res);
    }


    public function _DO_info()
    {
        $res = servUser::sole($this->platform)->usn2openid('U598bc53aa656a');
        var_dump($res);
    }

   

    public function _DO_material()
    {
        $wx = new WechatTpl('mp');
        $res = WechatTpl::listMaterial('news', 0, 10);
        $result = json_decode($res, true);
//        print_r($result);
        foreach ($result['item'] as $item) {
            echo "media_id: $item[media_id]\n";
            echo "{$item['content']['news_item'][0]['title']}\n";
            echo "update: $item[update_time]\n";
        }
    }

    public function _DO_mpush($m, $n)
    {
        $mediaId = \input::cli('mediaId')->value(true);
        $touser = $this->_DO_user($m, $n);
        $data = [
            'touser' => $touser,
            'mpnews' => [
                "media_id" => $mediaId
            ],
            "msgtype" => "mpnews",
            "send_ignore_reprint" => 0,
            "clientmsgid" => crc32($mediaId)
        ];
        $wx = new WechatTpl('mp');
        $res = WechatTpl::sendMass($data);
        \output::debug('wxmsg', $res);
        echo $res;
    }

    public function _DO_user($m, $n)
    {
        $res = dataUser::sole($this)->fetchAll("mod(id, $m)=$n and info->'$.subscribe'=1", "json_unquote(info->'$.openid')", null, 0);
        return array_filter($res);
    }

    public function _DO_datacube($action)
    {
        $data = [
            'begin_date' => \input::cli('begin_date', 'yesterday')->toDate('Y-m-d'),
            'end_date' => \input::cli('end_date', 'yesterday')->toDate('Y-m-d')
        ];
        $res = WechatTpl::dataCube($action, $data);
        $result = json_decode($res, true);
        print_r($result);
    }

    public function _DO_openid()
    {
        $daoUserAuth = dataUserAuth::sole($this->platform);
        $daoUser = dataUser::sole($this->platform);
        $limit = \input::cli('limit')->toInt();
        $res = $daoUserAuth->fetchAll(['i_type' => dataUserAuth::TYPE_WEIXIN, 'code' => ''], ['id', 'uid'], null, null, "limit $limit");
        $cnt = 0;
        foreach ($res as $row) {
            $openid = $daoUser->fetchOne(['id' => $row['uid']], "json_unquote(info->'$.openid')", 0);
            $cnt += $daoUserAuth->update(['code' => $openid], ['id' => $row['id']])->rowCount();
            echo "$cnt $row[uid] $openid".LF;
        }
        echo "cnt $cnt".LF;
    }

    public function _DO_wx()
    {
        echo serv::sole($this->platform)->getAccessToken('wxa');
    }

    public function _DO_wxa_scene()
    {
        $accessToken = serv::sole($this->platform)->getAccessToken('wxa');
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$accessToken;
        $scene = "path:/lesson/oid=14353";
        $data = [
            'scene' => $scene,
            'path' => 'page/web/view'
        ];
        $res = Http::inst()->post($url, json_encode($data));
        echo $res;
    }

    public function _DO_wx_material()
    {
        $accessToken = serv::sole($this->platform)->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$accessToken;
//        $url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$accessToken;
        $data = [
            'type' => 'image',
            'offset' => '0',
            'count' => '10',
        ];
//        var_dump ($data);
        $res = Http::inst()->post($url, json_encode($data));
//        $res = Http::inst()->get($url);
        print_r(json_decode($res));

    }

    public function _DO_payoff()
    {
        $offset = \input::cli('offset')->toInt();
        $limit = \input::cli('limit')->toInt();
        $daoOrder = dataOrder::sole($this->platform);
        $daoPayoff = dataPayoff_::sole($this->platform);
        $srvPayoff = servPayoff::sole($this->platform);
        do {
            $orders = $daoOrder->fetchAll([
                'id<?' => [$offset],
                'i_type>0',
                'i_status<>0',
                'order_amount>0'
            ], '*', null, null, "order by id desc limit $limit");
            $settleTime = strToDate('-3 days', "Y-m-d H:i:s");
            foreach ($orders as $order) {
                echo json_encode($order).LF;
                $order['extra'] = json_decode($order['extra'], true);
                $srvPayoff->fixPayoff_($order);
                if ($order['tms_update'] < $settleTime && $order['i_status'] == dataOrder::STATUS_FIRM) {
                    $daoPayoff->update(['order_status'=>dataOrder::STATUS_DONE], ['order_id' => $order['id']]);
                }
                if ($order['i_status'] == dataOrder::STATUS_REFUND) {
                    $daoPayoff->update(['order_status'=>dataOrder::STATUS_REFUND], ['order_id' => $order['id']]);
                }
            }
            $end = end($orders);
            $offset = $end ? $end['id'] : $offset-1;
        } while($offset>1);
    }

}