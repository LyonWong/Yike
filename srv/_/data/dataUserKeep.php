<?php


namespace _;


use Core\unitInstance;

class dataUserKeep extends dataSole
{
    use unitInstance;

    const TABLE='user_keep';

    const ITEM_MONEY = 1;
    const ITEM_PAYOFF = 2;

    /**
     * @param null $platform
     * @return self
     */
    public static function sole($platform=null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function set($uid, $item, $data)
    {
        $data['tms'] = date('Y-m-d H:i:s');
        $rowCount = $this->update($data, [
            'uid' => $uid,
            'i_item' => $item,
        ])->rowCount();
        if ($rowCount) {
            return $rowCount;
        } else {
            $data['uid'] = $uid;
            $data['i_item'] = $item;
            $this->insert($data, ['num', 'str', 'txt', 'obj']);
            return $this->mysql->lastInsertId();
        }
    }

    public function get($uid, $item, $field='*')
    {
        return $this->fetchOne(['uid' => $uid, 'i_item' => $item], $field);
    }

    public function num($uid, $item, $value=null)
    {
    }

    public function str($uid, $item, $value=null)
    {}

    public function txt($uid, $item, $value=null)
    {}

    public function obj($uid, $item, $value=null)
    {
        if ($value) {
            return $this->set($uid, $item, ['obj'=>json_encode($value)]);
        } else {
            $res = $this->get($uid, $item, 'obj');
            return json_decode($res['obj'], true);
        }
    }

    public function attr($uid, $item, $path, $value=null)
    {}

    public function setAttr($uid, $item, $path, $value)
    {
        $res = $this->fetchOne(['uid' => $uid, 'i_item'=>$item], ['id']);
        if (!$res) {
            $this->insert(['uid' => $uid, 'i_item'=>$item, 'obj'=>'{}']);
        }
        $this->mysql->s("update `$this->TABLE` set obj = json_set(obj,'$path',?)", [$value])->w(['uid'=>$uid, 'i_item'=>$item])->e();
    }

    public function varAttr($uid, $item, $path, $value)
    {
        $res = $this->fetchOne(['uid' => $uid, 'i_item'=>$item], ['id']);
        if (!$res) {
            $this->insert(['uid' => $uid, 'i_item'=>$item, 'obj'=>'{}']);
        }
        $this->mysql->s("update `$this->TABLE` set obj=json_set(obj,'$path',if(obj->'$path',obj->'$path',0)+?)", [$value ?: 0])->w(['uid'=>$uid, 'i_item'=>$item])->e();
    }

    public function varBalance($uid, $amount)
    {}

    public function varPayoff($uid, $amount, $deductGross=false)
    {
        $res = $this->fetchOne(['uid'=>$uid, 'i_item' => self::ITEM_PAYOFF], ['id', 'obj']);
        if ($res) {
            $data = json_decode($res['obj'], true);
            if ($amount > 0 || $deductGross==true) {
                $data['gross'] += $amount;
            }
            $data['remain'] += $amount;
            $this->update(['obj'=>json_encode($data)], ['id'=>$res['id']]);
        } else {
            $data = [
                'gross' => $amount,
                'remain' => $amount,
            ];
            $this->insert(['uid' => $uid, 'i_item' => self::ITEM_PAYOFF, 'obj'=>json_encode($data)]);
        }
    }

    public function getPayoff($uid)
    {
        $obj = $this->fetchOne(['uid'=>$uid, 'i_item'=>self::ITEM_MONEY], 'obj', 0);
        $expect = dataPayoff::sole($this->platform)->fetchOne(['uid' => $uid, 'order_status'=>dataOrder::STATUS_PAID], "sum(amount)", 0);
        if ($obj) {
            $res = json_decode($obj, true);
            return [
                'gross' => $res['payoff'],
                'remain' => $res['cash'],
                'expect' => max($res['expect'], $res['payoff']) + $expect,
            ];
        } else {
            return [
                'gross' => 0,
                'remain' => 0,
                'expect' => 0,
            ];
        }
    }

}