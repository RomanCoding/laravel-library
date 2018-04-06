<?php

namespace App\Console\Commands\Library;

use App\File;
use App\Folder;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class SyncStorageFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:sync-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync actual existing files in storage folder with database table.';

    private $existingFiles = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!Folder::where('name', '/')->whereNull('parent_id')->count()) {
            $this->createRootFolder();
        }
        $rootFolder = Folder::where('name', '/')->whereNull('parent_id')->first();

        $this->existingFiles = collect();

        $this->listFolderFiles($rootFolder, storage_path('library'));

        $files = File::all();

        $this->removeDeletedFiles($files);

        dd();

        $iterator = new \RecursiveIteratorIterator(new RecursiveDirectoryIterator(storage_path('library'), RecursiveDirectoryIterator::CURRENT_AS_PATHNAME));

        $files = File::all();

        $folders = Folder::all();

        $existingFiles = collect();

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                var_dump($file->getRealPath());
                continue;
            }

//            $relativePath = str_after($file->getRealPath(), '/storage/library/');
//            $stat = stat($file);
//
//            if (!$files->where('filepath', $relativePath)->where('filesize', $stat['size'])->count()) {
//                File::create([
//                    'filepath' => $relativePath,
//                    'filename' => $file->getFilename(),
//                    'extension' => pathinfo($file, PATHINFO_EXTENSION),
//                    'filesize' => $stat['size'],
//                    'mtime' => $stat['mtime']
//                ]);
//            } else {
//                $existingFiles->push([
//                    'filepath' => $relativePath,
//                    'filesize' => $stat['size'],
//                ]);
//            }
        }

        $this->removeDeletedFiles($files, $existingFiles);
    }


    public function listFolderFiles(Folder $parent, $dir)
    {
        $files = scandir($dir);
        $databaseFiles = File::all();
        unset($files[array_search('.', $files, true)]);
        unset($files[array_search('..', $files, true)]);

        // prevent empty ordered elements
        if (count($files) < 1)
            return;

        foreach ($files as $file) {
            if (!is_dir($dir . '/' . $file)) {
                $relativePath = $dir . '/' . $file;
                $stat = stat($dir . '/' . $file);

                if (!$databaseFiles->where('filepath', $relativePath)->where('folder_id', $parent->id)->count()) {
                    File::create([
                        'filepath' => $relativePath,
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'extension' => pathinfo($file, PATHINFO_EXTENSION),
                        'filesize' => $stat['size'],
                        'mtime' => $stat['mtime'],
                        'folder_id' => $parent->id,
                    ]);
                    $this->existingFiles->push([
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'extension' => pathinfo($file, PATHINFO_EXTENSION),
                        'folder_id' => $parent->id,
                    ]);
                } else {
                    $this->existingFiles->push([
                        'filename' => pathinfo($file, PATHINFO_FILENAME),
                        'extension' => pathinfo($file, PATHINFO_EXTENSION),
                        'folder_id' => $parent->id,
                    ]);
                }
            }
            else {
                $folder = Folder::updateOrCreate([
                    'name' =>   $file,
                    'parent_id' => $parent->id
                ]);
                $this->listFolderFiles($folder, $dir . '/' . $file);
            }
        }
    }

    private function createRootFolder()
    {
        return Folder::create([
            'name' => '/',
            'parent_id' => null
        ]);
    }

    /**
     * Delete file from database if it's not presented in filesystem.
     *
     * @param Collection $files files from filesystem
     */
    private function removeDeletedFiles(Collection $files)
    {
        foreach ($files as $file) {
            $delete = true;
            foreach ($this->existingFiles as $realFile) {
                if ($realFile['filename'] == $file->filename &&
                    $realFile['folder_id'] == $file->folder_id &&
                    $realFile['extension'] == $file->extension
                ) {
                    $files->forget($file->id);
                    $delete = false;
                    break;
                }
            }
            if ($delete) {
                File::find($file->id)->delete();
            }
        }
    }
}
