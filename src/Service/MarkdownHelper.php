<?php

namespace App\Service;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class MarkdownHelper
{
    private $markdownParser;
    private $cache;
    private $isDebug;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct (MarkdownParserInterface $parser, CacheInterface $cache, bool $isDebug, LoggerInterface $logger)
    {
        $this->markdownParser = $parser;
        $this->cache = $cache;
        $this->isDebug = $isDebug;
        $this->logger = $logger;
    }

    public function parse(string $text)
    {
        if ($this->isDebug) {
            return $this->markdownParser->transformMarkdown($text);
        } else {
            return $this->cache->get('markdown_'.md5($text), function () use ($text) {
                return $this->markdownParser->transformMarkdown($text);
            });
        }
    }
}