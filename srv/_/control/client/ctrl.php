<?php


namespace _\client;


use _\ctrl_;

class ctrl extends ctrl_
{
    public function run()
    {
        echo 'run client<br>';
        echo "URI: $this->_URI_<br>";
        echo "WAY: $this->_WAY_<br>";
    }

}