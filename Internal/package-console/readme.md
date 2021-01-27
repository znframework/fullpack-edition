<h2>ZN Framework Console Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-console
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/yerel-servisler/konsol-komutlari">documentation</a> of your library.
</p>

<h3>Usage Steps</h3>
<p>
<ul>
<li>Move to the root directory of the <code>zerocore</code> file in the package.</li>
<li>Change the name of the <code>zeroneed.php</code> file in the <code>zerocore</code> file to index.php.</li>
<li>Edit the contents of the  <code>index.php</code> file as follows.</li>
</ul>
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();
```
```bash
# After the steps above, you can use commands from the console.
php zerocore command-list
```