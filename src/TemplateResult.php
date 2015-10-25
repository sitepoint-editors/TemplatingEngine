<?php

namespace SitePoint\TemplatingEngine;

/**
 * The result of a rendered template.
 */
class TemplateResult
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var array
     */
    private $blocks;

    /**
     * Constructor for the template result.
     *
     * @param string $content The template content.
     * @param array  $blocks  The template blocks.
     */
    public function __construct($content, array $blocks = [])
    {
        $this->content = $content;
        $this->blocks  = $blocks;
    }

    /**
     * Get the content of the result.
     *
     * @return string The content of the template result.
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the blocks of the result.
     *
     * @return string The blocks of the template result.
     */
    public function getBlocks()
    {
        return $this->blocks;
    }
}
