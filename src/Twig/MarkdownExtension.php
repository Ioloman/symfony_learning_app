<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MarkdownExtension extends AbstractExtension
{
    private $parser;

    public function __construct(MarkdownHelper $parser)
    {
        $this->parser = $parser;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('markdown_filter', [$this, 'filterMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function filterMarkdown($value)
    {
        return $this->parser->parse($value);
    }
}
