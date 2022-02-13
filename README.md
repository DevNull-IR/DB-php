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


# SQL INNER JOIN Example

```sql 
SELECT Orders.OrderID, Customers.CustomerName FROM Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID;
```


PHP

```php
select('Orders.OrderID, Customers.CustomerName','Orders INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID');
```
