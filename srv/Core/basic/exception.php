<?php


class coreException extends Exception
{
    const EID = 0;

    use unitException;

    public static function makeInfo($exception, $items = null)
    {
        $items = $items ? : ['code', 'message', 'file', 'line', 'trace', 'previous'];
        $info = [];
        foreach ($items as $item) {
            $method = ($item == 'trace') ? 'getTraceAsString' : sprintf('get%s', ucfirst($item));
            if (method_exists($exception, $method)) {
                $info[$item] = $exception->$method();
            } else {
                $info[$item] = null;
            }
            if ($item == 'code') {
                $info['code'] = self::parseCode($info['code']);
            }
        }
        if (isset ($info['trace'])) {
            $info['trace'] = LF . str_replace("\n", LF, $info['trace']);
        }
        if (!empty($info['previous']) && method_exists($info['previous'], 'getInfo')) {
            $info['previous'] = '{' . LF . $info['previous']->getInfo() . LF . '}' . LF;
        }
        $ret = sprintf('[%s] Exception ', date('Y-m-d H:i:s'));
        foreach ($info as $key => $content) {
            $ret .= ucfirst($key) . ": $content" . LF;
        }
        return $ret;
    }

}

class servException extends coreException
{
    const EID = 1;
    use unitException;
}

class viewException extends coreException
{
    const EID = 2;
    use unitException;
}

class privException extends coreException
{
    const EID = 3;
    use unitException;
}

trait unitException
{
    public static function halt($message='', $subCode=0, Exception $previous = null)
    {
        $EID = constant('self::EID');
        $code = self::makeCode($EID, $subCode);
        throw new self ($message, $code, $previous);
    }

    public static function makeCode($EID, $subCode)
    {
        $code = ($EID << 24) + $subCode;
        return $code;
    }

    public static function parseCode($code, $asArray=false)
    {
        $EID = $code >> 24;
        $subCode = $code & 16777215;
        if ($asArray) {
            return [$EID, $subCode];
        } else {
            return "$EID.$subCode";
        }
    }
}


