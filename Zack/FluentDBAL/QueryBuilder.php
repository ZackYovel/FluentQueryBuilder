<?php

namespace Zack\FluentDBAL;

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

class QueryBuilder {

    private $crud;
    private $selection;
    private $tables;
    private $where;

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
    private $dbInfo;

    /**
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $pass
     */
    function __construct() {
        $this->dbInfo = func_get_args();
    }

    public function insert($selection) {
        return $this->initQuery("INSERT INTO", $selection);
    }

    public function select($selection) {
        return $this->initQuery("SELECT", $selection);
    }

    public function alter($selection) {
        return $this->initQuery("ALTER", $selection);
    }

    public function delete($selection) {
        return $this->initQuery("DELETE", $selection);
    }

    private function initQuery($crud, $selection) {
        $this->crud = $crud;
        $this->selection = $selection;
        return $this;
    }

    /**
     * Appends the origin table.
     * This does not get prepared,
     * DO NOT put user input here!
     * @param type $origin
     * @return \QueryBuilder
     */
    public function from($origin) {
        $this->tables = $origin;
        return $this;
    }

    public function where(\WhereBuilder $where) {
        $this->where = $where;
        return $this;
    }

    public function send() {
        $pdo = $this->getConnection($this->dbInfo[0], $this->dbInfo[1], $this->dbInfo[2], $this->dbInfo[3]);
        try {
            $sqlString = $this->getSqlString();
            $pdoStatement = $pdo->prepare($sqlString);
            $this->where->bindToStatement($pdoStatement);
            $pdoStatement->execute();
            return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            print "Error!: " . $ex->getMessage() . "<br/>";
            exit($ex->getCode());
        }
    }

    private function getSqlString() {
        $result = "$this->crud $this->selection FROM $this->tables";
        if ($this->where) {
            $result .= " WHERE $this->where";
        }
        return $result;
    }

    private function getConnection($host, $dbname, $user, $password, $encoding = "'utf8'") {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES $encoding");
            return $pdo;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}
