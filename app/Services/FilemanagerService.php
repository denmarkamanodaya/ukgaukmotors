<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : FilemanagerService.php
 **/

namespace App\Services;


use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;

class FilemanagerService
{
    var $user;
    var $basepath;
    var $baseurl;
    var $working_folder;

    public function __construct()
    {

    }
    public function setFolders()
    {
        $this->user = \Auth::user();
        if(!$this->user) abort(404);
        $this->working_folder = date('m-y');
        $this->basepath = config('main.public_path').'/media/'.$this->working_folder.'/photos/'.$this->user->username;
        $this->baseurl = url('/media/'.$this->working_folder.'/photos/'.$this->user->username);
    }

    public function getItems()
    {
        $path = $this->basepath.'/'.$this->working_folder;
        $this->pathExists($path);
        $sort_type = request('sort_type');
        $files = $this->sortFilesAndDirectories($this->getFilesWithInfo($path), $sort_type);

        $view = \View::make('admin.Filemanager.grid-view', ['items' => $files]);
        $contents = $view->render();
        $out = [
            'html' => $contents
        ];
        return json_encode($out, true);


    }

    private function sortFilesAndDirectories($arr_items, $sort_type)
    {
        if ($sort_type == 'time') {
            $key_to_sort = 'updated';
        } elseif ($sort_type == 'alphabetic') {
            $key_to_sort = 'name';
        } else {
            $key_to_sort = 'updated';
        }

        if($key_to_sort == 'updated')
        {
            uasort($arr_items, function ($a, $b) use ($key_to_sort) {
                return strcmp($b->{$key_to_sort}, $a->{$key_to_sort});
            });
        } else {
            uasort($arr_items, function ($a, $b) use ($key_to_sort) {
                return strcmp($a->{$key_to_sort}, $b->{$key_to_sort});
            });
        }

        return $arr_items;
    }

    private function pathExists($path)
    {
        if (!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
            File::makeDirectory($path.'/thumbs', $mode = 0777, true, true);
        }
    }
    private function getFilesWithInfo($path)
    {

        $files = array_map(function ($file) {
            return $this->objectPresenter($file);
        }, File::files($path));
        return $files;
    }

    private function objectPresenter($item)
    {

        $item_name = $this->getName($item);
        $is_file = is_file($item);

        if (!$is_file) {
            $file_type = trans('laravel-filemanager::lfm.type-folder');
            $icon = 'fa-folder-o';
            $thumb_url = asset('vendor/laravel-filemanager/img/folder.png');
        } elseif ($this->fileIsImage($item)) {
            $file_type = $this->getFileType($item);
            $icon = 'fa-image';

            $thumb_path = $this->getThumbPath($item_name);
            $file_path = $this->getCurrentPath($item_name);
            if ($this->imageShouldNotHaveThumb($file_path)) {
                $thumb_url = $this->getFileUrl($item_name) . '?timestamp=' . filemtime($file_path);
            } elseif (File::exists($thumb_path)) {
                $thumb_url = $this->getThumbUrl($item_name) . '?timestamp=' . filemtime($thumb_path);
            } else {
                $thumb_url = $this->getFileUrl($item_name) . '?timestamp=' . filemtime($file_path);
            }
        } else {
            $extension = strtolower(File::extension($item_name));
            $file_type = config('lfm.file_type_array.' . $extension) ?: 'File';
            $icon = config('lfm.file_icon_array.' . $extension) ?: 'fa-file';
            $thumb_url = null;
        }

        return (object)[
            'name'    => $item_name,
            'url'     => $is_file ? $this->getFileUrl($item_name) : '',
            'size'    => $is_file ? $this->humanFilesize(File::size($item)) : '',
            'updated' => filemtime($item),
            'path'    => $is_file ? '' : $this->getInternalPath($item),
            'time'    => date("Y-m-d h:m", filemtime($item)),
            'type'    => $file_type,
            'icon'    => $icon,
            'thumb'   => $thumb_url,
            'is_file' => $is_file
        ];
    }

    public function getName($file)
    {
        $arr_dir = explode('/', $file);
        $file_name = end($arr_dir);

        return $file_name;
    }

    public function fileIsImage($file)
    {
        $mime_type = $this->getFileType($file);

        return starts_with($mime_type, 'image');
    }

    public function getFileType($file)
    {
        if ($file instanceof UploadedFile) {
            $mime_type = $file->getMimeType();
        } else {
            $mime_type = File::mimeType($file);
        }

        return $mime_type;
    }

    public function getThumbPath($item_name)
    {
        $path = $this->basepath.'/'.$this->working_folder.'/thumbs/'.$item_name;
        return $path;
    }

    public function getCurrentPath($item_name)
    {
        $path = $this->basepath.'/'.$this->working_folder.'/'.$item_name;
        return $path;
    }

    public function getInternalPath($item)
    {
        if(starts_with($item, $this->basepath)) return $item;
        $path = $this->basepath.'/'.$this->working_folder.'/'.$item;
        return $path;
    }

    public function getFileUrl($item_name)
    {
        $path = url('/photos/'.$this->user->username.'/'.$this->working_folder.'/'.$item_name);
        return $path;
    }

    public function getThumbUrl($item_name)
    {
        $path = url('/photos/'.$this->user->username.'/'.$this->working_folder.'/thumbs/'.$item_name);
        return $path;
    }

    public function imageShouldNotHaveThumb($file)
    {
        $mine_type = $this->getFileType($file);
        $noThumbType = ['image/gif', 'image/svg+xml'];

        return in_array($mine_type, $noThumbType);
    }

    public function humanFilesize($bytes, $decimals = 2)
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }
    public function getErrors()
    {
        $arr_errors = [];

        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            array_push($arr_errors, trans('laravel-filemanager::lfm.message-extension_not_found'));
        }

        $type_key = $this->currentLfmType();
        $mine_config = 'lfm.valid_' . $type_key . '_mimetypes';
        $config_error = null;

        if (!is_array(config($mine_config))) {
            array_push($arr_errors, 'Config : ' . $mine_config . ' is not a valid array.');
        }

        return json_encode($arr_errors);
    }

    public function isProcessingImages()
    {
        return lcfirst(str_singular(request('type'))) === 'image';
    }

    public function isProcessingFiles()
    {
        return !$this->isProcessingImages();
    }

    public function currentLfmType()
    {
        $file_type = 'file';
        if ($this->isProcessingImages()) {
            $file_type = 'image';
        }

        return $file_type;
    }

    public function getFolders()
    {
        $folder_types = [];
        $root_folders = [];

        $folder_types['user'] = 'root';


        foreach ($folder_types as $folder_type => $lang_key) {
            $root_folder_path = $this->getRootFolderPath($folder_type);

            $children = $this->getDirectories($root_folder_path);
            usort($children, function ($a, $b) {
                return strcmp($a->name, $b->name);
            });

            array_push($root_folders, (object)[
                'name' => trans('laravel-filemanager::lfm.title-' . $lang_key),
                'path' => $this->getInternalPath($root_folder_path),
                'children' => $children,
                'has_next' => !($lang_key == end($folder_types))
            ]);
        }
dd($root_folders);
        return view('laravel-filemanager::tree')
            ->with(compact('root_folders'));
    }


    /**
     * Add a new folder
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = trim(request('name'));

        $path = $this->getCurrentPath($folder_name);

        if (empty($folder_name)) {
            return $this->error('folder-name');
        } elseif (File::exists($path)) {
            return $this->error('folder-exist');
        } elseif (config('lfm.alphanumeric_directory') && preg_match('/[^\w-]/i', $folder_name)) {
            return $this->error('folder-alnum');
        } else {
            $this->createFolderByPath($path);
            return 'OK';
        }
    }

    public function getRootFolderPath($type)
    {
        if($type == 'user') return config('main.public_path').'/photos/'.$this->user->username;
        return config('main.public_path').'/photos/'.$this->user->username.'/'.$type;
    }

    public function getDirectories($path)
    {
        return array_map(function ($directory) {
            return $this->objectPresenter($directory);
        }, array_filter(File::directories($path), function ($directory) {
            return $this->getName($directory) !== config('lfm.thumb_folder_name');
        }));
    }

    public function error($error_type, $variables = [])
    {
        return trans('laravel-filemanager::lfm.error-' . $error_type, $variables);
    }

    public function createFolderByPath($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    public function uploadImage($request)
    {
        $this->setFolders();
        $out = '';
        $path = $this->basepath;
        if ($request->hasFile('upload')) {
            $files = $request->file('upload');
            if(is_array($files))
            {
                foreach($files as $file){
                    $filename = $file->getClientOriginalName();
                    $filename = $this->cleanName($filename);
                    $image = Image::make($file->getRealPath());
                    $this->pathExists($path);
                    $image->save($path.'/'.$filename);
                    $image->resize(200, 200)->save($path.'/thumbs/'.$filename);
                    $out = [
                        "uploaded" => 1,
                        "fileName" => $filename,
                        "url" => $path.'/'.$filename
                    ];
                }
            } else {
                $filename = $files->getClientOriginalName();
                $filename = $this->cleanName($filename);
                $image = Image::make($files->getRealPath());
                $this->pathExists($path);
                $image->save($path.'/'.$filename);
                $image->resize(200, 200)->save($path.'/thumbs/'.$filename);
                $out = [
                    "uploaded" => 1,
                    "fileName" => $filename,
                    "url" => $this->baseurl.'/'.$filename
                ];
            }



        }

        return json_encode($out);

    }

    public function cleanName($filename)
    {
        $filename = str_replace(' ', '_', $filename);
        $filename = str_replace('\\', '', $filename);
        return $filename;
    }
}