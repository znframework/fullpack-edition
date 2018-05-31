<h2>ZN Framework Date & Time Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-datetime
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/tarih-ve-zaman-kutuphaneleri">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

Output::writeLine(Date::standart());
Output::writeLine(Time::current());
Output::writeLine(Date::calculate('{year}-{monthNumber}-{dayNumber}', '30 day'));
```
