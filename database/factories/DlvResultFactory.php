<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\DlvResult::class, function (Faker $faker) {
    $created_at = $faker->numberBetween(1503264565, 1603264565);
    $updated_at = $faker->numberBetween($created_at, 1603264565);
    //手机号码和task_id都不应该是随机的
    $task_ids = \App\Models\SmsTask::whereUserId(2)->whereIn('status', [\App\Models\SmsTask::STATUS_SEND_OVER])->pluck('id')->toArray();
    $task_id = $faker->randomElements($task_ids, 1) [0];
    //选出联系人组,,然后进行
    $task_info = \App\Models\SmsTask::find($task_id);
    $group_ids = explode(' ', $task_info->group_id);
    $groupMember = new \App\Models\GroupMember(['custom_db' => 'user_id_2']);
    $contact_ids = $groupMember->whereIn('group_id', $group_ids)->pluck('contact_id')->toArray();
    $contactInfos = new \App\Models\ContactInfo(['custom_db' => 'user_id_2']);
    $mobiles = $contactInfos->whereIn('id', $contact_ids)->pluck('mobile')->toArray();
    $mobile = $faker->randomElements($mobiles, 1)[0];
    return [
        //
//        'mobile' => $faker->randomElements([13, 15, 18], 1) [0] . $faker->randomNumber(9, true),
        'mobile' => $mobile,
//        'task_id' => $faker->randomNumber(2),
        'task_id' => $task_id,
        'result' => $faker->randomElements([0, 1, 2], 1)[0],
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
