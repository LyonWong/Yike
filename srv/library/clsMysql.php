<?php


class clsMysql extends clsPDO
{

    protected $buffer;

    /**
     * @var clsPDOStatement
     */
    protected $statement;

    protected $lastRunInfo;

    /**
     * @param $statement
     * @param array $params
     * @param array $options PDO driver options
     * @return clsPDOStatement
     */
    public function run($statement, array $params = [], array $options = [])
    {
        $this->reset();
        if ($this->statement = $this->prepare($statement, $options)) {
            $this->statement->execute($params);
            $this->lastRunInfo = [
                'statement' => $statement,
                'params' => $params,
                'options' => $options
            ];
        } else { // still return clsPDOStatement if prepare failed
            $this->statement = new clsPDOStatement(null, $this);
        }
        $this->hook();
        return $this->statement;
    }

    public function buffer()
    {
        return $this->buffer;
    }

    public function statement()
    {
        return $this->statement;
    }

    public function lastRunInfo()
    {
        return $this->lastRunInfo;
    }

    private function reset()
    {
        $this->buffer = [
            'clause' => [],
            'params' => [],
        ];
        $this->statement = null;
        $this->lastRunInfo = [];
    }

    protected function hook()
    {}

    /**
     * @param $table
     * @param $fields
     * @param null $wheres
     * @param null $_
     * @return clsPDOStatement
     */
    public function select($table, $fields, $wheres = null, $_ = null)
    {
        $fields = $this->makeFields($fields);
        $this->s("SELECT $fields FROM `$table`");
        if ($wheres) {
            $this->w($wheres);
        }
        if ($_) {
            $this->a($_);
        }
        return $this->e();
    }

    /**
     * @param $table
     * @param array $data
     * @param null $onDuplicateKey
     * @return clsPDOStatement
     */
    public function insert($table, array $data, $onDuplicateKey = null)
    {
        $fields = implode('`,`', array_keys($data));
        $this->s("INSERT INTO `$table` (`$fields`) ")->v('VALUES', $data);
        if ($onDuplicateKey) {
            $this->o($onDuplicateKey);
        }
        return $this->e();
    }

    public function insertBatch($table, array $fields, array $values, $onDuplicateKey = null, $batchSize = 0)
    {
        if ($batchSize == 0) {
            $field = $this->makeFields($fields);
            $vholder = str_repeat(",?", count($fields));
            $vholder[0] = '(';
            $vholder .= ")";
            $this->s("INSERT INTO `$table` ($field) VALUES");
            $clauses = [];
            $paramts = [];
            foreach ($values as $value) {
                $clauses[] = $vholder;
                array_push($paramts, ...$value);
            }
            $clause = implode(',', $clauses);
            $this->a($clause, $paramts);
            if ($onDuplicateKey) {
                $this->o($onDuplicateKey);
            }
            $this->e();
        } else {
            $this->reset();
            foreach (array_chunk($values, $batchSize) as $_values) {
                if ($this->statement === null || count($_values) < $batchSize) {
                    $this->insertBatch($table, $fields, $_values, $onDuplicateKey);
                } else {
                    $params = [];
                    foreach ($_values as $_value) {
                        array_push($params, ...$_value);
                    }
                    $this->statement->execute($params);
                }
            }
        }
    }

    /**
     * @param $table
     * @param $data
     * @param array|string|null $where
     * @param null $_
     * @return clsPDOStatement
     */
    public function update($table, $data, $where, $_ = null)
    {
        $mdata = $this->makeData($data, '`%s`=?', ',');
        $this->s("UPDATE `$table` SET $mdata[clause]", $mdata['params']);
        if ($where) {
            $this->w($where);
        }
        if ($_) {
            $this->a($_);
        }
        return $this->e();
    }

    /**
     * @param $table
     * @param array|string|null $where
     * @param null $_
     * @return clsPDOStatement
     */
    public function delete($table, $where, $_ = null)
    {
        $this->s("DELETE FROM `$table`");
        if ($where) {
            $this->w($where);
        }
        if ($_) {
            $this->a($_);
        }
        return $this->e();
    }

    /**
     * Start SQL
     * @param $statement
     * @param array $params
     * @return clsMysql
     */
    public function s($statement, array $params = [])
    {
        $this->reset();
        return $this->a($statement, $params);
    }

    /**
     * Append SQL
     * @param $statement
     * @param array $params
     * @return $this
     */
    public function a($statement, array $params = [])
    {
        if ($statement) {
            array_push($this->buffer['clause'], $statement);
        }
        if ($params) {
            array_push($this->buffer['params'], ...$params);
        }
        return $this;
    }

    /**
     * Execute SQL
     * @param array $options
     * @return clsPDOStatement
     */
    public function e(array $options = [])
    {
        $statement = implode(' ', $this->buffer['clause']);
        $statement = preg_replace('#\s+#', ' ', $statement);
        return $this->run($statement, $this->buffer['params'], $options);
    }

    /**
     * Data format
     * @param $data
     * @param $format
     * @param string $glue
     * @return clsMysql
     */
    public function d($data, $format, $glue = ',')
    {
        if (is_object($format)) {
            $res = $format($data);
        } else {
            $res = $this->makeData($data, $format, $glue);
        }
        return $this->a($res['clause'], $res['params']);
    }

    /**
     * Where conditions
     * @param array ...$wheres
     * @return $this
     */
    public function w(...$wheres)
    {
        $where = $this->makeWheres(...$wheres);
        return $this->a('WHERE ' . $where['clause'], $where['params']);
    }

    /**
     * Key fields
     * @param array $data
     * @return clsMysql
     */
    public function k(array $data)
    {
        $fields = $this->makeFields($data);
        return $this->a($fields);
    }

    /**
     * Value set
     * @param string $a append statement
     * @param array $data
     * @return clsMysql
     */
    public function v($a, array $data)
    {
        $mdata = $this->makeData($data, '?', ',');
        return $this->a("$a ($mdata[clause])", $mdata['params']);
    }

    /**
     * Overwrite on duplicate key
     * @param null $onDuplicateKey
     * @return $this
     */
    public function o($onDuplicateKey = null)
    {
        $this->a("ON DUPLICATE KEY UPDATE");
        if (is_array($onDuplicateKey)) {
            $clause = array_reduce($onDuplicateKey, function ($carry, $item) {
                $carry .= ",`$item`=VALUES(`$item`)";
                return $carry;
            }, '');
            $clause[0] = ' ';
            $this->a($clause);
        } else {
            $this->a($onDuplicateKey);
        }
        return $this;
    }

    public static function makeFields($fields)
    {
        if (is_array($fields)) {
            return '`' . implode('`,`', $fields) . '`';
        } else {
            return $fields;
        }
    }

    /**
     * @param $data
     * @param string $format format of key
     * @param string $glue combine symbol
     * @return array
     */
    public static function makeData($data, $format, $glue)
    {
        if (is_array($data)) {
            $keys = array_keys($data);
            $values = array_values($data);
            foreach ($keys as &$key) {
                $key = sprintf($format, $key);
            }
            $clause = implode($glue, $keys);
            return [
                'clause' => $clause,
                'params' => $values,
            ];
        } else {
            return [
                'clause' => $data,
                'params' => [],
            ];
        }
    }

    public static function makeWheres(...$wheres)
    {
        $params = [];
        $ORs = [];
        foreach ($wheres as $where) {
            $ANDs = [];
            if (is_array($where)) {
                foreach ($where as $key => $val) {
                    if (is_array($val)) { // set by placeholder
                        $ANDs[] = $key;
                        $params = array_merge($params, $val);
                    } elseif (is_int($key)) { // set by num-index
                        $ANDs[] = $val;
                    } else { // set by key-value
                        $ANDs[] = "`$key`=?";
                        $params[] = $val;
                    }
                }

            } else { // set by string
                $ANDs[] = $where;
            }
            $ORs[] = '(' . implode(') AND (', $ANDs) . ')';
        }
        $clause = implode(' OR ', $ORs);
        return [
            'clause' => $clause,
            'params' => $params,
        ];
    }

}


class clsPDO
{
    /**
     * @var \PDO
     */
    protected $PDO;

    protected $options = [];

    private $configs;

    public function __construct($dsn, $username, $password, $options)
    {
        $this->configs = [$dsn, $username, $password, $options];
        $this->PDO = $this->instance();
    }

    public function instance()
    {
        return new \PDO(...$this->configs);
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return \PDO
     */
    public function PDO()
    {
        return $this->PDO;
    }

    public function close()
    {
        $this->PDO = null;
    }

    public function prepare($statement, array $options = [])
    {
        $opts = $this->options;
        foreach ($options as $key => $opt) {
            $opts[$key] = $opt;
        }
        return $this->callPDOMethod('prepare', [$statement, $opts]);
    }

    public function query($statement)
    {
        return $this->callPDOMethod('query', func_get_args());
    }

    public function exec($statement)
    {
        return $this->callPDOMethod('exec', func_get_args());
    }

    public function lastInsertId($name = null)
    {
        return $this->callPDOMethod('lastInsertId', func_get_args());
    }

    public function debug($info)
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $referer = '';
        foreach ($traces as $trace) {
            if (strstr($trace['class'], 'cls') === false) {
                $referer = @sprintf('%s::%s, line %d of %s', $trace['class'], $trace['function'], $trace['line'], $trace['file']);
                break;
            }
        }
        if (!is_array($info)) {
            $info = [$info];
        }
        $message = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $referer);
        foreach ($info as $key => $val) {
            $msg = json_encode($val);
            $flags = str_split($msg, 256); // message length limit
            $last = array_pop($flags);
            $first = array_shift($flags);
            $mid = $flags ? ' ... ' : '';
            $message .= "$key: $first$last$mid\n";
        }
        $this->log($message);
    }

    protected function log($message)
    {
        // write or overwrite your own log handler
    }


    /**
     * @param $method
     * @param $argus
     * @return clsPDOStatement|mixed|bool
     */
    private function callPDOMethod($method, $argus = [])
    {
        if ($this->PDO instanceof \PDO) {
            try {
                $res = call_user_func_array([$this->PDO, $method], $argus);
            } catch (\Exception $e) {
                $res = false;
            }
        } else {
            $res = false;
        }
        if ($res === false) {
            $info = [
                'ErrorInfo' => $this->PDO instanceof \PDO ? $this->PDO->errorInfo() : 'Illegal PDO instance.',
                'Method' => $method,
                'Argus' => $argus,
            ];
            if ($info['ErrorInfo'] === ["HY000",2006,"MySQL server has gone away"]) { //retry
                $this->PDO = $this->instance();
                return $this->callPDOMethod($method, $argus);
            }
            if (isset ($e) && $e instanceof \Exception) {
                $info['Message'] = $e->getMessage();
            }
            $this->debug($info);
        }
        if ($res instanceof \PDOStatement) {
            $res = new clsPDOStatement($res, $this);
        }
        return $res;
    }
}

class clsPDOStatement
{
    /**
     * @var \PDOStatement|null
     */
    private $PDOStatement;

    /**
     * @var clsPDO
     */
    private $clsPDO;

    public function __construct($PDOStatement, $clsPDO)
    {
        $this->PDOStatement = $PDOStatement;
        $this->clsPDO = $clsPDO;
    }

    /**
     * @return \PDOStatement
     */
    public function PDOStatement()
    {
        return $this->PDOStatement;
    }

    /**
     * @param array $params
     * @return clsPDOStatement
     */
    public function execute(array $params = null)
    {
        return $this->callPDOStatementMethod('execute', func_get_args());
    }

    /**
     * @param null|string|int $field column name or offset
     * @return bool|mixed|null
     */
    public function fetch($field = null)
    {
        $res = $this->callPDOStatementMethod('fetch');
        if ($field !== null) {
            $res = $this->arrayFetch($res, $field);
        }
        return $res;
    }

    /**
     * @param null $keyField
     * @param null $valField
     * @return array
     */
    public function fetchAll($keyField = null, $valField = null)
    {
        $res = $this->callPDOStatementMethod('fetchAll') ?: [];
        if ($keyField === null) {
            if ($valField === null) {
                $ret = $res;
            } else {
                $ret = [];
                foreach ($res as $row) {
                    $ret[] = $this->arrayFetch($row, $valField);
                }
            }
        } else {
            $ret = [];
            foreach ($res as $row) {
                $key = $this->arrayFetch($row, $keyField);
                $val = $valField === null ? $row : $this->arrayFetch($row, $valField);
                $ret[$key] = $val;
            }
        }
        return $ret;
    }

    public function rowCount()
    {
        return $this->callPDOStatementMethod('rowCount');
    }

    private function callPDOStatementMethod($method, $argus = [])
    {
        if (!$this->PDOStatement instanceof \PDOStatement) {
            return false;
        }
        try {
            $res = call_user_func_array([$this->PDOStatement, $method], $argus);
        } catch (\Exception $e) {
            $res = false;
        }
        if ($res === false && $this->PDOStatement->errorCode() != '00000') {
            $info = [
                'ErrorInfo' => $this->PDOStatement->errorInfo(),
                'SQL' => $this->PDOStatement->queryString,
                'Method' => $method,
                'Argus' => $argus,
            ];
            if (isset ($e) && $e instanceof \Exception) {
                $info['Message'] = $e->getMessage();
            }
            $this->clsPDO->debug($info);
        }
        return $res;
    }

    /**
     * @param $array
     * @param int $index
     * @return mixed|null
     */
    private function arrayFetch($array, $index = 0)
    {
        if (!is_array($array)) {
            return null;
        }
        if (isset ($array[$index])) {
            $ret = $array[$index];
        } elseif (is_int($index)) {
            while ($index-- > 0) {
                next($array);
            }
            $ret = current($array);
        } else {
            $ret = null;
        }
        return $ret;
    }

}

