<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cache;
use App\Entity\Member;
use Log;

class SyncUserActivedTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:sync-user-actived-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync user actived time';

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
        //// 注意这里获取的 Redis key 为 actived_time_for_update
        // 获取完以后立马删除，这样就只更新需要更新的用 户数据
        $data = Cache::pull('actived_time_for_update');
        if (!$data) {
            $this->error('Error: No Data!');
            return false;
        }
        Log::info("开始执行数据库同步");
        // Log::info($data);


        foreach ($data as $user_id => $last_actived_at) {
            Member::query()->where('id', $user_id)
                         ->update(['last_actived_at' => $last_actived_at]);
        }

        $this->info('Done!');
    }
}
