<?php


namespace Admin;

use _\dataTicket;
use Core\unitInstance;

class servTicket extends \_\servTicket
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
        $this->data = dataTicket::sole($this->platform);
    }




}