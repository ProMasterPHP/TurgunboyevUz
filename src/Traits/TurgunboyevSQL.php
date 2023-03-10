<?php
namespace TurgunboyevUz;

/**
 * @author Turg'unboyev Diyorbek
 * @license MIT license
 * @privacy Mualliflik huquqini hurmat qiling!
 * 
 * Bog'lanish uchun - Telegram: @Turgunboyev_D
*/

trait TurgunboyevSQL{

    protected $query;
    protected $do;
    protected $columns;
    protected $column_name;

    private function isAssoc(array $arr){
        if (array() === $arr) return false;
            return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function checkSQL(){
        $filter = array_filter([
            'host'=>!isset($this->host),
            'dbuser'=>!isset($this->db_user),
            'dbpass'=>!isset($this->db_pass),
            'dbname'=>!isset($this->db_name)
        ]);

        if(count($filter)>0){
            $description = "Database information not included: ";
            foreach($filter as $key => $value){
                $desciption .= $key." ";
            }

            return [
                'error_type'=>'mysql_include',
                'description'=>$description
            ];
        }else{
            return true;
        }
    }

    public function connect(){

        if(is_array($this->checkSQL())){
            TelegramError::mysql($this->checkSQL());
        }else{

            $conn = new \mysqli($this->host, $this->db_user, $this->db_pass, $this->db_name);

            if($conn->connect_errno or $conn->connect_error){
                return TelegramError::mysql([
                    'error_code'=>$conn->connect_errno,
                    'description'=>$conn->connect_error
                ]);
            }else{
                return $conn;
            }
        }
    }

    public function ascend(){
        return "ASC";
    }

    public function descend(){
        return "DESC";
    }

    public function limit($from, $offset){
        return [
            'from'=>$from,
            'offset'=>$offset
        ];
    }
    private function quoteColumn($column){
        if(is_bool($column)){
            if($column == true){
                $column = "TRUE";
            }elseif($column == false){
                $column = "FALSE";
            }
        }elseif(is_null($column)){
            $column = "NULL";
        }else{
            if($column[0] == "'"){}else{
                $column = "'".$column;
            }

            if($column[strlen($column)-1] == "'"){}else{
                $column .= "'";
            }
        }

        return $column;
    }

    private function curvedQuote($column){
        if($column != "*"){
            if($column[0] == "`"){}else{
                $column = "`".$column;
            }

            if($column[strlen($column)-1] == "`"){}else{
                $column .= "`";
            }

            return $column;
        }else{
            return $column;
        }
    }

    private function prepareSelectColumns($columns){
        $prepare = "";
        if($this->isAssoc($columns)){
            foreach($columns as $key => $value){
                $prepare .= $this->curvedQuote($key)." AS ".$this->curvedQuote($value).", ";
            }
        }else{
            foreach($columns as $key){
                $prepare .= $this->curvedQuote($key).", ";
            }
        }

        $ex = explode(",", $prepare);
        unset($ex[count($ex)-1]);

        return implode(',', $ex);
    }

    private function prepareWhereCondition($params){
        $prepare = "WHERE ";
        if(is_string($params)){
            $prepare .= $params;

            return $prepare;
        }else{
            foreach($params as $key => $value){
                $prepare .= $this->curvedQuote($key)." = ".$this->quoteColumn($value)." AND ";
            }

            $ex = explode("AND", $prepare);
            unset($ex[count($ex)-1]);

            return implode('AND', $ex);
        }
    }

    private function prepareOrderCondition($param){
        $prepare = "ORDER BY ";
        if($this->isAssoc($param)){
            foreach($param as $key => $value){
                $prepare .= $this->quoteColumn($key)." ".$value.", ";
            }
        }else{
            foreach($param as $key){
                $prepare .= $this->quoteColumn($key).", ";
            }
        }

        $ex = explode(",", $prepare);
        unset($ex[count($ex)-1]);

        return implode(',', $ex);
    }

    private function prepareLimitCondition($param){
        $prepare = "LIMIT ";
        if(is_array($param)){
            $prepare .= $param['from'].", ".$param['offset'];
        }else{
            $prepare .= $param;
        }

        return $prepare;
    }

    public function select($table, $columns, $params = []){
        $query = "SELECT ".$this->prepareSelectColumns($columns)." FROM ".$this->curvedQuote($table);

        if(isset($params['where'])){
            $query .= " ".$this->prepareWhereCondition($params['where']);
        }

        if(isset($params['order'])){
            $query .= " ".$this->prepareOrderCondition($params['order']);
        }

        if(isset($params['limit'])){
            $query .= " ".$this->prepareLimitCondition($params['limit']);
        }

        $query .= ";";
        $query = str_replace("  ", " ", $query);

        $this->query = $query;

        return $this;
    }

    private function prepareInsertValues($values){
        $column = [];
        $data = [];
        
        foreach($values as $key => $value){
            $column[] = $this->curvedQuote($key);
            $data[] = $this->quoteColumn($value);
        }

        $prepare = "(".implode(" , ", $column).") VALUES(".implode(" , ", $data).")";

        return $prepare;
    }

    public function insert($table, $values){
        $query = "INSERT INTO ".$this->curvedQuote($table)." ".$this->prepareInsertValues($values). ";";
        $this->query = $query;

        return $this;
    }

    private function prepareUpdateValues($values){
        $prepare = "";
        if($this->isAssoc($values)){
            foreach($values as $key => $value){
                $prepare .= $this->curvedQuote($key)." = ".$this->quoteColumn($value).", ";
            }
            $ex = explode(",", $prepare);
            unset($ex[count($ex)-1]);

            return implode(',', $ex);
        }else{
            return false;
        }     
    }

    public function update($table, $values, $where = []){
        $query = "UPDATE ".$this->curvedQuote($table)." SET ".$this->prepareUpdateValues($values);
        if(count($where) > 0){
            $query .= " ".$this->prepareWhereCondition($where);
        }

        $query .= ";";
        $this->query = $query;

        return $this;
    }

    public function delete($table, $where){
        $query = "DELETE FROM ".$this->curvedQuote($table);
        if(count($where) > 0){
            $query .= " ".$this->prepareWhereCondition($where);
        }

        $query .= ";";
        $this->query = $query;

        return $this;
    }

    public function getSQL(){
        $this->columns = [];
        $this->column_name = "";
        return $this->query;
    }

    public function do(){
        $this->columns = [];
        $this->column_name = "";
        if(isset($this->query)){
            if(isset($this->connect)){
                $conn = $this->connect;
            }else{
                $conn = $this->connect();
                $this->connect = $conn;
            }
            $do = $conn->query($this->query);
            
            $this->do = $do;

            return $this;
        }else{
            return false;
        }
    }

    public function getDO(){
        $this->columns = [];
        $this->column_name = "";
        return $this->do;
    }

    public function rows(){
        if(!empty($this->do)){
            return $this->do->num_rows;
        }else{
            return false;
        }
    }

    public function fetch(){
        if(isset($this->do)){
            $fetch = $this->do->fetch_assoc();

            return $fetch;
        }else{
            return false;
        }
    }

    public function fetch_all($value = MYSQLI_ASSOC){
        if(isset($this->do)){
            $fetch = $this->do->fetch_all($value);

            return $fetch;
        }else{
            return false;
        }
    }

    /**
     * @var Create Table query builder
    */

    public function bool(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "BOOL";

        $this->columns = $column;

        return $this;
    }
    public function bit($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "BIT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function int($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "INT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function tinyint($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TINYINT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function smallint($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "SMALLINT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function mediumint($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "MEDIUMINT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function bigint($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "BITINT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function float($size, $dec){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "FLOAT(".$size.", ".$dec.")";

        $this->columns = $column;

        return $this;
    }
    public function double($size, $dec){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "DOUBLE(".$size.", ".$dec.")";

        $this->columns = $column;

        return $this;
    }
    public function dec($size, $dec){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "DECIMAL(".$size.", ".$dec.")";

        $this->columns = $column;

        return $this;
    }

    public function char($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "CHAR(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function varchar($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "VARCHAR(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function bin($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "BINARY(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function varbin($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "VARBINARY(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function sql_text($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TEXT(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function blob($size){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "BLOB(".$size.")";

        $this->columns = $column;

        return $this;
    }
    public function tinytext(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TINYTEXT";

        $this->columns = $column;

        return $this;
    }
    public function tinyblob(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TINYBLOB";

        $this->columns = $column;

        return $this;
    }
    public function mediumtext(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "MEDIUMTEXT";

        $this->columns = $column;

        return $this;
    }
    public function mediumblob(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "MEDIUMBLOB";

        $this->columns = $column;

        return $this;
    }
    public function longtext(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "LONGTEXT";

        $this->columns = $column;

        return $this;
    }
    public function longblob(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "LONGBLOB";

        $this->columns = $column;

        return $this;
    }
    public function enum($array){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "ENUM(".implode(", ",$array).")";

        $this->columns = $column;

        return $this;
    }

    public function date(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "DATE";

        $this->columns = $column;

        return $this;
    }
    public function datetime(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "DATETIME";

        $this->columns = $column;

        return $this;
    }
    public function timestamp(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TIMESTAMP";

        $this->columns = $column;

        return $this;
    }
    public function time(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "TIME";

        $this->columns = $column;

        return $this;
    }
    public function year(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_type'] = "YEAR";

        $this->columns = $column;

        return $this;
    }


    public function notNull(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_not_null'] = true;

        $this->columns = $column;

        return $this;
    }
    public function primaryKey(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_primary_key'] = true;

        $this->columns = $column;

        return $this;
    }
    public function autoIncrement(){
        $column = $this->columns;
        $name = $this->column_name;

        $column[$name]['column_auto_increment'] = true;

        $this->columns = $column;

        return $this;
    }

    public function column($name){
        $columns = $this->columns;
        $columns[$name] = [];

        $this->columns = $columns;
        $this->column_name = $name;
        return $this;
    }

    public function createTable($table, $array){
        $prepare = "CREATE TABLE IF NOT EXISTS ".$this->curvedQuote($table)."(\n";
        if(!$this->isAssoc($array)){
            $columns = $this->columns;

            foreach($columns as $column => $column_value){
                $string = $column;

                if(isset($column_value['column_type'])){
                    $string .= " ".$column_value['column_type'];
                }else{
                    return false;
                    break;
                }

                if(isset($column_value['column_not_null'])){
                    $string .= " NOT NULL";
                }

                if(isset($column_value['column_primary_key'])){
                    $string .= " PRIMARY KEY";
                }

                if(isset($column_value['column_auto_increment'])){
                    $string .= " AUTO_INCREMENT";
                }

                $string .= ",\n";

                $prepare .= $string;
            }

            $ex = explode(",", $prepare);
            unset($ex[count($ex)-1]);

            $prepare = implode(',', $ex);
            $prepare .= "\n);";

            $this->query = $prepare;

            return $this;
        }else{
            return false;
        }
    }

    public function dropTable($table){
        $this->query = "DROP TABLE ".$this->curvedQuote($table);

        return $this;
    }
}
?>
