<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteUsersByName extends Command
{
    protected $signature = 'delete:users-by-name {names*} {--soft}';
    protected $description = 'Delete users by exact names; hard delete by default, use --soft for soft delete';

    public function handle(): int
    {
        $names = $this->argument('names');
        $soft = (bool) $this->option('soft');

        $this->info('Deleting users: ' . implode(', ', $names) . ' (soft=' . ($soft ? 'true' : 'false') . ')');

        $totalDeleted = 0;
        foreach ($names as $name) {
            $users = User::withTrashed()->where('name', $name)->get();
            if ($users->isEmpty()) {
                $this->warn("No user found with name: {$name}");
                continue;
            }

            foreach ($users as $user) {
                DB::transaction(function () use ($user, $soft, &$totalDeleted) {
                    // Delete user documents and files
                    $docs = UserDocument::where('user_id', $user->id)->get();
                    foreach ($docs as $doc) {
                        $path = $doc->file_path;
                        if ($path) {
                            if (str_starts_with($path, 'docs/users/')) {
                                $full = public_path('/' . $path);
                                if (is_file($full)) { @unlink($full); }
                            } else {
                                @Storage::delete($path);
                            }
                        }
                        $doc->delete();
                    }

                    if ($soft) {
                        $user->delete();
                    } else {
                        $user->forceDelete();
                    }
                    $totalDeleted++;
                    $this->line('Deleted user id=' . $user->id . ' name=' . $user->name);
                });
            }
        }

        $this->info('Total users deleted: ' . $totalDeleted);
        return Command::SUCCESS;
    }
}

