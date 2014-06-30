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

class WhereBuilder {

    /**
     * This is always the newest Predicate in the list.
     * @var \Predicate
     */
    private $tail;

    /**
     * This is the first Predicate int the list.
     * @var \Predicate
     */
    private $head;

    function __construct() {
        $this->tail = new Predicate();
        $this->head = $this->tail;
    }

    /**
     * 
     * @param type $param
     * @return \WhereBuilder
     */
    public function param($param) {
        $this->tail->setParam($param);
        return $this;
    }

    /**
     * 
     * @param type $value
     * @return \WhereBuilder
     */
    public function value($value) {
        $this->tail->setValue($value);
        return $this;
    }

    /**
     * Put column name a left operator of a comparison or such.
     * This does NOT get prepared. Never put user input here!
     * @param string $colunm
     */
    public function column($colunm) {
        $this->tail->setColumns($colunm);
        return $this;
    }

    public function equalsValue($value) {
        $this->equals();
        $this->value($value);
        return $this;
    }

    public function equalsParam($param) {
        $this->equals();
        $this->param($param);
        return $this;
    }

    /**
     * 
     * @return \WhereBuilder
     */
    public function equals() {
        $this->tail->setOperator("=");
        return $this;
    }

    /**
     * 
     * @return \WhereBuilder
     */
    public function notEquals() {
        $this->tail->setOperator("<>");
        return $this;
    }

    /**
     * 
     * @return \WhereBuilder
     */
    public function appendAnd() {
        $this->appendLink(Link::AND_GLUE);
        return $this;
    }

    /**
     * 
     * @return \WhereBuilder
     */
    public function appendOr() {
        $this->appendLink(Link::OR_GLUE);
        return $this;
    }

    /**
     * 
     * @param int $glue
     */
    private function appendLink($glue) {
        $link = new Link();
        $link->setGlue($glue);
        $this->tail->setLink($link);
        $this->tail = $link->getNext();
    }

    public function __toString() {
        return " $this->head";
    }

    public function bindToStatement(PDOStatement $statement) {
        $this->head->bindInput($statement);
    }

}
