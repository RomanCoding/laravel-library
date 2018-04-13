<?php

namespace App\Console\Commands\Library;

use App\File;
use App\Folder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class SyncStorageFolder extends Command
{
    protected $signature = 'library:sync-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync actual existing files in storage folder with database table.';

    private $existingFiles = null;
    private $existingFolders = null;

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
        $this->existingFolders = collect();

        $this->existingFolders->push(str_after($rootFolder->storage_path, storage_path()));

        $this->listFolderFiles($rootFolder, storage_path('library'));

        $files = File::all();

        $folders = Folder::all();

        $this->removeDeletedFiles($files);

        $this->removeDeletedFolders($folders);
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
                $stat = stat($dir . '/' . $file);
                $storagePath = str_after($dir . '/' . $file, storage_path());
                $storagePath = str_after($storagePath, '/library');

                if (!$databaseFiles->where('filepath', $storagePath)->where('folder_id', $parent->id)->count()) {
                    File::create([
                        'filepath' => $storagePath,
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
                $storagePath = str_after($dir . '/' . $file, storage_path());
                $storagePath = str_after($storagePath, '/library');

                $this->existingFolders->push($storagePath);
                $folder = Folder::updateOrCreate([
                    'name' =>   $file,
                    'parent_id' => $parent->id,
                    'storage_path' => $storagePath
                ], [
                    'name' =>   $file,
                    'parent_id' => $parent->id,
                    'storage_path' => $storagePath
                ]);
                $this->listFolderFiles($folder, $dir . '/' . $file);
            }
        }
    }

    private function createRootFolder()
    {
        return Folder::create([
            'name' => '/',
            'parent_id' => null,
            'storage_path' => '/',
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

    /**
     * Delete file from database if it's not presented in filesystem.
     *
     * @param Collection $folders folders from filesystem
     */
    private function removeDeletedFolders(Collection $folders)
    {
        foreach ($folders as $folder) {
            $delete = true;
            foreach ($this->existingFolders as $realFolder) {
                if ($realFolder == $folder->storage_path) {
                    $folders->forget($folder->id);
                    $delete = false;
                    break;
                }
            }
            if ($delete) {
                try {
                    Folder::findOrFail($folder->id)->delete();
                } catch (Exception $e) {
                    continue;
                    // if folder already deleted
                }
            }
        }
    }
}
