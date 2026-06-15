<?php

namespace App\Services;

use FilesystemIterator;
use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use ZipArchive;

class BackupService
{
    public function run(): string
    {
        $backupDir = storage_path('app/backups');

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $timestamp = now()->format('Ymd-His');
        $path = "{$backupDir}/glamhouse-backup-{$timestamp}.zip";
        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create backup archive.');
        }

        $this->addDatabaseBackup($zip);
        $this->addDirectory($zip, storage_path('app/public'), 'storage/app/public');
        $zip->addFromString('metadata.txt', implode(PHP_EOL, [
            'Application: '.config('app.name', 'Glamhouse'),
            'URL: '.config('app.url'),
            'Environment: '.config('app.env'),
            'Created at: '.now()->toIso8601String(),
        ]).PHP_EOL);

        $zip->close();
        $this->pruneOldBackups();

        return $path;
    }

    private function addDatabaseBackup(ZipArchive $zip): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $database = (string) config('database.connections.sqlite.database');

            if ($database !== ':memory:' && is_file($database)) {
                $zip->addFile($database, 'database/database.sqlite');
                return;
            }
        }

        $zip->addFromString('database/database.sql', $this->sqlDump());
    }

    private function sqlDump(): string
    {
        $driver = DB::connection()->getDriverName();
        $tables = $driver === 'mysql' ? $this->mysqlTables() : $this->sqliteTables();
        $dump = '-- Glamhouse database backup'.PHP_EOL.'-- Created at '.now()->toIso8601String().PHP_EOL.PHP_EOL;

        foreach ($tables as $table) {
            $dump .= $this->createStatement($driver, $table).PHP_EOL.PHP_EOL;

            DB::table($table)->orderByRaw('1')->chunk(500, function ($rows) use (&$dump, $table): void {
                foreach ($rows as $row) {
                    $values = array_map(fn ($value): string => $this->quote($value), (array) $row);
                    $columns = array_map(fn ($column): string => '`'.$column.'`', array_keys((array) $row));
                    $dump .= 'INSERT INTO `'.$table.'` ('.implode(', ', $columns).') VALUES ('.implode(', ', $values).');'.PHP_EOL;
                }
            });

            $dump .= PHP_EOL;
        }

        return $dump;
    }

    private function mysqlTables(): array
    {
        return array_map(
            static fn ($row): string => array_values((array) $row)[0],
            DB::select('SHOW TABLES'),
        );
    }

    private function sqliteTables(): array
    {
        return DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', 'not like', 'sqlite_%')
            ->pluck('name')
            ->all();
    }

    private function createStatement(string $driver, string $table): string
    {
        if ($driver === 'mysql') {
            $row = (array) DB::selectOne('SHOW CREATE TABLE `'.$table.'`');
            $create = array_values($row)[1] ?? '';

            return 'DROP TABLE IF EXISTS `'.$table.'`;'.PHP_EOL.$create.';';
        }

        $create = DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', $table)
            ->value('sql');

        return 'DROP TABLE IF EXISTS `'.$table.'`;'.PHP_EOL.$create.';';
    }

    private function quote(mixed $value): string
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return DB::connection()->getPdo()->quote((string) $value);
    }

    private function addDirectory(ZipArchive $zip, string $path, string $prefix): void
    {
        if (! is_dir($path)) {
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($files as $file) {
            if (! $file instanceof SplFileInfo || ! $file->isFile()) {
                continue;
            }

            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($path) + 1));
            $zip->addFile($file->getPathname(), $prefix.'/'.$relative);
        }
    }

    private function pruneOldBackups(): void
    {
        $retentionDays = (int) config('glamhouse.backups.retention_days', 14);

        if ($retentionDays <= 0) {
            return;
        }

        foreach (glob(storage_path('app/backups/glamhouse-backup-*.zip')) ?: [] as $file) {
            if (is_file($file) && filemtime($file) < now()->subDays($retentionDays)->getTimestamp()) {
                unlink($file);
            }
        }
    }
}
