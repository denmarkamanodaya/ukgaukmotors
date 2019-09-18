<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : WikiScraperService.php
 **/

namespace App\Services;


use Illuminate\Support\Facades\Cache;

class WikiScraperService
{

    protected $baseUrl = 'https://en.wikipedia.org/w/api.php?format=php&action=parse&prop=text|images|wikitext&utf8=&page=';

    protected $prop = [];

    protected $xpath;


    public function getPageByTitle($title)
    {

        if (Cache::has('wikipage_'.$title)) {
            return Cache::get('wikipage_'.$title);
        }
        $page = file_get_contents( $this->baseUrl.$title );
        $page = unserialize($page);
        if(isset($page['error']))
        {
            $page['parse']['text']['*'] = $page['error']['info'];
        }
        Cache::put('wikipage_'.$title, $page, 20);
        return $page;

    }

    public function cleanWikiPage($page)
    {
        $dom = new \DOMDocument();
        $dom->strictErrorChecking = false;
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));
        $this->xpath = new \DOMXPath($dom);

        //$this->removeInfoBoxes();
        $this->removeContents();
        $this->removeLinks();
        $this->removeSup();
        $this->removeReferences();
        $this->removeBibliography();
        $this->removeExternalLinks();
        $this->removeNavigation();
        $this->removeEdit();
        $this->removeNamedSection('See_also');
        $this->removeComments();
        $this->removeContentMeta();
        $this->adjustFirstParagraph();

        $mock = new \DOMDocument();
        $body = $dom->getElementsByTagName('body')->item(0);
        foreach ($body->childNodes as $child){
            $mock->appendChild($mock->importNode($child, true));
        }

        $content = $mock->saveHTML();
        $content = $this->searchReplace('[citation needed]', '', $content);
        $content = $this->searchReplace('<b>', '', $content);
        $content = $this->searchReplace('</b>', '', $content);
        $content = $this->searchReplace('<p>&nbsp;</p>', '', $content);
        $content = $this->searchReplace('<p></p>', '', $content);
        $content = preg_replace("/[\r\n]+/", "\n", $content);
        return $content;
    }

    private function removeInfoBoxes()
    {
        //remove info boxes
        foreach($this->xpath->query('//table[@class=\'infobox hproduct\']') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
    }

    private function removeContents()
    {
        $nlist = $this->xpath->query("//div[@id='toc']");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }
    }

    private function removeLinks()
    {
        //remove all wiki links but leave text
        foreach ($this->xpath->query('//a[starts-with(@href, "/wiki")]') as $link) {
            // Move all link tag content to its parent node just before it.
            while($link->hasChildNodes()) {
                $child = $link->removeChild($link->firstChild);
                $link->parentNode->insertBefore($child, $link);
            }
            // Remove the link tag.
            $link->parentNode->removeChild($link);
        }
        foreach ($this->xpath->query('//a[starts-with(@href, "/w/index.php")]') as $link) {
            // Move all link tag content to its parent node just before it.
            while($link->hasChildNodes()) {
                $child = $link->removeChild($link->firstChild);
                $link->parentNode->insertBefore($child, $link);
            }
            // Remove the link tag.
            $link->parentNode->removeChild($link);
        }
    }

    private function removeSup()
    {
        //remove all sup
        foreach($this->xpath->query('//sup[@class=\'reference\']') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
    }

    private function removeReferences()
    {
        $nlist = $this->xpath->query("//span[@id='References']/..");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }
        $nlist = $this->xpath->query("//div[contains(@class, 'reflist')]");
        if($nlist->length)
        {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }
    }

    private function removeBibliography()
    {
        $nlist = $this->xpath->query("//span[@id='Bibliography']/../following-sibling::ul");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        $nlist = $this->xpath->query("//span[@id='Bibliography']/..");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }
    }

    private function removeExternalLinks()
    {
        $nlist = $this->xpath->query("//span[@id='External_links']/../following-sibling::table");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        $nlist = $this->xpath->query("//span[@id='External_links']/../following-sibling::ul");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        $nlist = $this->xpath->query("//span[@id='External_links']/..");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        foreach($this->xpath->query('//table[contains(@class, \'navbox collapsible autocollapse\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
        foreach($this->xpath->query('//table[contains(@class, \'navbox collapsible collapsed\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }

    }

    private function removeNavigation()
    {

        foreach($this->xpath->query('//div[@role=\'navigation\']') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }

    }

    private function removeEdit()
    {

        foreach($this->xpath->query('//span[@class=\'mw-editsection\']') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }

    }

    private function removeNamedSection($name)
    {
        $nlist = $this->xpath->query("//span[@id='$name']/../following-sibling::table");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        $nlist = $this->xpath->query("//span[@id='$name']/../following-sibling::ul");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

        $nlist = $this->xpath->query("//span[@id='$name']/..");
        if($nlist->length) {
            $node = $nlist->item(0);
            $node->parentNode->removeChild($node);
        }

    }

    private function removeComments()
    {

        foreach($this->xpath->query('//comment()') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }

    }

    private function adjustFirstParagraph()
    {
        if ($node = $this->xpath->query('//p[1]'))
        {

            $firstPara = $node->item(0)->nodeValue;

            $firstParts = explode('.', $firstPara);
            $newpara = '';
            foreach ($firstParts as $key => $part)
            {
                if($key == 0)
                {
                    $newpara = '<h2>'.$part.'.</h2>';
                } else {
                    $newpara .= $part;
                }

            }

            $node->item(0)->nodeValue = $newpara;
        }
    }

    private function removeContentMeta()
    {
        foreach($this->xpath->query('//table[contains(@class, \'plainlinks metadata ambox ambox-content\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
        foreach($this->xpath->query('//span[contains(@class, \'unicode haudio\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
        foreach($this->xpath->query('//table[contains(@class, \'mbox-small plainlinks sistersitebox\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }
        foreach($this->xpath->query('//table[contains(@class, \'plainlinks metadata ambox mbox-small-left ambox-content\')]') as $e ) {
            // Delete this node
            $e->parentNode->removeChild($e);
        }

    }

    private function searchReplace($search, $replace,  $content)
    {
        $content = str_replace($search, $replace, $content);
        return $content;
    }


}