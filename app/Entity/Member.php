<?php
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Cache;

class Member extends Model
{
	protected $table = 'member';
	protected $primaryKey = 'id';

	//记录最后活跃时间
	public static function recordLastActivedAt()
	{
		$now = Carbon::now()->toDateTimeString();

		// 这个 Redis 用于数据库更新，数据库每同步一次则清空一次该 Redis 。
	    $update_key = 'actived_time_for_update';
	    $update_data = Cache::get($update_key);   // 取所有用户的
	    $update_data[session('member.id')] = $now;    // 更新当前用户的
	    Cache::forever($update_key, $update_data);  // 更新之后重新写入到缓存中

// session('member.id')

	    // 这个 Redis 用于读取，每次要获取活跃时间时，先到该 Redis 中获取数据。
	    $show_key = 'actived_time_data';
	    $show_data = Cache::get($show_key);
	    $show_data[session('member.id')] = $now;
	    Cache::forever($show_key, $show_data);
	}

	//查询最后活跃时间
	public function lastActivedAt()
	{
	    $show_key  = 'actived_time_data';
	    $show_data = Cache::get($show_key);

	    // 如果 Redis 中没有（可能redis崩了），则从数据库里获取，并同步到 Redis 中
	    if (!isset($show_data[session('member.id')])) {
	        $show_data[session('member.id')] = session('member.last_actived_at');
	        Cache::forever($show_key, $show_data);
	    }

	    return $show_data[session('member.id')];
	}
}