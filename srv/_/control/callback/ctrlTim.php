<?php


namespace _\callback;


use _\servLesson;
use _\servTIM;
use _\servUser;

class ctrlTim extends ctrl_
{
    protected $log;

    protected $data;

    protected $platform;

    public function run()
    {
        $SdkAppId = \input::get('SdkAppid')->value();
        $post = \phpStream::input();
        $this->log = $this->_URI_.LF.$post.LF;

        if ($SdkAppId != \config::load('tencent', 'im', 'SdkAppId')) {
            $this->_response('FAIL', "Illegal SdkAppId `$SdkAppId`", 1);
        }

        $this->data = json_decode($post);
        if (!$this->data) {
            $this->_failure('Illegal post');
        };

        list($mdl, $cmd) = explode('.', $this->data->CallbackCommand);

        $method = "{$mdl}_{$cmd}";
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->_response('FAIL', "Illegal cmd `{$this->data->CallbackCommand}`", 1);
        }


    }

    public function __destruct()
    {
        \output::debug('tim', $this->log, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        parent::__destruct();
    }

    public function _response($ActionStatus, $ErrorInfo, $ErrorCode)
    {
        $res = json_encode([
            'ActionStatus' => $ActionStatus,
            'ErrorInfo' => $ErrorInfo,
            'ErrorCode' => $ErrorCode,
        ]);
        $this->log .= "Response: $res";
        echo $res;
        exit;
    }

    public function _success()
    {
        $this->_response('OK', '', 0);
    }

    public function _failure($info)
    {
        $this->_response('FAIL', $info, 1);
    }

    protected function Group_CallbackBeforeApplyJoinGroup()
    {
        list($lessonSn, $chatType) = explode('-', $this->data->GroupId);
        $usn = $this->data->Requestor_Account;
        $servLesson = servLesson::sole($this->platform);
        $res = $servLesson->checkAccess($lessonSn, $this->data->Requestor_Account, $message);
        if ($res) {
            if ($chatType == 'T') { // 只记录进入授课区的日志
                $uid = servUser::sole($this->platform)->usn2uid($usn);
                $servLesson->access($lessonSn, $uid);
                if ($servLesson->checkSpeak($lessonSn, $usn) == false) {
                    $rs = servTIM::sole($this->platform)->tim()->group_forbid_send_msg($this->data->GroupId, $usn, SECONDS_DAY);
                    $this->log .= "NoSpeak:".json_encode($rs);
                }
            }
            $this->_success();
        } else {
            $this->_failure($message);
        }
    }

    protected function Group_CallbackAfterSendMsg()
    {
        list($lessonSn, $chatType) = explode('-', $this->data->GroupId);
        if ($chatType != 'T') {
            $this->_success(); //只处理讲师授课
        }
        servLesson::sole($this->platform)->setRecord($lessonSn, $this->data->From_Account, $this->data);
        $this->_success();
    }

    //{"CallbackCommand":"State.StateChange","Info":{"To_Account":"U59048ee5624d8","Action":"Logout","Reason":"TimeOut"}}
    protected function State_StateChange()
    {
        if ($this->data->Info->Action == 'Logout') {
            $args = [
                'ip' => $_GET['ClientIP']
            ];
            servLesson::sole($this->platform)->leave($this->data->Info->To_Account, $args);
        }
    }


}