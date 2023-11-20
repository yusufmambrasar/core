# CORE

Hierarchical model–view–controller (HMVC) is a software architectural pattern, a variation of model–view–controller (MVC) similar to presentation–abstraction–control (PAC), that was published in 2000 in an article in JavaWorld Magazine, the authors apparently unaware of PAC, which was published 13 years earlier.

The controller has some oversight in that it selects first the model and then the view, realizing an approval mechanism by the controller. The model prevents the view from accessing the data source directly.

## INSTALL

```
git clone https://github.com/yusufmambrasar/core.git
```

## SETUP

### .htaccess ###

Create .htaccess.

```
\.htaccess
```

```
# Remove the question mark from the request but maintain the query string
Options All -Indexes
RewriteEngine On

# Uncomment the following line if your public folder isn't the web server's root
# RewriteBase /path/
RewriteRule (^|/)App(/|$) - [F]
RewriteRule (^|/)Core(/|$) - [F]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php?$1 [L,QSA]
```

### App Config ###

Change App Configs.

```
\App\Configs\App.php
```

```
<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

$config['app']['name'] = 'App name';
$config['app']['description'] = 'App description';
```

### Database Config ###

Change Database Configs.

```
\App\Configs\Database.php
```

```
<?php
defined('BASE') or header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

$config['host'] = 'localhost';
$config['username'] = 'user';
$config['password'] = 'password';
$config['db'] = 'database';
$config['port'] = 3306;
$config['socket'] = null;
$config['charset'] = 'utf8';
```

### APP DIRECTRORY STRUCTURE

APP
.
|-- ..
|-- Configs                         # Configs Directory 
    |-- App.php
    |-- Database.php
|-- Controllers                     # Global controllers
|-- Libraries                       # Global libraries
|-- Models                          # Global models
|-- Modules                         # Global modules
    |-- Land
        |-- Controllers             # Module Controller
            |-- Land.php
        |-- Views                   # Module Views
            |-- land.php
            |-- layout.php
|-- Views                           # Global views
|-- ..

### Cache Dir ###

Set Permission to 777 for Cache Directory;

```
\Caches\
```

# FOLLOW ME! #
Instagram: @yusufmambrasar
Twitter: @yusufmambrasar
Web: yusufmambrasar.id

# SUPPORT ME! #
Ko-Fi: https://ko-fi.com/yusufmambrasar

# Bussiness #
For Business Inquiries :
Email: helo@yusufmambrasar.id