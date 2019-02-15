# Вставить данные в таблицу

Теперь давайте вставим какие-то данные:

```
INSERT INTO customers (first_name, last_name, email, address, city, state, zipcode) VALUE('John', 'Doe', 'j.doe@gmail.com', '55 Main st', 'Boston', 'Massachusetts', '01221');
```

Теперь можем проверить что данные у нас успешно добавленны:

```
SELECT * FROM customers;
```

На выходе мы получим:

```
+----+------------+-----------+-----------------+------------+--------+---------------+---------+
| id | first_name | last_name | email           | address    | city   | state         | zipcode |
+----+------------+-----------+-----------------+------------+--------+---------------+---------+
|  1 | John       | Doe       | j.doe@gmail.com | 55 Main st | Boston | Massachusetts | 01221   |
+----+------------+-----------+-----------------+------------+--------+---------------+---------+
```

Также можно вставлять несколько строк данных разделяя их запятыми:

```
INSERT INTO customers (first_name, last_name, email, address, city, state, zipcode) VALUES
('Mike', 'Smith', 'm.smith@gmail.com', '22 Birch lane', 'Amesbury', 'Massachusetts', '01193'),
('Kathy', 'Morris', 'k.morris@gmail.com', '40 Williow st', 'Haverhill', 'Massachusetts', '01816'),
('Steven', 'Samson', 's.samson@gmail.com', '12 Gils Rd', 'Exeter', 'New Hampshire', '01283'),
('Lilian', 'Davidson', 'l.davidson@gmail.com', '7 Whittier st', 'Brooklyn', 'New York', '34883'),
('Derek', 'Williams', 'd.williams@gmail.com', '445 Madison ct', 'Yorkers', 'New York', '34993');
```