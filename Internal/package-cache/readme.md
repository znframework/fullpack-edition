<h2>ZN Framework Cache Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-cache
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/veri-saklama-kutuphaneleri/on-bellekleme-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

Cache::insert('example', 'Example Data');

echo Cache::select('example');
```
