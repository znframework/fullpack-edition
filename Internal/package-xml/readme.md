<h2>ZN Framework XML Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-xml
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/veri-bicimlendirme-kutuphaneleri/xml-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

$data = XML::build
([  
    'name'  => 'media', 
    'attr'  => ['id' => 1], 
    'child' => 
    [ 
        ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo'], 
        [
            'name'  => 'music', 
            'attr'  => ['id' => 3], 
            'child' => 
            [ 
                ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo'], 
                ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo'], 
                ['name' => 'video', 'attr' => ['id' => 2], 'content' => 'Vidyo'] 
            ]
        ]
    ]
]);

Output::display($data);
```
```xml
<?xml version="1.0" encoding="UTF-8"?>
<media id="1">
	<video id="2">Vidyo</video>
	<music id="3">
		<video id="2">Vidyo</video>
		<video id="2">Vidyo</video>
		<video id="2">Vidyo</video>
	</music>
</media>
```