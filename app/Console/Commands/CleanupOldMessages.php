<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupOldMessages extends Command
{
    protected $signature = 'messages:cleanup';
    protected $description = 'Remove legacy message system and refresh caches';

    public function handle()
    {
        $path = base_path('app/Messages/messages.php');

        $this->info('🧹 Cleaning up old message system...');

        // 1️⃣ حذف الملف القديم إن وجد
        if (File::exists($path)) {
            try {
                File::delete($path);
                $this->info("✅ Deleted: {$path}");
            } catch (\Throwable $e) {
                $this->error("⚠️ Failed to delete {$path}: " . $e->getMessage());
            }
        } else {
            $this->warn('⚠️ No legacy file found.');
        }

        // 2️⃣ حذف مجلد Messages إن كان فارغ
        $dir = dirname($path);
        if (File::isDirectory($dir) && count(File::files($dir)) === 0) {
            File::deleteDirectory($dir);
            $this->info("🗂️ Removed empty directory: {$dir}");
        }

        // 3️⃣ تنظيف الكاش
        $this->callSilent('optimize:clear');
        $this->callSilent('view:clear');
        $this->callSilent('config:clear');
        $this->callSilent('route:clear');

        $this->info('✨ Cleanup complete! The system now uses the unified message database.');
        return Command::SUCCESS;
    }
}
