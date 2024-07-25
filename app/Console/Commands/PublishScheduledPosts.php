<?php

namespace App\Console\Commands;

use App\Helpers\Constant;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $posts = Post::where('scheduled_at','<>', Null)
            ->where('scheduled_at', '<=', $now)
            ->where('status', '=', Constant::POST_STATUS['Scheduled'])
            ->get();

        foreach ($posts as $post) {
            $post->status = Constant::POST_STATUS['Active'];
            $post->save();
        }

        $this->info('Scheduled posts has been published successfully.');
    }
}
