<?php
namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Support\Facades\DB;

class LoginListener
{

    // handle方法中处理事件
    public function handle(LoginEvent $event)
    {
        //获取事件中保存的信息
        $user = $event->getUser();
        $agent = $event->getAgent();
        $ip = $event->getIp();
        $timestamp = $event->getTimestamp();


        //登录信息
        $login_info = [
            'ip' => $ip,
            'login_time' => $timestamp,
            'user_id' => $user['user_id'],
            'username' => $user['username']
        ];


        $addresses = \Ip::find($ip);
        $login_info['address'] = implode(' ', $addresses);


        $login_info['device'] = $agent->device();
        $browser = $agent->browser();
        $login_info['browser'] = $browser . ' ' . $agent->version($browser);
        $platform = $agent->platform();
        $login_info['platform'] = $platform . ' ' . $agent->version($platform);
        $login_info['language'] = implode(',', $agent->languages());

        if ($agent->isTablet()) {

            $login_info['device_type'] = 'tablet';
        } else if ($agent->isMobile()) {

            $login_info['device_type'] = 'mobile';
        } else if ($agent->isRobot()) {

            $login_info['device_type'] = 'robot';
            $login_info['device'] = $agent->robot();
        } else {

            $login_info['device_type'] = 'desktop';
        }


        DB::table('login_log')->insert($login_info);

    }
}