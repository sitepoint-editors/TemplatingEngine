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
     * @var string
     */
    private $extension;

    /**
     * Constructor for the engine.
     *
     * The key of entries into the namespaces array should be the namespace
     * and the value should be the root directory path for templates in that
     * namespace.
     *
     * @param array  $namespaces The template namespaces.
     * @param string $extension  The file extension of the templates.
     */
    public function __construct(array $namespaces = [], $extension = 'phpt')
    {
        $this->namespaces = $namespaces;
        $this->extension  = $extension;
    }

    /**
     * Convert a template name to a file path.
     *
     * @param string $name The template name.
     *
     * @return string The file path of the template.
     *
     * @throws InvalidTemplateNameException If the template name is invalid.
     * @throws TemplateNotFoundException    If the template namespace does not exist.
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
     * Check to see if a template exists.
     *
     * @param string $name The service name.
     *
     * @return bool True if the container has the service, false otherwise.
     *
     * @throws InvalidTemplateNameException If the template name is invalid.
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
     * Render a template.
     *
     * @param string $name   The template name.
     * @param array  $params An array of parameters to pass to the template.
     *
     * @return string The rendered template content.
     *
     * @throws InvalidTemplateNameException If the template name is invalid.
     * @throws TemplateNotFoundException    If the template namespace does not exist.
     * @throws EngineException              If an error is encountered rendering the template.
     */
    public function render($name, array $params = [])
    {
        $context = new TemplateContext($this, $name, $params, []);
        $result = $context();

        return $result->getContent();
    }
}