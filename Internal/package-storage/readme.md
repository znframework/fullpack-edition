<h2>ZN Framework Storage Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-storage
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/veri-saklama-kutuphaneleri/oturum-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

Session::example('Example Data');

echo Session::example();
```
```php
Cookie::example('Example Data');

echo Cookie::example();
```
