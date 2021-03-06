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

class QueryFactory {

    public static function select($selection) {
        return new SelectBuilder($selection);
    }

    public static function update($selection){
        return new UpdateBuilder($selection);
    }
//    
//    public static function insert($selection){
//        return new \Zack\FluentDBAL\SelectBuilder($selection);
//    }
//    
//    public static function drop($selection){
//        return new \Zack\FluentDBAL\SelectBuilder($selection);
//    }
//    
//    public static function alter($selection){
//        return new \Zack\FluentDBAL\SelectBuilder($selection);
//    }
}
