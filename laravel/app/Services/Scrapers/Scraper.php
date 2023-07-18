<?php

namespace App\Services\Scrapers;

use \DOMXPath;
use \DOMDocument;
use \DOMNode;
use \DOMNodeList;

class Scraper
{
    private $html;
    private $doc;
    private $xpath;
    private $elements;

    public function __construct(string $html)
    {
        $this->html = $html;
        $this->doc = $this->createDomDocumentAndLoad($html);
        $this->xpath = new DOMXPath($this->doc);
    }

    protected function html(): ?string
    {
        return $this->html;
    }

    protected function doc(): ?DOMDocument
    {
        return $this->doc;
    }

    protected function xpath(): ?DOMXpath
    {
        return $this->xpath;
    }

    protected function isScrapable(): bool
    {
        return $this->xpath() ? true : false;
    }

    protected function scrape(string $xpathExpression, ?DOMNode $contextNode = null): Scraper
    {
        $this->elements = $this->xpath()->query($xpathExpression, $contextNode);

        return $this;
    }

    protected function all(): DOMNodeList
    {
        return $this->elements;
    }

    protected function first(): ?DOMNode
    {
        return $this->elements->item(0);
    }

    static public function createDomDocumentAndLoad(string $html): DOMDocument
    {
        $doc = new DOMDocument();

        libxml_use_internal_errors(true);

        $doc->loadHTML($html);

        libxml_use_internal_errors(false);

        return $doc;
    }
}
