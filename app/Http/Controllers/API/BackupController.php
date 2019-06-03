<?php

namespace Apkom\Http\Controllers\API;

use Illuminate\Http\Request;
use Apkom\Http\Controllers\Controller;
use BackupManager\Filesystems\Destination;
use BackupManager\Manager;

class BackupController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $this->authorize('isWarek');
        $backups = [];
        if (file_exists(storage_path('app/backup/db'))) {
            $file = \File::allFiles(storage_path('app/backup/db'));
            foreach($file as $data){
                array_push($backups,
                [
                    'name' => $data->getFileName(),
                    'size' => $data->getSize(),
                    'created' => date("F d Y H:i:s.",filemtime($data)),
                ]
                );
            }
            usort($backups, function($a, $b) {
                return -1 * strcmp($a['created'], $b['created']);
            });
        }
        return $backups;
    }
    
    public function store(Request $request)
    {
        $this->authorize('isWarek');
        if (!file_exists(storage_path('app/backup/db/') . $request->name.'.gz' )) {
            $manager = app()->make(Manager::class);
            $fileName = $request->name;
            $manager->makeBackup()->run('mysql', [
                    new Destination('local', 'backup/db/' . $fileName)
                ], 'gzip');
            return ['Message' => 'Backup Success'];
        }
        return false;
    }

    public function destroy($fileName)
    {
        $this->authorize('isWarek');
        if (file_exists(storage_path('app/backup/db/') . $fileName)) {
            unlink(storage_path('app/backup/db/') . $fileName);
            return ['Message' => 'Delete Success'];
        }
        return false;
    }

    public function download($fileName)
    {
        $this->authorize('isWarek');
        return response()->download(storage_path('app/backup/db/') . $fileName);
    }

    public function restore($fileName)
    {
        if (file_exists(storage_path('app/backup/db/') . $fileName )) {
            $manager = app()->make(Manager::class);
            $manager->makeRestore()->run('local', 'backup/db/' . $fileName, 'mysql', 'gzip');
            return ['Message' => 'Restore Success'];;
        }
        return false;
    }

}
