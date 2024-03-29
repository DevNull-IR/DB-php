# DB-php

Free Functions To Connect To The Database ( Mysql ) For Php Programmers

**This Version : 5.0**

# connect to database

`connect(string $dbname,string $username_db,string $password_db,string $host = 'localhost');`

This Function :

```php
$cn = connect('dbName','myuser','passworduser');
```

**class**

```php
$db = new db(string $dbname,string $username_db,string $password_db,string $host = 'localhost');
```

- Php :

```php

        $Option = [
        PDO::ATTR_PERSISTENT => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',
        PDO::ATTR_EMULATE_PREPARES => false
    ];
        $pdo = new PDO("mysql:host=localhost;dbname=dmn_nm;charset=utf8", "myAdmin" , 'abcdefgh1234' , $Option );
```

# SQL Select Examples

- SQL

```sql
select *
from db
where id = 10
limit 3
```

- PHP :

```php
$select = select($cn, '*','db',['id'=>10],'limit 3');
```

**OR**

```sql
select *
from db
where id = 10 coin >= 15
```

- PHP :

```php
select($cn, '*','db',['id'=>10,['coin','>=',15]]);
```

- execute :

![](https://raw.githubusercontent.com/DevNull-IR/DB-php/main/src-document/Capture.PNG)

```array
array (size=2)
  'count' => int 3
  'fetchAll' => 
    array (size=3)
      0 => 
        array (size=7)
          'id' => int 17
          'step' => string 'support' (length=7)
          'chat_id' => int 1212
          'Cash' => null
          'vip' => int 0
          'grade' => int 0
          'Download' => int 0
      1 => 
        array (size=7)
          'id' => int 18
          'step' => string 'NewUser' (length=7)
          'chat_id' => int 1016239559
          'Cash' => null
          'vip' => int 0
          'grade' => int 0
          'Download' => int 0
      2 => 
        array (size=7)
          'id' => int 19
          'step' => string 'NewUser' (length=7)
          'chat_id' => int -663757927
          'Cash' => null
          'vip' => int 0
          'grade' => int 0
          'Download' => int 0
```

- SQL

```sql 
select * from db
```

- PHP

```php
select($cn, '*','db');
```

# SQL LIKE Examples

- SQL

```sql
SELECT *
FROM Customers
WHERE CustomerName LIKE 'a%';
```

- PHP

This Update : 2.0

```php
like($cn, '*','Customers',[
    'CustomerName'=>'a%'
]);
```

OR

- SQL:

```sql 
select * from db where column LIKE 'a%' and id <= 15
```

- PHP:

```php
like($cn, '*','db',[
       'column'=>"a%"
    ],
    [
        [
            'id','<=',15
        ]
    ]);
```

OR

- SQL:

```sql 
select * from db where column LIKE 'a%' and id = 15
```

- PHP

```php
like($cn, '*','db',[
       'column'=>"a%"
    ],
    [
        'id'=>15
    ]);
```

# MIN() Example

- SQL

```sql
SELECT MIN(Price) AS SmallestPrice
FROM Products;
```

- PHP

```php
select($cn, 'MIN(Price) AS SmallestPrice','Products');
```

# SQL INNER JOIN Example

**This section will be updated later**

- SQL

```sql 
SELECT Orders.OrderID, Customers.CustomerName FROM Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID;
```

- PHP

```php
select($cn, 'Orders.OrderID, Customers.CustomerName','Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID');
```

# SQL insert Example

- SQL :

```sql
insert into table (one, tow, there)
values ('one', 'tow', 'there')
```

- php :

```php

insert($cn, 'table',['one'=>'one','tow'=>'tow','there'=>'there']);

```

- **execute => false or true**
  query `insert('table',['one'=>'one','tow'=>'tow','there'=>'there']);` :

```sql
insert into table (one, tow, there)
values (?, ?, ?)
```

**The content is then filled with prepare and bindValue**

# SQL delete data

`deleted(string $table ,$where = "None",string $other = null);`

- SQL:

```sql
DELETE
FROM one
WHERE p = 12
```

- PHP:

```php
deleted($cn, 'one',['p'=>12]);
```

# SQL update Example

- SQL:

```sql
update tb
set id = '12'
where name = '14'
```

- PHP :

```php
update($cn, 'tb',['id'=>12],['name'=>14]);
```

execute : false or true
__If execute is equal to false, it means that the update has not been done__

# SQL Created New Table

`table(string $table,$column);`

- SQL:

```sql
CREATE TABLE accounts
(
    id int
);
```

- php :

```php
table($cn, 'accounts',['id'=>'int']);
```

execute => false or true

# SQL Set unique column

`unique(string $table,$column);`

- SQL :

```sql
ALTER TABLE articles
    ADD UNIQUE (slug)
```

- php:

```php

unique($cn, 'articles',['slug']);
```

# SQL Set  primary Key

`primary(string $table,$column);`

- SQL:

```sql
ALTER TABLE accounts
    ADD PRIMARY KEY (token);
```

- php :

```php
primary($cn, 'accounts',['token']);
```

# Drop Table & column Exsample

`drop($table,array $columns = []);`

- SQL Drop column:

```sql
ALTER TABLE table
    DROP COLUMN column;
```

- PHP Drop column:

```php
drop($cn, 'table',['column']);
```

+ SQL Drop Table

```sql
DROP TABLE a,b;
```

- PHP Drop Table :

```php
drop($cn, ['a','b']);
```

# Set AUTO_INCREMENT

`autoIncrement(string $table, string $column);`

Sql :
`ALTER TABLE `TableNabe` CHANGE `columnName` `columnName` BIGINT NOT NULL AUTO_INCREMENT;`

PHP:

```php
autoIncrement($cn, 'TableNabe','columnName');
```

PHP Classes:

```php
$db->autoIncrement('TableNabe','columnName');
```

# Update version 3.5

## Where

- select
- update
- like
- delete

## Description

- If you enter another presentation in the presentation, you can (mandatory) send three values to the second
  presentation
- First value (first parameter): The name of the column in the table specified in the connect function
- Second value (second parameter): Enter the type Operators to check
- Third value (third parameter): value to check 
