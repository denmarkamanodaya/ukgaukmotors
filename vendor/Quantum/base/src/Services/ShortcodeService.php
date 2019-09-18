<?php
namespace Quantum\base\Services;

use Quantum\base\Models\Shortcodes;
use Thunder\Shortcode\HandlerContainer\HandlerContainer;
use Thunder\Shortcode\Parser\RegexParser;
use Thunder\Shortcode\Processor\Processor;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;


class ShortcodeService
{
    /** @var HandlerContainer */
    private $handlers;
    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->handlers = new HandlerContainer();
    }

    public function registerAll()
    {
        $savedShortcodes = $this->getSaveShortcodes();

        if($savedShortcodes)
        {
            foreach ($savedShortcodes as $shortcode)
            {
                $this->prepareToRegister($shortcode);
            }
        }
    }

    private function getSaveShortcodes()
    {
        $savedShortcodes = [];
        try {
            $savedShortcodes = \Cache::rememberForever('shortcodes', function () {
                return Shortcodes::all();
            });
        } catch (\Exception $e) {
        }
        return $savedShortcodes;
    }

    private function prepareToRegister($shortcode)
    {
        if($shortcode->system == 1)
        {
            $this->register($shortcode->name, $shortcode->callback);
        } else {
            //basic TODO expand to full dynamic usage
            $this->register($shortcode->name, function(ShortcodeInterface $s) use ($shortcode) {
                return $shortcode->callback;
            });
        }
    }

    public function clearCache()
    {
        \Cache::forget('shortcodes');
        \Cache::forget('shortModal');
    }
    /**
     * Get all shortcodes.
     *
     * @return array
     */
    public function all()
    {
        return $this->handlers->getNames();
    }
    /**
     * Register new shortcode.
     *
     * @param string $name
     * @param mixed  $callback
     */
    public function register($name, $callback, $area = 'all')
    {
        try {
            $this->handlers->add($name, $callback);
        } catch (\Exception $e) {
        }
    }
    /**
     * Unregister the specified shortcode by given name.
     *
     * @param string $name
     */
    public function unregister($name)
    {
        if ($this->exists($name)) {
            $this->handlers->remove($name);
        }
        return $this;
    }
    /**
     * Unregister all shortcodes.
     *
     * @return self
     */
    public function destroy()
    {
        $this->handlers = new HandlerContainer();
        return $this;
    }
    /**
     * Strip any shortcodes.
     *
     * @param string $content
     *
     * @return string
     */
    public function strip($content)
    {
        $handlers = new HandlerContainer();
        $handlers->setDefault(function(ShortcodeInterface $s) { return $s->getContent(); });
        $processor = new Processor(new RegexParser(), $handlers);
        return $processor->process($content);
    }
    /**
     * Get count from all shortcodes.
     *
     * @return int
     */
    public function count()
    {
        return count($this->handlers->getNames());
    }
    /**
     * Return true is the given name exist in shortcodes array.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name)
    {
        return $this->handlers->has($name);
    }
    /**
     * Return true is the given content contain the given name shortcode.
     *
     * @param string $content
     * @param string $name
     *
     * @return bool
     */
    public function contains($content, $name)
    {
        $hasShortcode = false;
        $handlers = new HandlerContainer();
        $handlers->setDefault(function(ShortcodeInterface $s) use($name, &$hasShortcode) {
            if($s->getName() === $name) {
                $hasShortcode = true;
            }
        });
        $processor = new Processor(new RegexParser(), $handlers);
        $processor->process($content);
        return $hasShortcode;
    }
    /**
     * Parse content and replace parts of it using registered handlers
     *
     * @param $content
     *
     * @return string
     */
    public function parse($content)
    {
        //$this->registerAll();
        $processor = new Processor(new RegexParser(), $this->handlers);
        return $processor->process($content);
    }
    
    public function helpText()
    {
        $shortcodeHelp = Shortcodes::orderBy('id', 'ASC')->get();
        return $shortcodeHelp->groupBy('type')->toArray();
    }
    
    public function showButton()
    {
        $button = '<button class="btn btn-primary btn-rounded btn-xs" data-target="#shortcode_help" data-toggle="modal" type="button">
                                        Shortcodes
                                        <i class="far fa-question position-right"></i>
                                    </button>';
        return $button;
    }
    
    public function showModal()
    {
        $shortModal = \Cache::rememberForever('shortModal', function() {
            $shortcodeHelp = Shortcodes::orderBy('id', 'ASC')->where('hidden', 0)->get();
            $shortcodeHelp = $shortcodeHelp->groupBy('type')->toArray();

            $view = \View::make('base::admin.Shortcodes.helpModal', compact('shortcodeHelp'));
            $shortModal = $view->render();
            return $shortModal;
        });


        return $shortModal;
    }

    public function createShortcode($request)
    {
        $shortcode = Shortcodes::create([
            'name' => $request->name,
            'callback' => $request->replace,
            'title' => $request->title,
            'description' => $request->description,
            'type' => 'Custom',
            'system' => 0
        ]);

        flash('Shortcode has been created.')->success();
        \Activitylogger::log('Admin - Created Shortcode : ['.$shortcode->name.']', $shortcode);
        return $shortcode;
    }

    public function updateShortcode($id, $request)
    {
        $shortcode = Shortcodes::where('id', $id)->where('system', 0)->firstOrfail();

        $shortcode->name = $request->name;
        $shortcode->callback = $request->replace;
        $shortcode->title = $request->title;
        $shortcode->description = $request->description;
        $shortcode->save();

        flash('Shortcode has been updated.')->success();
        \Activitylogger::log('Admin - Updated Shortcode : ['.$shortcode->name.']', $shortcode);
        return $shortcode;
    }
    
    public function deleteShortcode($id)
    {
        $shortcode = Shortcodes::where('id', $id)->where('system', 0)->firstOrFail();
        \Activitylogger::log('Admin - Deleted Shortcode : ['.$shortcode->name.']', $shortcode);
        $shortcode->delete();
        flash('Shortcode has been deleted')->success();
        return;
    }
}