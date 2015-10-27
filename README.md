# ![SitePoint](https://avatars2.githubusercontent.com/u/15340853?v=3&s=40) Templating Engine

[![Latest Stable Version](https://poser.pugx.org/sitepoint/templating-engine/v/stable)](https://packagist.org/packages/sitepoint/templating-engine)
[![Build Status](https://travis-ci.org/sitepoint/TemplatingEngine.svg?branch=master)](https://travis-ci.org/sitepoint/TemplatingEngine)
[![Code Coverage](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sitepoint/TemplatingEngine/?branch=master)
[![Code Climate](https://codeclimate.com/repos/562f5ab26956804f290021b2/badges/5d9d0ff31e26e0ba3c9d/gpa.svg)](https://codeclimate.com/repos/562f5ab26956804f290021b2/feed)
[![Total Downloads](https://poser.pugx.org/sitepoint/templating-engine/downloads)](https://packagist.org/packages/sitepoint/templating-engine)
[![License](https://poser.pugx.org/sitepoint/templating-engine/license)](https://packagist.org/packages/sitepoint/templating-engine)

A simple, easy to follow PHP templating engine. Designed to be forked, modified, extended and hacked.

## Example Usage

This templating engine supports inheritance through blocks. Child templates declare blocks which can be overridden, extended and displayed by parent templates.

Child templates can declare a single parent template at any point using the `parent()` method which also provides the opportunity to modify the variables that are in scope.

All templates must follow the `namespace::path/to/template` format.

```php
<?php $this->parent('app::layout', ['title' => 'Blog Post: '.$title]); ?>

<?php $this->block('content', function () { ?>
    <article>
        <header>
            <h1><?=$this->escape($this->caps($title));?></h1>
        </header>
        <main>
            <?php foreach($paragraphs as $paragraph): ?>
                <p>
                    <?=$this->escape($paragraph);?>
                </p>
            <?php endforeach; ?>
        </main>
    </article>
<?php }); ?>
```

```html
<html>
    <head>
        <title><?=$this->escape($title);?></title>
    </head>
    <body>
        <?=$this->block('content');?>
    </body>
</html>
```

Namespaces and function callbacks are registered with the templating engine when it is constructed. Function callbacks are available as methods within the template context and must be `callable`.

The default template extension is `phtml`, but this can be overridden.

```php
use SitePoint\TemplatingEngine\TemplatingEngine;

$engine = new TemplatingEngine(
    ['app'  => '/path/to/templates/app'], // The namespaces to register
    ['caps' => 'strtoupper'],             // Function callbacks to register inside the template context
    'phtml'                               // The extension of the templates (defaults to phtml)
);

$params = [
    'title' => 'My Blog Post',
    'paragraphs' => [
        'My first paragraph.',
        'My second paragraph.',
    ],
];

echo $engine->render('app::post', $params);
```

## Template Context Methods

The following methods are available by default within the template context.

```php
/**
 * Define a parent template.
 *
 * @param string $template The name of the parent template.
 * @param array  $params   Parameters to add to the parent template context
 *
 * @throws EngineException If a parent template has already been defined.
 */
public function parent($template, array $params = []);
```

```php
/**
 * Insert a template.
 *
 * @param string $template The name of the template.
 * @param array  $params   Parameters to add to the template context
 */
public function insert($template, array $params = []);
```

```php
/**
 * Render a block.
 *
 * @param string $name The name of the block.
 */
public function block($name, callable $callback = null);
```

```php
/**
 * Escape a string for safe output as HTML.
 *
 * @param string $raw The unescaped string.
 *
 * @return string The escaped HTML output.
 */
public function escape($raw);
```

## Authors

- [Andrew Carter](https://twitter.com/AndrewCarterUK)

## Change Log

This project maintains a [change log file](CHANGELOG.md)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
