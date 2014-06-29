Fluent-DBAL
===========

A light, easy to use, fluent builder-style DataBase Abstraction Layer for php.

Installation:
* install [composer](https://getcomposer.org/doc/00-intro.md)
* add the following file to the root of your project:
    
    //composer.json
    {
        "require": {
            "Zack/FluentDBAL": "dev-master"
        }
    }

* run the 'composer install' command (it has several possible syntaxes which may
change according to your os. Make sure which one suites you. Plenty of composer
questions out there).

Usage (see tests/test.php):
* add `require 'vendor/autoload.php';` to your script
* create a new QueryBuilder: `$query = new QueryBuilder('db host','db name','user name','password');`
* start building your query:
    
    $query->select('*')
            ->from('persons')
            ->where((new WhereBuilder())
                    ->column('id')
                    ->equalsValue(2)
                    ->appendOr()
                    ->column('name')
                    ->equalsValue('John Doe')
    );
* send your query and fetch the results: `$results = $query->send();` (usses `PDO::FETCH_ASSOC`)

To install a working example:
* import tests/test.sql to a MySQL server (will create a small test database)
* request tests/test.php from your server (http://yourdomain.ext/vendor/zack/fluentdbal/Zack/tests/test.php)