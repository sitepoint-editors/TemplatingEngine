# Templating Engine

<!--- [![Latest Stable Version](https://poser.pugx.org/sitepoint/templating-engine/v/stable)](https://packagist.org/packages/sitepoint/templating-engine)
[![Build Status](https://travis-ci.org/sitepoint/templating-engine.svg?branch=master)](https://travis-ci.org/sitepoint/templating-engine)
[![Coverage Status](https://coveralls.io/repos/sitepoint/TemplatingEngine/badge.svg?branch=master&service=github)](https://coveralls.io/github/sitepoint/TemplatingEngine?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/?branch=master)
[![Total Downloads](https://poser.pugx.org/sitepoint/templating-engine/downloads)](https://packagist.org/packages/sitepoint/templating-engine)[![License](https://poser.pugx.org/sitepoint/container/license)](https://packagist.org/packages/sitepoint/templating-engine) -->

A simple, easy to follow PHP templating engine. Designed to be forked, modified, extended and hacked.

## Usage

```php
<?php $this->parent('app::layout', ['title' => 'Blog Post: '.$title]); ?>

<?php $this->block('content', function () { ?>
    <h1><?=$this->escape($title);?></h1>

    <?php foreach($paragraphs as $paragraph): ?>
        <?=$this->escape($paragraph);?>
    <?php endforeach; ?>
<?php }) ?>
```

```php
<html>
    <head>
        <title><?=$this->escape($title);?></title>
    </head>
    <body>
        <?=$this->block('content');?>
    </body>
</html>
```


```php
use SitePoint\TemplatingEngine\TemplatingEngine;

$engine = new TemplatingEngine(['app' => '/path/to/app/templates']);

$params = [
    'title' => 'My Blog Post',
    'paragraphs' => [
        'My first paragraph.',
        'My second paragraph.',
    ],
];

echo $engine->render('app::post', $params);
```

## Change Log

This project maintains a [change log file](CHANGELOG.md)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.