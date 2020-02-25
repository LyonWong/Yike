<?php

namespace _\cli;


use _\config;
use _\data;
use _\servLesson;
use _\servMpMsg;
use _\servOrder;
use _\servRefund;
use _\servTIM;
use _\servTrigger;
use _\servUser;
use Core\library\Mysql;

MYSQL::$GLOBAL_OPTIONS[\PDO::MYSQL_ATTR_INIT_COMMAND] = "set wait_timeout=86400, interactive_timeout=86400";

class ctrlDaemon extends ctrl_
{
    public function _DO_()
    {
        /**
         * todo
         * 增加register_shutdown
         * 增加异常终止检查
         * 增加启动、停止日志记录
         */
        $this->_DO_redis();
        register_shutdown_function(function(){
            \output::debug('daemon', "crash down");
        });
    }

    public function _DO_redis()
    {
        $redis = data::redis('notify');
        $database = config::load('redis', 'notify', 'database', 0);
        $redis->subscribe(["__keyevent@{$database}__:expired"], [$this, 'redisTrigger']);
    }

    public function redisTrigger($redis, $channel, $message)
    {
        list($tag, $str) = explode(':', $message);
        parse_str($str, $args);
        $res = null;
        $platform = $args['_platform_'] ?? null;
        switch ($tag) {
            case servTrigger::TAG_LESSON_UPCOME:
                //向讲师发送开课提醒
                $res = servMpMsg::sole($platform)->sendUpCome($args['lesson_sn']);
                break;
            case servTrigger::TAG_LESSON_UP2STUDENT:
                //开课前1小时向学员发送提醒
                $res = servMpMsg::sole($platform)->beforeOneHourNotice($args['lesson_sn']);
                break;
            case servTrigger::TAG_LESSON_START:
                $res = servMpMsg::sole($this->platform)->sendOpenClass($args['lesson_sn']);
                break;
            case servTrigger::TAG_LESSON_PAUSE:
                $res = servTIM::sole($platform)->systemMessage(
                    "$args[lesson_sn]-T", servTIM::SYS_MSG_HINT, "讲师暂时离开"
                );
                break;
            case servTrigger::TAG_LESSON_ABORT:
                $res = servTIM::sole($platform)->systemMessage(
                    "$args[lesson_sn]-T", servTIM::SYS_MSG_HINT, "讲师授课超时，课程终止"
                );
                servLesson::sole($platform)->finish($args['lesson_sn'], 0);
                break;
            case servTrigger::TAG_LESSON_DELAY:
                //推送课程变更通知
                servMpMsg::sole($platform)->sendChangeNotice(
                    $args['lesson_sn'],
                    '讲师未能按时开课',
                    '我们暂时下架了该课程，并将在查明情况后做出后续调整'
                    );
                servLesson::sole($platform)->close($args['lesson_sn'], 0);
                break;
            case servTrigger::TAG_LESSON_SILENT:
                //讲师沉默
                //todo 向发送自动结束提醒
                break;
            case servTrigger::TAG_LESSON_FINISH:
                servLesson::sole($platform)->finish($args['lesson_sn'], 0);
                break;
            case servTrigger::TAG_REFUND_REMIND:
                $usn = servUser::sole($platform)->uid2usn($args['uid']);
                $res = servTIM::sole($platform)->systemMessage(
                    "$args[lesson_sn]-T",
                    servTIM::SYS_MSG_HINT,
                    "您的无条件退款时间还剩5分钟",
                    [$usn]
                );
                break;
            case servTrigger::TAG_REFUND_LAPSE:
                $servOrder = servOrder::sole($args['_platform_'] ?? null);
                if ($orderSn = $servOrder->findPaidOrder($args['uid'], $args['lesson_sn'])) {
                    $res = $servOrder->confirm($orderSn);
                }
                break;
            case servTrigger::TAG_REFUND_AUTO:
                //未听课自动退款
                //servRefund::sole($platform)->autoRefund($args['lesson_sn']);
                break;
            case servTrigger::TAG_LESSON_FINISH2NOTIN:
                //未听课提醒
                /*
                $res = servMpMsg::sole($platform)->lessonOpenRemind($args['lesson_sn'], $args['past'], $args['left']);
                if ($args['past'] == 24) {
                    servTrigger::sole($platform)->touch(servTrigger::TAG_LESSON_FINISH2NOTIN,
                        ['lesson_sn'=>$args['lesson_sn'], 'past' => 72, 'left' => 96], SECONDS_HOUR * 48);
                }
                if ($args['past'] == 72) {
                    servTrigger::sole($platform)->touch(servTrigger::TAG_LESSON_FINISH2NOTIN,
                        ['lesson_sn'=>$args['lesson_sn'], 'past' => 144, 'left' => 24], SECONDS_HOUR * 72);
                }
                */
                break;

        }
        \output::debug('daemon', "trigger::$message|" . json_encode($res), DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
    }

}