<?php


namespace Admin;

use Core\unitInstance;

class servUser extends \_\servUser
{
    use unitInstance;
    
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }
    public function setScopeAuth($uid, $field, $point, $priv)
    {
        dataScopeUser::sole($this->platform)->updateAuth($uid, $field, $point, $priv);
    }

    public function setScopeGroup($uid, $groups)
    {}
}