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

class UpdateBuilder {

    private $selection;
//    private $origin;
    /**
     *
     * @var WhereBuilder
     */
    private $where;

    /**
     *
     * @var SetPredicate
     */
    private $setHead;

    /**
     *
     * @var SetPredicate
     */
    private $setTail;

    function __construct($selection) {
        $this->selection = $selection;
        $this->setHead = new SetPredicate();
        $this->setTail = $this->setHead;
    }

    public function where(WhereBuilder $where) {
        $this->where = $where;
        return $this;
    }

    public function set($column) {
        $this->setTail->setColumns($column);
        return $this;
    }

    public function toValue($value) {
        $this->setTail->setValue($value);
        return $this;
    }

    public function toParam($param) {
        $this->setTail->setParam($param);
        return $this;
    }

    public function andSet($column) {
        $link = $this->setTail->getLink();
        $this->tail = $link->getNext();
        return $this->set($column);
    }

}
