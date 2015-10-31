<?php

namespace SitePoint\TemplatingEngine;

use SitePoint\TemplatingEngine\Exception\EngineException;
use SitePoint\TemplatingEngine\Exception\InvalidTemplateNameException;
use SitePoint\TemplatingEngine\Exception\TemplateNotFoundException;

/**
 * An interface for the templating engine.
 */
interface TemplatingEngineInterface
{
    /**
     * Render a template.
     *
     * @param string $name   The template name.
     * @param array  $params An array of parameters to pass to the template.
     *
     * @return TemplateResult The result from the template.
     *
     * @throws InvalidTemplateNameException If the template name is invalid.
     * @throws TemplateNotFoundException    If the template namespace does not exist.
     * @throws EngineException              If an error is encountered rendering the template.
     */
    public function render($name, array $params = []);

    /**
     * Check to see if a template exists.
     *
     * @param string $name The service name.
     *
     * @return bool True if the container has the service, false otherwise.
     *
     * @throws InvalidTemplateNameException If the template name is invalid.
     */
    public function exists($name);

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
    public function getTemplatePath($name);

    /**
     * Call a function that has been registered with the templating engine.
     *
     * @param string $name      The function name.
     * @param array  $arguments The arguments to supply to the function.
     *
     * @return mixed The function result.
     */
    public function callFunction($name, array $arguments = []);
}
