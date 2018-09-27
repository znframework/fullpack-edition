<h2>ZN Framework Hypertext Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-hypertext
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/gorunum-nesneleri/form-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

echo Form::where('id', 1)->process('update')->open('users');
echo Form::postback()->validate('required', 'xss')->text('name');
echo Form::postback()->validate('email', 'required')->text('email');
echo Form::select('gender', [1 => 'Male', 2 => 'Female']);
echo Form::validate('required')->textarea('about');
echo Form::submit('submit', 'Submit');
echo Form::close();
echo Form::validateErrorMessage();
```
