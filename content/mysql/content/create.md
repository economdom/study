# Создание таблицы

Создадим таблицы для этой БД:

```
CREATE TABLE customers(
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255),
    address VARCHAR(255),
    city VARCHAR(255),
    state VARCHAR(255),
    zipcode VARCHAR(255),
    PRIMARY KEY(id)
);
```

## Посмотреть структуру таблицы

Посмотрим на нашу таблицу:

```
SHOW TABLES;
DESCRIBE customers;
```

И мы должны увидеть примерно следующее:

```
+------------+--------------+------+-----+---------+----------------+
| Field      | Type         | Null | Key | Default | Extra          |
+------------+--------------+------+-----+---------+----------------+
| id         | int(11)      | NO   | PRI | NULL    | auto_increment |
| first_name | varchar(255) | YES  |     | NULL    |                |
| last_name  | varchar(255) | YES  |     | NULL    |                |
| email      | varchar(255) | YES  |     | NULL    |                |
| address    | varchar(255) | YES  |     | NULL    |                |
| city       | varchar(255) | YES  |     | NULL    |                |
| state      | varchar(255) | YES  |     | NULL    |                |
| zipcode    | varchar(255) | YES  |     | NULL    |                |
+------------+--------------+------+-----+---------+----------------+
```