<?php namespace Webula\SmallBackup\Models;

use Model, Db;
use Webula\SmallBackup\Classes\PathHelper;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel',
    ];

    public $requiredPermissions = ['webula.smallbackup.access_settings'];

    public $settingsCode = 'webula_smallbackup_settings';

    public $settingsFields = 'fields.yaml';


    public function getTablesOptions()
    {
        return Db::connection()->getDoctrineSchemaManager()->listTableNames();
    }


    public function getResourcesOptions()
    {
        // OCMSv1
        $resources = collect(config('cms.storage'))->where('disk', 'local')->map(function ($item) {
            return str_after(PathHelper::normalizePath(array_get($item, 'path')), PathHelper::normalizePath(base_path()));
        })->toArray();

        // OCMSv3
        if (empty($resources)) {
            $resources = collect(config('filesystems.disks'))->where('driver', 'local')->map(function ($item) {
                return str_after(PathHelper::normalizePath(array_get($item, 'root')), PathHelper::normalizePath(base_path()));
            })->toArray();
        }

        return $resources;
    }

}
