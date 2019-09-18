<?php

namespace App\Http\Controllers\Admin;

use App\Services\FilemanagerService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Filemanager extends Controller
{

    /**
     * @var FilemanagerService
     */
    private $filemanagerService;

    public function __construct(FilemanagerService $filemanagerService)
    {
        $this->filemanagerService = $filemanagerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.Filemanager.index');
    }

    public function jsonitems()
    {
        return $this->filemanagerService->getItems();
    }

    public function getErrors()
    {
        $this->filemanagerService->getErrors();
    }

    public function getFolders()
    {
        $this->filemanagerService->getFolders();
    }

    public function upload(Requests\Admin\FileManagerUpload $request)
    {
        return $this->filemanagerService->uploadImage($request);
    }
}
