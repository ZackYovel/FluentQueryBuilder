<?php

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
