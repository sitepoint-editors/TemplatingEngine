<?php

namespace SitePoint\TemplatingEngine;

use SitePoint\TemplatingEngine\Exception\EngineException;

/**
 * The context for a template being rendered.
 */
class TemplateContext
{
    /**
     * @var TemplatingEngine
     */
    private $engine;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $blocks;

    /**
     * @var string
     */
    private $parentTemplate;

    /**
     * @var array
     */
    private $parentParams;

    /**
     * Constructor for the template context.
     *
     * @param TemplatingEngine $engine   The templating engine.
     * @param string           $name     The template name.
     * @param array            $params   The template parameters.
     * @param array            $blocks   Child template blocks
     */
    public function __construct(TemplatingEngine $engine, $name, array $params = [], array $blocks = [])
    {
        $this->engine   = $engine;
        $this->name     = $name;
        $this->params   = $params;
        $this->blocks   = $blocks;

        // By default this template has no parent
        $this->parentTemplate = null;
        $this->parentParams   = $params;
    }

    /**
     * Invoke the template and return the generated content.
     *
     * @return TemplateResult The result of the template.
     *
     * @throws EngineException If an error is encountered rendering the template.
     */
    public function __invoke()
    {
        $content = $this->getOutput(function ($params) {
            $templatePath = $this->engine->getTemplatePath($this->name);
            extract($params);
            include $templatePath;
        });

        if (null !== $this->parentTemplate) {
            $parentContext = new self($this->engine, $this->parentTemplate, $this->parentParams, $this->blocks);
            return $parentContext();
        }

        return new TemplateResult($content, $this->blocks);
    }

    /**
     * Get output from callable
     *
     * @param callable $callback The callback to get the output from.
     *
     * @return string
     */
    private function getOutput(callable $callback)
    {
        ob_start();

        try {
            $callback($this->params);
        } finally {
            $output = ob_get_contents();
            ob_end_clean();
        }

        return $output;
    }

    /**
     * Define a parent template.
     *
     * @param string $template The name of the parent template.
     * @param array  $params   Parameters to add to the parent template context
     *
     * @throws EngineException If a parent template has already been defined.
     */
    public function parent($template, array $params = [])
    {
        if (null !== $this->parentTemplate) {
            throw new EngineException('A parent template has already been defined');
        }

        $this->parentTemplate = $template;
        $this->parentParams = array_merge($this->parentParams, $params);
    }

    /**
     * Insert a template.
     *
     * @param string $template The name of the template.
     * @param array  $params   Parameters to add to the template context
     */
    public function insert($template, array $params = [])
    {
        $context = new self($this->engine, $template, array_merge($this->params, $params), $this->blocks);
        $result = $context();

        $this->blocks = $result->getBlocks();

        echo $result->getContent();
    }

    /**
     * Render a block.
     *
     * @param string $name The name of the block.
     */
    public function block($name, callable $callback = null)
    {
        if (null !== $callback) {
            $this->blocks[$name] = $this->getOutput($callback);
        }

        if (!isset($this->blocks[$name])) {
            throw new EngineException('The '.$name.' block has not been defined');
        }

        echo $this->blocks[$name];
    }

    /**
     * Escape a string for safe output as HTML.
     *
     * @param string $raw The unescaped string.
     *
     * @return string The escaped HTML output.
     */
    public function escape($raw)
    {
        return htmlspecialchars($raw, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Delegate a method call to the templating engine to see if a function has
     * been defined.
     *
     * @param string $name      The method name being called.
     * @param array  $arguments The arguments provided to the method.
     *
     * @return mixed The function result.
     */
    public function __call($name, array $arguments)
    {
        return $this->engine->callFunction($name, $arguments);
    }
}
