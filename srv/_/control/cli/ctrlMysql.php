<?php


namespace _\cli;


use Core\library\Mysql;
use Core\unitFile;

class ctrlMysql extends ctrl_
{
    use unitFile;

    protected $mysql;

    public function __construct()
    {
        $this->mysql = Mysql::inst('yike');
    }

    public function _DO_test()
    {
        $version = $this->mysql->s("select version()")->e()->fetch(0);
        if ($version) {
            echo "Mysql version: $version\n";
        } else {
            echo "Failed to connect to mysql.";
        }
    }

    public function _DO_export()
    {
        $hereDDLs = $this->mysql->showStructure('yike');
        foreach ($hereDDLs as $key => $ddl) {
            if (preg_match("#stats_daily_\d{8}#", $key)) {
                unset($hereDDLs[$key]);
            }
        }
        $hereDDL = implode(";\n\n", $hereDDLs).";\n";
        $this->fileWrite('database.sql', $hereDDL);
    }

    public function _DO_diff()
    {
        $base = \input::cli('base')->value(true);
        $diff = \input::cli('diff')->value(true);
        $baseStruct = $this->mysql->showStructure($base);
        $diffStruct = $this->mysql->showStructure($diff);
        $res = array_diff_assoc($baseStruct, $diffStruct);
        foreach ($res as $table => $null) {
            echo "Table:$table\n";
            echo "Base: $baseStruct[$table]\n";
            echo "-------------------------\n";
            echo @"Diff: $diffStruct[$table]\n";
            echo "========================\n\n";
        }
    }

    public function _DO_check()
    {
        $hereDDL = $this->fileRead('database.sql');
        $dbname = \config::load('mysql', 'yike', 'dbname');
        $res = $this->check($hereDDL, $dbname);
    }

    protected function check($struct, $dbname)
    {
        $check = $this->mysql->showStructure($dbname);
        $structs = explode(';', $struct);
        $_s = [];
        $res = true;
        foreach ($structs as $DDL) {
            if (!preg_match('#CREATE TABLE IF NOT EXISTS `(\w+)`#', $DDL, $matches)) {
                continue;
            }
            $table = $matches[1];
            $DDL = trim($DDL);
            if (empty($check[$table]) || $check[$table] != $DDL) {
                $base = explode("\n", $DDL);
                $diff = explode("\n", $check[$table]);
                echo "$dbname.$table\n";
                echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n";
                echo "Base: \n";
                foreach ($base as $i => $_base) {
                    if (isset($diff[$i]) && $_base == $diff[$i]) {
                        echo "  $_base\n";
                    } else {
                        echo "> $_base\n";
                    }
                }
                echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n";
                echo @"Diff: \n";
                foreach ($diff as $i => $_diff) {
                    if (isset($base[$i]) && $_diff == $base[$i]) {
                        echo "  $_diff\n";
                    } else {
                        echo "< $_diff\n";
                    }
                }
                echo "================================\n\n";
                $res = false;
            }
            if ($table[0] == '_') {
                $table = trim($table, '_');
                $_s[$table] = $DDL;
            }
        }
        return $res;
        /*
        foreach ($check as $table => $DDL) {
            list($prefix) = explode('_', $table);
            if (isset($_s[$prefix]) && $_s[$prefix] != $DDL) {
                echo "$dbname.$table\n";
                echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\n";
                echo "Base: $DDL\n";
                echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n";
                echo @"Diff: $check[$table]\n";
                echo "================================\n\n";
            }
        }
        */
    }

}
