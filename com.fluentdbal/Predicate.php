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

class Predicate {

    const CATEGORY_VALUE = 2;
    const CATEGORY_PARAM = 3;

    private static $count = 0;

    /**
     *
     * @var string
     */
    private $tables;

    /**
     *
     * @var string
     */
    private $operator;

    /**
     *
     * @var mixed
     */
    private $input;

    /**
     *
     * @var int
     */
    private $inputCategory;

    /**
     * PDO::PARAM_<TYPE> flag.
     * e.g. PDO::PARAM_INT, PDO::PARAM_STR
     * @var int
     */
    private $inputType;
    
    /**
     *
     * @var string
     */
    private $parameter;

    /**
     *
     * @var \Link
     */
    private $link;

    function __construct() {
        $this->link = new Link("");
    }

    public function getTables() {
        return $this->tables;
    }

    public function getOperator() {
        return $this->operator;
    }

    public function getInput() {
        return $this->input;
    }

    public function getInputCategory() {
        return $this->inputCategory;
    }

    public function getLink() {
        return $this->link;
    }

    public function setColumns($tables) {
        $this->tables = $tables;
    }

    public function setOperator($operator) {
        $this->operator = $operator;
    }

    public function setValue($value, $type) {
        $this->setInput($value, $type, self::CATEGORY_VALUE);
    }

    public function setParam($param, $type) {
        $this->setInput($param, $type, self::CATEGORY_PARAM);
    }

    private function setInput($input, $type, $category) {
        $this->input = $input;
        $this->inputType = $type;
        $this->inputCategory = $category;
        $this->parameter = ":" . ($this->inputCategory === Predicate::CATEGORY_PARAM ? "param" : "value") . ++self::$count;
    }

    public function setLink(\Link $link) {
        $this->link = $link;
    }

    public function __toString() {
        return " $this->tables $this->operator $this->parameter $this->link";
    }
    
    public function bindInput(PDOStatement $statement){
        if ($this->inputCategory === self::CATEGORY_PARAM){
            $statement->bindParam($statement, $this->input, $this->inputType);
        }else{
            $statement->bindValue($statement, $this->input, $this->inputType);
        }
        $this->link->bindNext($statement);
    }
}
