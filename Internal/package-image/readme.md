<h2>ZN Framework Image Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-image
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/resim-isleme-kutuphaneleri/resim-isleme-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

$thumbPath = Thumb::path('images/wallpaper.jpg')
                  ->quality(80)
                  ->crop(100, 200)
                  ->resize(300, 200)
                  ->create();

echo Html::image($thumbPath);
```