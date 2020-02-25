<?php


namespace _\cli;


use _\dataLesson;
use _\dataLessonAccess;
use _\dataMoney;
use _\dataOrder;
use _\dataTicket;
use _\dataUser;
use _\servLesson;
use _\servMoney;
use _\servOrder;
use _\servPayoff;
use _\sign\servWeixin;
use _\weixin\serv;
use Core\library\Http;
use Student\pay\servRefund;

class ctrlTest extends ctrl_
{
    public function _DO_refundMode()
    {
        $servLesson = servLesson::sole();
        $lessonSn = 'L58fef03f22293';
        $lessonId = $servLesson->sn2id($lessonSn);
        $uname = uniqid('User');
        $uid = dataUser::sole()->append(0, $uname);

        $dataAccess = dataLessonAccess::sole();

        /*
        $data = [
            'lesson_id' => $lessonId,
            'uid' => $uid,
            'i_event' => dataLessonAccess::EVENT_ACCESS,
            'tms' => strToDate('-10 minutes', 'Y-m-d H:i:s'),
            'args' => '{}',
        ];
        dataLessonAccess::sole()->insert($data);
        */

        echo LF."未听课自由退款...";
        echo $servLesson->returnRefundMode($lessonSn, $uid) == $servLesson::REFUND_MODE_FREELY ? 'ok' : 'fail';

        $aid = $dataAccess->append($lessonId, $uid, dataLessonAccess::EVENT_ACCESS);
        echo LF."听课1小时内自由退款...";
        $dataAccess->update(['tms' => strToDate('-10 minutes', 'Y-m-d H:i:s')], ['id' => $aid]);
        echo $servLesson->returnRefundMode($lessonSn, $uid) == $servLesson::REFUND_MODE_FREELY ? 'ok' : 'fail';

        echo LF."听课1小时后申请退款...";
        $dataAccess->update(['tms' => strToDate('-2 hours', 'Y-m-d H:i:s')], ['id' => $aid]);
        $mode = $servLesson->returnRefundMode($lessonSn, $uid);
        echo $mode == $servLesson::REFUND_MODE_APPLY ? 'ok' : 'fail';

        echo LF."听课3天后不能退款...";
        $dataAccess->update(['tms' => strToDate('-4 days', 'Y-m-d H:i:s')], ['id' => $aid]);
        $mode = $servLesson->returnRefundMode($lessonSn, $uid);
        echo $mode == false ? 'ok' : 'fail';

        echo LF."申请退款被拒绝3天内可以申诉...";
        $tid = dataTicket::sole()->commit(dataTicket::TYPE_REFUND_APPLY, $uid, ['lesson_sn' => $lessonSn]);
        dataTicket::sole()->status($tid, dataTicket::STATUS_REJECT);
        $mode = $servLesson->returnRefundMode($lessonSn, $uid);
        echo $mode == $servLesson::REFUND_MODE_APPEAL ? 'ok' : 'fail';

        echo LF."申请退款被拒绝超过三天不能退款...";
        dataTicket::sole()->update(
            ['tms_update' => strToDate('-4 days', 'Y-m-d H:i:s')],
            ['id'=>$tid]);
        $mode = $servLesson->returnRefundMode($lessonSn, $uid);
        echo $mode == false ? 'ok' : 'fail';

        echo LF;

    }

    public function _DO_payoff()
    {
        $uid = 1;
        $lessonId = 1;
        $lessonSn = servLesson::sole(null)->id2sn($lessonId);

        $dataOrder = dataOrder::sole(null);
        $servOrder = servOrder::sole(null);

        $orderSn = $dataOrder->append($uid, 1, 1, 100, 1);
        echo "orderSn: $orderSn".LF;
        $_date = strToDate('-4 days', 'Y-m-d H:i:s');
        $dataOrder->update([
            'tms_create' => $_date,
            'i_status' => dataOrder::STATUS_PAID
        ], ['sn' => $orderSn]);


        $firm = $servOrder->confirm($orderSn);
        echo "confirm: $firm".LF;

        $servPayoff = servPayoff::sole(null);
        $servPayoff->settlement($_date);

        $refund = servRefund::sole(null)->refund($lessonSn, $uid);
        echo "refund: $refund".LF;
    }

    public function _DO_money()
    {
        $uid = 9;
        $res = servMoney::sole(null)->change(dataMoney::ITEM_LESSON_INCOME, $uid, 100, []);
        print_r($res);
        $res = servMoney::sole(null)->change(dataMoney::ITEM_REBATE, $uid, 70, []);
        print_r($res);
        $res = servMoney::sole(null)->change(dataMoney::ITEM_PURCHASE, $uid, -150, []);
        print_r($res);
        $res = servMoney::sole(null)->change(dataMoney::ITEM_RETURN, $uid, 150, ['var' => $res]);
        print_r($res);
    }

    public function _DO_temp()
    {
        $xml = '<xml><foo>bar</foo></xml>';
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        var_dump($xmlstring);exit;

        $val = json_decode(json_encode($xmlstring),true);
        $dom = new \simple_html_dom();
        $res = $dom->load('<xml><foo>bar</foo></xml>');
        $r = $res->dump();
        print_r($r);exit;

        $data = ['A' => 'B'];

        $xml = "<xml>\n";
        foreach ($data as $key => $val) {
            $xml .= "<$key>$val</$key>\n";
        }
        $xml .= "</xml>";
        echo  $xml;
    }

    public function _DO_CSCheck()
    {
        $text = \input::cli('text')->value();
        $token = serv::sole($this->platform)->getAccessToken('wxa');
        $res = Http::inst()->post('https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $token, json_encode([
            'content' => $text
        ], JSON_UNESCAPED_UNICODE));
        print_r($res);
    }

}