<?php


namespace _;


use Core\unitInstance;

class dataLessonRecord extends dataSole
{
    use unitInstance;

    const FORM_TIM = 1; // 腾讯云通信直播
    const FORM_VIEW = 2; // 静态播放
    const TYPE_DELETE = -1;

    const TABLE = 'lesson_record';

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
        $this->TABLE = self::TABLE;
    }

    public function append($lessonId, $fromUid, $iForm, $content)
    {
        $data = [
            'lesson_id' => $lessonId,
            'from_uid' => $fromUid,
            'i_type' => $iForm,
            'content' => $content,
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }

    public function slice($iForm, $lessonId, $id, $toward, int $limit)
    {
        $where = [
            'i_type' => $iForm,
            'lesson_id' => $lessonId,
        ];
        $orderBy = 'order by ';
        switch ($toward) {
            case data::TOWARD_NEXT:
                if ($id) {
                    $where["id>?"] = [$id];
                }
                $orderBy .= "id";
                break;
            case data::TOWARD_PREV:
                if ($id) {
                    $where["id<?"] = [$id];
                }
                $orderBy .= "id desc";
                break;
            case data::TOWARD_FORE:
                if ($id) {
                    $where ['id>=?'] = [$id];
                }
                $orderBy .= "id";
                break;
            case data::TOWARD_HIND:
                if ($id) {
                    $where ['id<=?'] = [$id];
                }
                $orderBy .= "id desc";
                break;
        }
        if ($limit > 0) {
            $limit = "limit $limit";
        } else {
            $limit = '';
        }
        $res = $this->mysql->select($this->TABLE, '*', $where, "$orderBy $limit")->fetchAll();
        switch ($toward) {
            case data::TOWARD_PREV:
            case data::TOWARD_HIND:
                $res = array_reverse($res);
                break;
        }
        return $res;
    }
}