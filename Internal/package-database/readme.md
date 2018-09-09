<h2>ZN Framework Database Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-database
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/veritabani-kullanimi/veritabani-kutuphanesi-bolum-1">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

# The default settings are in the ZN\Database\DatabaseDefaultConfiguration file. 
# You can make your settings in this file.
# The Config::set() method should be used if you need to configure settings externally.
Config::database('database', 
[
    'driver'   => 'mysqli',
    'host'     => 'localhost', 
    'database' => 'test',
    'user'     => 'root',
    'password' => '',
    'prefix'   => ''
]);

$persons = DB::persons();

Output::display($persons->result());
```
