# DB-php
Free Functions To Connect To The Database ( Mysql ) For Php Programmers


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
