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

class Link {

    /**
     *
     * @var string
     */
    private $glue;

    /**
     *
     * @var \Predicate
     */
    private $next;

    const OR_GLUE = 2;
    const AND_GLUE = 3;

    function __construct() {
        $this->next = new Predicate();
    }

    public function getGlue() {
        return $this->glue;
    }

    public function getNext() {
        return $this->next;
    }

    public function setGlue($glue) {
        switch ($glue) {
            case self::OR_GLUE:
                $this->glue = "OR";
                break;
            case self::AND_GLUE:
                $this->glue = "AND";
                break;
            default:
                $this->glue = "";
        }
    }

    public function setNext(\Predicate $next) {
        $this->next = $next;
    }

    public function __toString() {
        return " $this->glue $this->next";
    }

    public function bindNext(PDOStatement $statement) {
        if ($this->next) {
            $this->next->bindInput($statement);
        }
    }

}
