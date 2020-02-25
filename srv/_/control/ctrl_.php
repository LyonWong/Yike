<?php


namespace _;

class ctrl_
{
    /**
     * Resource Identifier
     * @var
     */
    public $_URI_;

    /**
     * controller path
     * @var
     */
    public $_WAY_;

    /**
     * extension type
     * @var
     */
    public $_EXT_;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function runBefore()
    {
        return true;
    }

    public function runBehind()
    {
        return true;
    }
    
    public function debugOnJSC($name, $content)
    {
        \output::debug($name, $content, DEBUG_SLOT_TEMP, DEBUG_REPORT_JSC);
    }
}