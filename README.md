Installation
===

Clone project
```
$ git clone git@github.com:lubarvv/yii2-comments-tree.git
```

Install composer dependencies
```
$ composer global require "fxp/composer-asset-plugin:~1.2.0"
```
```
$ composer install
```

Copy ```config/db-example.php``` to ```config/db.php``` and edit with your mysql access data

Exec ```./yii migrate```

Run PHP server
===

```
$ cd web
```

```
$ php -S localhost:8000
```