<?php


namespace _;


use Core\unitInstance;

class servFeedback extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function submit($suid, $text, $image=null)
    {
        $content['text'] = $text;
        if ($image) {
            $imageData = preg_replace('@data:image[\s|\S]+?;base64,@', '', $image);
            $imageData = base64_decode($imageData);
            $imagePath = uniqid('feedback/image/');
            servQiniu::inst()->put($imagePath, $imageData);
            $content['image'] = $imagePath;
        }
        dataTicket::sole($this->platform)->commit(dataTicket::TYPE_FEEDBACK, $suid, $content);
        //向管理员发送通知
        $info = servUser::sole($this->platform)->uid2info($suid, 'name, telephone');

        servMpMsg::sole($this->platform)->callNotice(
            '您好，' . $info['name'] . ' 提交了一个问题反馈，有待查看',
            $info['name'] . "\r\n描述：" . $text,
            date('Y-m-d H:i'),
            $info['telephone']
        );
    }

}