<h2>ZN Framework Email Package</h2>
<p>
Follow the steps below for installation and use.
</p>

<h3>Installation</h3>
<p>
You only need to run the following code for the installation.
</p>

```
composer require znframework/package-email
```

<h3>Documentation</h3>
<p>
Click for <a href="https://docs.znframework.com/uzak-servisler/mail-kutuphanesi">documentation</a> of your library.
</p>

<h3>Example Usage</h3>
<p>
Basic level usage is shown below.
</p>

```php
<?php require 'vendor/autoload.php';

ZN\ZN::run();

# The default settings are in the ZN\Email\EmailDefaultConfiguration file. 
# You can make your settings in this file.
# The Config::set() method should be used if you need to configure settings externally.
Config::services('email', 
[
    'driver' => 'smtp',
    'smtp'   =>
    [
        'host'      => '',
        'user'      => '',
        'password'  => '',
        'port'      => 587,
        'keepAlive' => false,
        'timeout'   => 10,
        'encode'    => '',  # empty, tls, ssl
        'dsn'       => false,
        'auth'      => true
    ],
    'general' =>
    [
        'senderMail'    => '',                  # Default Sender E-mail Address.
        'senderName'    => '',                  # Default Sender Name.
        'priority'      => 3,                   # 1, 2, 3, 4, 5
        'charset'       => 'UTF-8',             # Charset Type
        'contentType'   => 'html',              # plain, html
        'multiPart'     => 'mixed',             # mixed, related, alternative
        'xMailer'       => 'ZN',
        'encoding'      => '8bit',              # 8bit, 7bit
        'mimeVersion'   => '1.0',               # MIME Version
        'mailPath'      => '/usr/sbin/sendmail' # Default Mail Path
    ]
]);

Email::from('from@example.com')
     ->to('to@example.com')
     ->send('This is Subject', 'This is message.');

Output::display(Email::error());
```
