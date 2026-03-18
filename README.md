# CI-App – Webshop Rendeléskezelő 

## 1. Környezet 
- XAMPP telepítve
- Apache és MySQL futtatva (a XAMPP Control Panel-ben)
- MySQL Admin alatt elérhető az adatbázis és a táblák 

## 2. Repo klónozása 
```bash
git clone https://github.com/YOUR-USERNAME/YOUR-REPOSITORY ci-app
```
```bash 
cd ci-app 
```
## 3. .env beállítások 
- Másold az .env fájlt, és állítsd be:
CI_ENVIRONMENT = development

database.default.hostname = localhost

database.default.database = ci_order_management

database.default.username = root

database.default.password =

database.default.DBDriver = MySQLi

## 4. Adatbázis létrehozása 
- Hozd létre a ci_order_management adatbázist
- Futtasd a migrációt:
```bash
  php spark migrate
```
Ez létrehozza az alábbi táblákat:
- orders
- order_items
- order_status_logs
- products

## 5. Alkalmazás futtatása 
```bash
php spark serve
``` 
- Böngészőben nyisd meg: http://localhost:8080/
