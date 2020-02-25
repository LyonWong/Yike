<?php


namespace Student;



class ctrlLink extends ctrlSess
{
    public function _DO_()
    {
        $path = \input::get('path', '/')->value();
        $query = \input::get('query')->value();
        $hash = \input::get('hash')->value();
        $this->httpLocation("$path?$query#$hash");
    }
    public function _DO_enroll()
    {
        $tsn = \input::get('tsn')->value();
        $org = \input::get('org')->value();
        $domain = \config::load('boot', 'public', 'domain', null, 'Student');
        $url = $_SERVER['REQUEST_SCHEME']."://$domain/?v=1";
        if ($tsn[0] == 'L') {
            $url .= "#/course/detail/brief?lesson_sn=$tsn&origin=$org";
        }
        if ($tsn[0] == 'S') {
            $url .= "#/course/series/detail/$tsn/brief/?origin=$org";
        }
        $this->httpLocation($url);
    }

    public function _DO_user()
    {
        $this->httpLocation('/?v=1#/user');
    }
}