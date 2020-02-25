<?php


namespace _\callback;



class ctrl_ extends \_\ctrl_
{
    protected $debug = [];

    protected $platform = null;

    public function runBefore()
    {
        $prev = parent::runBefore();
        $this->debug['URI'] = $this->_URI_;
        $this->debug['REQUEST'] = $_REQUEST;
        $this->debug['POST_RAW'] = \input::postRawData();
        ob_start();
        return $prev;
    }

    public function runBehind()
    {
        $this->debug['RESPONSE'] = ob_get_contents();
        \output::debug('callback', $this->debug, DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
        ob_end_flush();
        return parent::runBehind();
    }

}