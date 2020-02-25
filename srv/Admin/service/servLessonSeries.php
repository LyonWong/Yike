<?php


namespace Admin;


use _\dataLessonSeries;
use _\dataTicket;
use Core\library\Mysql;
use Core\unitInstance;

class servLessonSeries extends \_\servLessonSeries
{
    use unitInstance;

    protected $data;

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->data = dataLessonSeries::sole($this->platform);
    }
    
    public function seriesList()
    {
        $lists = dataLessonSeries::sole($this->platform)->list([]);
        foreach ($lists as &$list) {
            $list['introduce'] = json_decode($list['introduce'],true);
            $list['introduce']['price'] /= 100;
            $list['scheme'] = json_decode($list['scheme'],true);
            $list['teacher'] = servUser::sole($this->platform)->uid2profile($list['uid']);
        }
        return $lists;
    }

    public function page(unitQuerySeries $query, $pageNum, $pageStep)
    {

        if(!$query->userValue  && !$query->seriesValue) {
            $where = [
                'tms_create between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"],
            ];
        } else {
            $where = [];
        }

        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['uid'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['uid'] = servUser::sole($this->platform)->usn2uid($query->userValue);
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["uid in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->seriesValue) {
            if ($query->seriesField == 'id') {
                $where['id'] = $query->seriesValue;
            } elseif ($query->seriesField == 'sn') {
                $where['sn'] = $query->seriesValue;
            } else {
                $seriesIds = dataLessonSeries::sole($this->platform)->searchByTitle($query->seriesValue);
                $made = Mysql::makeData($seriesIds, '?',',');
                $where["id in ($made[clause])"] = $made['params'];
            }
        }

        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

}