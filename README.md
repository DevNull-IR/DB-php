# DB-php
Free Functions To Connect To The Database ( Mysql ) For Php Programmers


# SQL LIKE Examples

```sql
SELECT * FROM Customers
WHERE CustomerName LIKE 'a%';
```

```php
select('*','Customers',null,'and CustomerName LIKE "a%"');
```


# MIN() Example

```sql
SELECT MIN(Price) AS SmallestPrice
FROM Products;
```
