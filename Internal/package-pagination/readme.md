<h2>ZN Framework Pagination Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-pagination
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/gorunum-nesneleri/sayfalama-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

$persons = DB::limit(NULL, 1)->persons();

Output::display($persons->result());
Output::display($persons->pagination('index.php'));
```
