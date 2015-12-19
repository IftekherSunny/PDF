## Sun PDF

[![Total Downloads](https://poser.pugx.org/sun/pdf/downloads)](https://packagist.org/packages/sun/pdf) [![Latest Stable Version](https://poser.pugx.org/sun/pdf/v/stable)](https://packagist.org/packages/sun/pdf) [![Latest Unstable Version](https://poser.pugx.org/sun/pdf/v/unstable)](https://packagist.org/packages/sun/pdf) [![License](https://poser.pugx.org/sun/pdf/license)](https://packagist.org/packages/sun/pdf)
  
Sun PDF is the pdf generating tools for the serious PHP developers.

## Installation Process
 
Just copy PDF folder somewhere into your project directory. Then include Sun PDF autoload file.        
 
```php
require_once('/path/to/PDF/autoload.php');
```

Sun PDF is also available via Composer/Packagist.

```
composer require sun/pdf
```
 
## Basic Uses

#### Creating PDF

```php
$pdf = new Sun\PDF;
$pdf->download("<h1>Hello world</h1>");
```

#### Viewing PDF In The Browser

```php
$pdf = new Sun\PDF;
$pdf->stream("<h1>Hello world</h1>");
```

#### Getting PDF Output

```php
$pdf = new Sun\PDF;
$pdf->output("<h1>Hello world</h1>");
```


## Changing Configuration

In the Sun PDF I used PhantomJS. You can change all the configuration of the PhantomJS. To change PhantomJS configuration, you need to pass your own configuration file into Sun PDF constructor.

```php
$pdf = new Sun\PDF("/path/to/SunPdf.js");
```

Please, click [This Link](http://phantomjs.org/api/webpage/property/paper-size.html) to know about all the configuration of the PhantomJS.
 
If you do not wish to pass HTML codes as plain string, you can pass your HTML/PHP file into all the method of the Sun PDF. To use default template engine features you need to setup everything before use. All you have to do for setup is - 

```php
$pdf = new Sun\PDF(null, "/path/to/views");
```

I used Twig template engine, click [This Link](http://twig.sensiolabs.org/) to know about Twig template engine.


## Generating PDF With Template Engine

```php    
$pdf = new Sun\PDF(null, "/path/to/views");

// download pdf 
$pdf->download("/path/to/views/test.php", ['name' => 'Iftekher Sunny']);

// viewing pdf in the browser
$pdf->stream("/path/to/views/test.php", ['name' => 'Iftekher Sunny']);
```


## Integration In Laravel Framework

Add the ServiceProvider to the providers array in config/app.php

```php
Sun\Provider\PDFServiceProvider::class,
```

Add the facade to the aliases array in config/app.php

```php
'PDF'   =>  Sun\Facade\PDFFacade::class,
```

## Integration In Planet Framework

Add the provider in the config/provider.php file.

```php
Sun\Provider\PDFProvider::class,
```

Add the alien in the config/alien.php file.

```php
'PDF'   =>  Sun\Alien\PDFAlien::class,
```


## License
This package is licensed under the [MIT License](https://github.com/iftekhersunny/PDF/blob/master/LICENSE)
