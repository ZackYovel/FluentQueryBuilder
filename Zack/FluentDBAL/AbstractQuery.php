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

abstract class AbstractQuery{
      
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
    
    abstract private function getSqlString();
    abstract private function bindComponents(PDOStatement $pdoStatement);
    
    public function send($host, $dbname, $user, $password, $encoding = "'utf8'") {
        $pdo = $this->getConnection($host, $dbname, $user, $password, $encoding);
        try {
            $sqlString = $this->getSqlString();
            $pdoStatement = $pdo->prepare($sqlString);
            $this->bindComponents($pdoStatement);
            $pdoStatement->execute();
            return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            print "Error!: " . $ex->getMessage() . "<br/>";
            exit($ex->getCode());
        }
    }

}
