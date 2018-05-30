<h2>ZN Framework Validation Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-validation
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/gorunum-nesneleri/validasyon-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

use ZN\Request\Post;

Post::username('ExampleUser');
Post::password('1234');

Validation::rules('username', ['required', 'email'], 'Username:');
Validation::rules('password', ['required', 'minchar' => 8, 'maxhar' => 32], 'Username:');

Output::display(Validation::error());
```
