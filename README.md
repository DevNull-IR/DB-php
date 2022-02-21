# DB-php
Free Functions To Connect To The Database ( Mysql ) For Php Programmers

**This Version : 2.0**

# connect to database

```php
connect('dbName','myuser','passworduser');
```

# SQL Select Examples

```sql
select * from db where id = 10 limit 3
```

PHP :

```php
$select = select('*','db',['id'=>10],'limit 3');
```
execute :

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

```sql 
select ** from db
```
PHP
```php
select('*','db');
```

# SQL LIKE Examples

```sql
SELECT * FROM Customers
WHERE CustomerName LIKE 'a%';
```


PHP 

This Update : 2.0
```php
like('*','Customers',[
    'CustomerName'=>'a%'
]);
```


# MIN() Example

```sql
SELECT MIN(Price) AS SmallestPrice
FROM Products;
```


PHP

```php
select('MIN(Price) AS SmallestPrice','Products');
```


# SQL INNER JOIN Example
**This section will be updated later**

```sql 
SELECT Orders.OrderID, Customers.CustomerName FROM Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID;
```


PHP

```php
select('Orders.OrderID, Customers.CustomerName','Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID');
```


# SQL insert Example
sql :

```sql
insert into table (one,tow,there) values ('one','tow','there')
```

php :
```php

insert('table',['one'=>'one','tow'=>'tow','there'=>'there']);

```
execute => 0 or 1
query `insert('table',['one'=>'one','tow'=>'tow','there'=>'there']);` :
```sql
insert into table (one,tow,there) values (?,?,?)
```
**The content is then filled with prepare and bindValue**

# SQL update Example

```sql
update tb set id = '12' where name = '14'
```
PHP :

```php
update('tb',['id'=>12],['name'=>14]);
```
execute : 0 or 1
If execute is equal to 0, it means that the update has not been done


# SQL Created New Table 
`table(string $table,$column);`
sql :

```sql
CREATE TABLE accounts (
        id int
        );
```
php :
```php
table('accounts',['id'=>'int');
```
 execute => 0 or 1
 
 # SQL Set unique column
 
 `unique(string $table,$column);`

SQL :

```sql

```

php:
```php

unique('accounts',['slug']);
```
