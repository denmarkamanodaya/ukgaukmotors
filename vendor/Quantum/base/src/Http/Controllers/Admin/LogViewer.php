<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : LogViewer.php
 **/

namespace Quantum\base\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Quantum\base\Services\LogViewerService;

class LogViewer extends Controller
{

    /**
     * @var LogViewerService
     */
    private $logViewerService;

    public function __construct(LogViewerService $logViewerService)
    {
        $this->logViewerService = $logViewerService;
    }

    public function index()
    {

        if (Request::input('l')) {
            $this->logViewerService->setFile(base64_decode(Request::input('l')));
        }

        if (Request::input('dl')) {
            return Response::download($this->logViewerService->pathToLogFile(base64_decode(Request::input('dl'))));
        } elseif (Request::has('del')) {
            File::delete($this->logViewerService->pathToLogFile(base64_decode(Request::input('del'))));
            return Redirect::to(Request::url());
        }

        $logs = $this->logViewerService->all();

        return View::make('base::admin.LogViewer.index', [
            'logs' => $logs,
            'files' => $this->logViewerService->getFiles(true),
            'current_file' => $this->logViewerService->getFileName()
        ]);
    }
}