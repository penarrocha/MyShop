<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup MySQL database to /database directory';

    public function handle(): int
    {
        $db = Config::get('database.connections.mysql');

        $filename = 'database/backups/mysql_' . now()->format('Y_m_d_His') . '.sql';

        $command = sprintf(
            'mysqldump --no-tablespaces -h%s -u%s %s %s > %s',
            escapeshellarg($db['host']),
            escapeshellarg($db['username']),
            $db['password'] ? '-p' . escapeshellarg($db['password']) : '',
            escapeshellarg($db['database']),
            base_path($filename)
        );


        exec($command, $output, $return);

        if ($return !== 0) {
            $this->error('Error creating database backup');
            return self::FAILURE;
        }

        $this->info("Backup created: {$filename}");
        return self::SUCCESS;
    }
}
