<?php

namespace Quantum\base\Http\Controllers\Admin;


use Quantum\base\Services\DocumentationService;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Controllers\Controller;

class Documentation extends Controller
{
    /**
     * The documentation repository.
     *
     * @var Documentation
     */
    protected $docs;
    protected $default_version = '2.0';


    /**
     * Create a new controller instance.
     *
     * @param  Documentation  $docs
     * @return void
     */
    public function __construct(DocumentationService $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Show the root documentation page (/docs).
     *
     * @return Response
     */
    public function showRootPage()
    {
        return redirect('admin/docs/'.$this->default_version);
    }

    /**
     * Show a documentation page.
     *
     * @param  string $version
     * @param  string|null $page
     * @return Response
     */
    public function show($version, $page = null)
    {
        if (! $this->isVersion($version)) {
            return redirect('admin/docs/'.$this->default_version.'/'.$version, 301);
        }

        if (! defined('CURRENT_VERSION')) {
            define('CURRENT_VERSION', $version);
        }

        $sectionPage = $page ?: 'installation';
        $content = $this->docs->get($version, $sectionPage);

        if (is_null($content)) {
            abort(404);
        }

        $title = (new Crawler($content))->filterXPath('//h1');

        $section = '';

        if ($this->docs->sectionExists($version, $page)) {
            $section .= '/'.$page;
        } elseif (! is_null($page)) {
            return redirect('/admin/docs/'.$version);
        }

        $canonical = null;

        if ($this->docs->sectionExists($this->default_version, $sectionPage)) {
            $canonical = 'admin/docs/'.$this->default_version.'/'.$sectionPage;
        }

        return view('base::docs.docs', [
            'title' => count($title) ? $title->text() : null,
            'index' => $this->docs->getIndex($version),
            'content' => $content,
            'currentVersion' => $version,
            'versions' => $this->docs->getDocVersions(),
            'currentSection' => $section,
            'canonical' => $canonical,
        ]);
    }

    /**
     * Determine if the given URL segment is a valid version.
     *
     * @param  string  $version
     * @return bool
     */
    protected function isVersion($version)
    {
        return in_array($version, array_keys($this->docs->getDocVersions()));
    }
}