<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

//* @property int $status 发送状态,0正常 1发送中 2结束 3草稿
//* @property string $activity_name 活动名称
//* @property string $send_time 发送时间
//* @property string $template_id 短信模板id
//* @property string $sign_id 短信签名id
//* @property string $group_id 发送的联系人组id,使用,隔开..不用json看看

$groupinfo = new \App\Models\GroupInfo(['custom_db' => 'user_id_2']);
$group_ids = $groupinfo->whereUserId(2)->pluck('id')->toArray();
$groupId_to_names = $groupinfo->whereUserId(2)->pluck('name', 'id')->toArray();
$template_ids = App\Models\SmsTemplate::whereUserId(2)->pluck('id')->toArray();
$templateId_to_name = App\Models\SmsTemplate::whereUserId(2)->pluck('template_name', 'id')->toArray();
$sign_id = App\Models\SmsSign::whereUserId(2)->pluck('id')->toArray();
$factory->define(\App\Models\SmsTask::class, function (Faker $faker) use ($group_ids, $template_ids, $sign_id, $groupId_to_names, $templateId_to_name) {
    $time = $faker->unixTime;

    $group_id = $faker->randomElements($group_ids, 1)[0];
    $template_id = $faker->randomElements($template_ids, 1)[0];
    return [
        //
        'user_id' => 2,
        'activity_name' => $faker->userName,
        'status' => $faker->randomElements([0, 1, 2], 1)[0],
        'group_id' => $group_id,
        'group_name' => $groupId_to_names[$group_id],
        'template_id' => $template_id,
        'start_at' => time(),
        'template_name' => $templateId_to_name[$template_id],
        'sign_id' => $faker->randomElements($sign_id, 1)[0],
        'send_time' => json_encode([
            'timeType' => $faker->numberBetween(0, 1),
            'sendDay' => Carbon::now()->toDateString(),
            'sendHour' => Carbon::now()->hour,
            'sendMin' => Carbon::now()->minute,
        ]),

//        'user_id' => 2,
//        'activity_name' => 1,
//        'status' => 1,
//        'group_id' => 1,
//        'template_id' => 1,
//        'sign_id' => $faker->randomElements($sign_id, 1)[0],
//        'send_time' => 'smgui',


    ];
});
