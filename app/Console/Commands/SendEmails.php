<?php

namespace App\Console\Commands;

use App\Mail\Advertisement;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send
    {--Q|queue=default : The names of the queues to send emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send advertisement emails to users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $queue = $this->option('queue');

        $this->sendEmails($queue);

        return 0;
    }

    /**
     * 이메일 전송
     *
     * @param  string  $queue
     * @return void
     */
    private function sendEmails(string $queue)
    {
        $posts = $this->posts();

        $this->users()->each(fn (User $user) => Mail::to($user)->send(
            (new Advertisement($posts))->onQueue($queue)
        ));
    }

    /**
     * 메일을 받을 사용자 목록
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function users()
    {
        return User::verified()->get();
    }

    /**
     * 글 목록
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function posts()
    {
        return Post::latest()->limit(5)->get();
    }
}
