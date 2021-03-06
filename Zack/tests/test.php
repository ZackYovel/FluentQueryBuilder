<?php

// tests/test.php

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

include '../FluentDBAL/QueryFactory.php';
include '../FluentDBAL/AbstractQuery.php';
include '../FluentDBAL/SelectBuilder.php';
include '../FluentDBAL/UpdateBuilder.php';
include '../FluentDBAL/WhereBuilder.php';
include '../FluentDBAL/Predicate.php';
include '../FluentDBAL/Link.php';
include '../FluentDBAL/SetPredicate.php';
include '../FluentDBAL/SetLink.php';

echo '<pre>';

// Baisic test for the QueryBuilder working with a WhereBuilder with two
// predicates bound by "OR".



function f() {
    $query = QueryFactory::select('*')
            ->from('persons')
            ->where((new WhereBuilder())
            ->column('id')
            ->equalsValue(2)
            ->appendOr()
            ->column('name')
            ->equalsValue('John Doe')
    );
    $result = $query->send('localhost', 'test', 'root', '');
    var_dump($result);
}

//f();

$query = QueryFactory::update('persons')
        ->set('name')
        ->toValue('Name is missing')
        ->where((new WhereBuilder())
        ->column('name')
        ->equalsValue('')
);
var_dump($query->send('localhost', 'test', 'root', ''));

//f();
