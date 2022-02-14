# DB-php
Free Functions To Connect To The Database ( Mysql ) For Php Programmers


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

# SQL LIKE Examples

```sql
SELECT * FROM Customers
WHERE CustomerName LIKE 'a%';
```


PHP 


```php
select('*','Customers',null,'and CustomerName LIKE "a%"');
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
