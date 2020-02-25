<?php


namespace Student;


class ctrlZsxq extends ctrlSess
{
    public function _DO_visit()
    {
        // 临时跳转
        $domain = \config('boot', 'public', 'domain', null, '_');
        header("Location: $domain/zsxq-visit?$_SERVER[QUERY_STRING]");
    }

}