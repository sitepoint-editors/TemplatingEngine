<?php

namespace SitePoint\TemplatingEngine;

use SitePoint\TemplatingEngine\Exception\EngineException;
use SitePoint\TemplatingEngine\Exception\InvalidTemplateNameException;
use SitePoint\TemplatingEngine\Exception\TemplateNotFoundException;

/**
 * A very simple templating engine.
 */
class TemplatingEngine
{
    /**
     * @var array
     */
    private $namespaces;

    /**
     * @var array
     */
    private $functions;

    /**
     * @var string
     */
    private $extension;

    /**
     * Constructor for the engine.
     *
     * The key of the entries into the namespaces array should be the namespace
     * and the value should be the root directory path for templates in that
     * namespace.
     *
     * The key of the entries to the functions array should be the method name
     * to hook in the template context and the value should be a callable to
     * invoke when this method is called.
     *
     * @param array  $namespaces The template namespaces to register.
     * @param array  $functions  The functions to register.
     * @param string $extension  The file extension of the templates.
     */
    public function __construct(array $namespaces = [], array $functions = [], $extension = 'phtml')
    {
        $this->namespaces = $namespaces;
        $this->functions  = $functions;
        $this->extension  = $extension;
    }

    /**
     * {@inheritDoc}
     */
    public function render($name, array $params = [])
    {
        $context = new TemplateContext($this, $name, $params, []);
        return $context()->getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function exists($name)
    {
        try {
            $this->getTemplatePath($name);
        } catch (TemplateNotFoundException $exception) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplatePath($name)
    {
        if (1 !== preg_match_all('/([^:]+)::(.+)/', $name, $matches)) {
            throw new InvalidTemplateNameException('Templates must follow the namespace::template convention');
        }

        $namespace = $matches[1][0];
        $template  = $matches[2][0];

        if (!isset($this->namespaces[$namespace])) {
            throw new TemplateNotFoundException('The '.$namespace.' namespace has not been registered');
        }

        $templatePath  = rtrim($this->namespaces[$namespace], '/').'/';
        $templatePath .= ltrim($template, '/');
        $templatePath .= '.'.$this->extension;

        if (!file_exists($templatePath)) {
            throw new TemplateNotFoundException('There is no template at the path: '.$templatePath);
        }

        return $templatePath;
    }

    /**
     * {@inheritDoc}
     */
    public function callFunction($name, array $arguments = [])
    {
        if (!isset($this->functions[$name]) || !is_callable($this->functions[$name])) {
            throw new EngineException('The '.$name.' function does not exist or is not callable');
        }

        return call_user_func_array($this->functions[$name], $arguments);
    }
}
