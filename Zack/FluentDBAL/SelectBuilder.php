<?php


/*
 * Copyright 2014 Yehezkel (Zack) Yovel
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class SelectBuilder extends AbstractQuery {

//    private $crud;
    private $selection;
    private $origin;
    private $where;
    private $offset;
    private $length;

    /**
     *
     * @var array of the format:
     * array(
     *      'host' => ?
     *      'dbname' => ?
     *      'user' => ?
     *      'pass' => ?
     * )
     */
//    private $dbInfo;

    /**
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     */
    function __construct($selection) {
//        $this->dbInfo = func_get_args();
        $this->selection = $selection;
    }

    /**
     * Appends the origin table.
     * This does not get prepared,
     * DO NOT put user input here!
     * @param type $origin
     * @return \QueryBuilder
     */
    public function from($origin) {
        $this->origin = $origin;
        return $this;
    }

    public function where(\WhereBuilder $where) {
        $this->where = $where;
        return $this;
    }
    
    public function limit(){
        $args = func_get_args();
        if(sizeof($args>1)){
            $this->offset = $args[0];
            $this->length = $args[1];
        }else{
            $this->length = $args[0];
        }
        
    }

    function getSqlString() {
        $result = "SELECT $this->selection FROM $this->origin";
        if ($this->where) {
            $result .= $this->where;
        }
        if(isset($this->offset)){
            $result .= " LIMIT $this->offset, $this->length";
        }else if(isset($this->length)){
            $result .= " LIMIT $this->length";
        }
        return $result;
    }
    
    function bindComponents(PDOStatement $pdoStatements){
        if($this->where){
            $this->where->bindToStatement($pdoStatements);
        }
    }
    
    public function send($host, $dbname, $user, $password, $encoding = "'utf8'") {
        return parent::send($host, $dbname, $user, $password, $encoding)->fetchAll(PDO::FETCH_ASSOC);
    }

}
