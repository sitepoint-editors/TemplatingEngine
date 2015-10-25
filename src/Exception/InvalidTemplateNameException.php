<?php

namespace SitePoint\TemplatingEngine\Exception;

/**
 * The InvalidTemplateNameException is thrown when the engine is asked to
 * locate a template that does not follow the 'namespace::template' convention.
 */
class InvalidTemplateNameException extends \Exception {}
