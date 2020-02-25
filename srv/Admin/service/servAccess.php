<?php

namespace Admin;

use Core\unitInstance;

class servAccess
{
    use unitInstance;

    /* 权限位 */
    const PRIV_FULL = -1;// 全部
    const PRIV_DENY = 0; // 拒绝
    const PRIV_VIEW = 1; // 查看
    const PRIV_EDIT = 2; // 修改
    const PRIV_EXEC = 4; // 执行

    const SCOPE_ADMIN = 'admin';

    const TYPE_MENU = 1;
    const TYPE_MORE = 2;

    /**
     * @var servAccess
     */
    public static $now;

    private $uid;

    private $auths;
    private $scopeList;
    private $scopeDict;
    private $scopeAuth;
    private $scopePriv;

    public function __construct($uid, $scope)
    {
        $this->uid = $uid;
        $this->scopeList = dataScopeList::inst($scope)->fetchList();
        self::sort($this->scopeList);
        $this->auths = self::getAuths($uid);
        foreach ($this->scopeList as $item) {
            $key = $item['key'];
            $prekey = substr($key, 0, strrpos($key, '-'));
            $item['prekey'] = $prekey;
            $item['names'] = array_merge($this->scopeDict[$prekey]['names']??[], [$item['name']]);
            $this->scopeDict[$key] = $item;
        }
//        \output::debug('scopelist', $this->scopeList, 3, DEBUG_REPORT_JSC);
//        \output::debug('auths', $this->auths, 3, DEBUG_REPORT_JSC);
    }

    /**
     * @param $scope
     * @param $uid
     * @return servAccess
     */
    public static function inst($uid, $scope)
    {
        return self::_singleton($uid, $scope);
    }

    /**
     * 获取用户权限
     * @param $uid
     * @return array
     */
    public static function getAuths($uid)
    {
        $res = dataScopeUser::fetchByUid($uid);
        $groupAuth = dataScopeGroup::fetchAuths(...$res['groups']);
        return  self::mergeAuths($res['auths'], ...$groupAuth);
    }

    /**
     * 指定权限域
     * @param $field
     * @param callable|null $walker
     * @return $this
     */
    public function assign($field, callable $walker=null)
    {
        $this->scopeAuth = self::authorize($this->scopeList, $this->auths[$field]??[]);
        if ($walker) {
            array_walk($this->scopeAuth, $walker);
        }
        $this->scopePriv = array_column($this->scopeAuth, 'priv', 'key');
        return $this;
    }

    /**
     * 获取权限树
     * @param callable|null $filter
     * @return array
     */
    public function getScopeTree(callable $filter=null)
    {
        $scopeAuth = $this->getScopeAuth($filter);
        return self::build($scopeAuth);
    }

    /**
     * 获取授权后的域
     * @param callable|null $filter
     * @return array
     */
    public function getScopeAuth(callable $filter=null)
    {
        if ($filter) {
            return array_filter($this->scopeAuth, $filter);
        } else {
            return $this->scopeAuth;
        }
    }
    
    public function getScopeList()
    {
        return $this->scopeList;
    }
    
    public function getScopeDict($key = null)
    {
        if ($key) {
            return $this->scopeDict[$key] ?? null;
        } else {
            return $this->scopeDict;
        }
    }

    /**
     * 获取授权域列表
     * @param string|callable $filter Preg pattern or callable function
     * @return array
     */
    public function getFields($filter=null)
    {
        $fields = array_keys($this->auths);
        if (is_string($filter)) {
            return array_filter($fields, function($v) use ($filter) {
                return preg_match($filter, $v);
            });
        }
        if (is_callable($filter)) {
            return array_filter($fields, $filter);
        }
        return $fields;
    }

    /**
     * 获取授权域权限
     * @param $field
     * @param null $privFilter
     * @return array
     */
    public function getFieldAuth($field, $privFilter=null)
    {
        $auth = $this->auths[$field] ?? [];
        if ($privFilter) {
            return array_filter($auth, function($p) use ($privFilter) {
                return $p & $privFilter;
            });
        } else {
            return $auth;
        }
    }

    /**
     * 检查当前授权域中某项权限是否被许可
     * @param $key
     * @param $priv
     * @return bool
     */
    public function isAllowed($key, $priv)
    {
        if ($this->scopePriv[$key] & $priv) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查某权限域中的某点权限
     * @param $field
     * @param $point
     * @param $priv
     * @return bool
     */
    public function hasPriv($field, $point, $priv)
    {
        $auth = $this->auths[$field] ?? [];
        if ( ($auth['*']??null) & $priv) {
            return true;
        }
        if ( ($auth[$point]??null) & $priv) {
            return true;
        }
        return false;
    }

    public function checkView($key)
    {
        if ($this->isAllowed($key, self::PRIV_VIEW) === false) {
            header('Location: /sign-in?callbackURI='.$_SERVER['REQUEST_URI']);
//            \viewException::halt("No view authority!", 403);
        }
    }

    public function checkPriv($field, $point, $priv)
    {
        if ($this->hasPriv($field, $point, $priv) == false) {
            \privException::halt("No privilege to access `$field`");
        }
    }

    /**
     * Authorize Scope
     * @param array $scope
     * @param array $auths
     * @return array
     */
    public static function authorize(array $scope, array $auths): array
    {
        $map = array_column($scope, 'id', 'key');

        foreach ($scope as &$item) {
            $id = $item['id'];
            foreach (['!', '', '*'] as $p) { //匹配叶子节点权限值
                $point = $id.$p;
                if (isset($auths[$point])) {
                    $priv = $auths[$point];
                    break;
                }
            }
            foreach (self::splitKey($item['key']) as $k) { //拆分key, 由近及远匹配
                $i = $map[$k];
                if (!isset($priv) && isset($auths["$i*"])) { //匹配路径上的权限值
                    $priv = $auths["$i*"];
                    break;
                }
            }
            if (!isset($priv) && isset($auths['*'])) { //检查根节点权限值
                $priv = $auths['*'];
            }
            $item['priv'] = $priv??null;
            unset($priv);
        }
        return $scope;
    }

    public static function splitKey($key)
    {
        $res = [$key];
        if ($p = strrpos($key, '-')) {
            $preKey = substr($key, 0, $p);
            $res = array_merge($res, self::splitKey($preKey));
        }
        return $res;
    }
    
    public static function sort(&$scope)
    {
        // order by `depth` ASC, `rank` DESC, `id` DESC
        usort($scope, function ($a, $b) {
            foreach ([
                         'depth' => 1,
                         'rank' => -1,
                         'id' => -1,
                     ] as $key => $res) {
                if ($a[$key] > $b[$key]) {
                    return $res;
                }
                if ($a[$key] < $b[$key]) {
                    return 0 - $res;
                }
            }
            return 0;
        });
    }

    /**
     * Build Scope Tree
     * @param $scope
     * @return array
     */
    public static function build($scope)
    {
        self::sort($scope);
        $map = array_flip(array_column($scope, 'key'));
        $res = [];
        while ($item = array_pop($scope)) {
            if ($p = strrpos($item['key'], '-')) {
                $prekey = substr($item['key'], 0, $p);
                $preId = $map[$prekey];
                switch ($item['type']) {
                    case self::TYPE_MENU:
                        $scope[$preId]['next'][] = $item;
                        break;
                    case self::TYPE_MORE:
                        $scope[$preId]['more'][] = $item;
                        break;
                }
            } else {
                $res[] = $item;
            }
        }
        return $res;
    }

    protected static function mergeAuths(...$auths)
    {
        $res = [];
        foreach ($auths as $auth) {
            foreach ($auth as $p => $privs) {
                foreach ($privs as $i => $v) {
                    if (empty($res[$p][$i])) {
                        $res[$p][$i] = $v;
                    } else {
                        $res[$p][$i] |= $v;
                    }
                }
            }
        }
        return $res;
    }


}