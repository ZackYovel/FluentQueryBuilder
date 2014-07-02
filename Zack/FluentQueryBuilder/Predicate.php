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

    protected static $count = 0;

    /**
     *
     * @var string
     */
    protected $tables;

    /**
     *
     * @var string
     */
    protected $operator;

    /**
     *
     * @var mixed
     */
    protected $input;

    /**
     *
     * @var int
     */
    protected $inputCategory;

    /**
     * PDO::PARAM_<TYPE> flag.
     * e.g. PDO::PARAM_INT, PDO::PARAM_STR
     * @var int
     */
    protected $inputType;

    /**
     *
     * @var string
     */
    protected $parameter;

    /**
     *
     * @var \Link
     */
    protected $link;

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

    public function setValue($value) {
        $this->setInput($value, self::CATEGORY_VALUE);
    }

    public function setParam($param) {
        $this->setInput($param, self::CATEGORY_PARAM);
    }

    protected function setInput($input, $category) {
        $type = $this->getType($input);
        $this->input = $input;
        $this->inputType = $type;
        $this->inputCategory = $category;
        $this->parameter = ":" . ($this->inputCategory === Predicate::CATEGORY_PARAM ? "param" : "value") . ++self::$count;
    }

    protected function getType($input) {
        switch (gettype($input)) {
            case 'string':
                $type = PDO::PARAM_STR;
                break;
            case 'int':
                $type = PDO::PARAM_INT;
                break;
            default:
                $type = PDO::PARAM_NULL;
        }
        return $type;
    }

    public function setLink(\Link $link) {
        $this->link = $link;
    }

    public function __toString() {
        $sql = "$this->tables $this->operator $this->parameter";
        $sql .= $this->link ? " $this->link" : "";
        return $sql;
    }

    public function bindInput(PDOStatement $statement) {
        if ($this->inputCategory === self::CATEGORY_PARAM) {
            $statement->bindParam($this->parameter, $this->input, $this->inputType);
        } else {
            $statement->bindValue($this->parameter, $this->input, $this->inputType);
        }
        if ($this->link) {
            $this->link->bindNext($statement);
        }
    }

}
