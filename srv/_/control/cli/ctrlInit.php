<?php


namespace _\cli;


use _\config;
use _\dataTeacher;
use _\dataUser;
use _\servOrigin;
use _\servUser;

class ctrlInit extends ctrl_
{
    public function _DO_origin()
    {
        $list = config::load('info', 'origin');
        $servOrigin = servOrigin::sole(null);
        foreach ($list as $key => $name) {
            $id = $servOrigin->checkIn($key, $name);
            echo "$id | $key | $name\n";
        }
    }

    public function _DO_thome()
    {
        $uids = dataTeacher::sole($this->platform)->fetchAll(null, ['tuid'], null, 0);
        foreach ($uids as $uid) {
            $profile = servUser::sole($this->platform)->uid2profile($uid);
            servOrigin::sole($this->platform)->checkIn("home-$profile[sn]", "讲师主页-$profile[name]");
        }
    }

}