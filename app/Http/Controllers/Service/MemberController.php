<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Entity\Member;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Models\M3Email;
use App\Tool\UUID;
use Mail;
use ChromePhp;
use App\Mail\OrderShipped;

class MemberController extends Controller
{
  public function register(Request $request)
  {
    date_default_timezone_set('PRC');

    

    $email = $request->input('email', '');
    $phone = $request->input('phone', '');
    $password = $request->input('password', '');
    $confirm = $request->input('confirm', '');
    $phone_code = $request->input('phone_code', '');
    $validate_code = $request->input('validate_code', '');

    $m3_result = new M3Result;

    if($email == '' && $phone == '') {
      $m3_result->status = 1;
      $m3_result->message = '手机号或邮箱不能为空';
      return $m3_result->toJson();
    }
    if($password == '' || strlen($password) < 6) {
      $m3_result->status = 2;
      $m3_result->message = '密码不少于6位';
      return $m3_result->toJson();
    }
    if($confirm == '' || strlen($confirm) < 6) {
      $m3_result->status = 3;
      $m3_result->message = '确认密码不少于6位';
      return $m3_result->toJson();
    }
    if($password != $confirm) {
      $m3_result->status = 4;
      $m3_result->message = '两次密码不相同';
      return $m3_result->toJson();
    }

    // 手机号注册
    if($phone != '') {
      if($phone_code == '' || strlen($phone_code) != 6) {
        $m3_result->status = 5;
        $m3_result->message = '手机验证码为6位';
        return $m3_result->toJson();
      }

      $tempPhone = TempPhone::where('phone', $phone)->first();
      if($tempPhone->code == $phone_code) {
        if(time() > strtotime($tempPhone->deadline)) {
          $m3_result->status = 7;
          $m3_result->message = '手机验证码不正确';
          return $m3_result->toJson();
        }

        $member = new Member;
        $member->phone = $phone;
        $member->password = md5('bk' + $password);
        $member->active = 1;
        $member->save();

        $m3_result->status = 0;
        $m3_result->message = '注册成功';
        return $m3_result->toJson();
      } else {
        $m3_result->status = 7;
        $m3_result->message = '手机验证码不正确';
        return $m3_result->toJson();
      }

    // 邮箱注册
    } else {
      if($validate_code == '' || strlen($validate_code) != 4) {
        $m3_result->status = 6;
        $m3_result->message = '验证码为4位';
        return $m3_result->toJson();
      }

      $validate_code_session = $request->session()->get('validate_code', '');
      if($validate_code_session != $validate_code) {
        $m3_result->status = 8;
        $m3_result->message = '验证码不正确';
        return $m3_result->toJson();
      }

      $member = new Member;
      $member->email = $email;
      $member->password = md5('bk' + $password);
      $member->save();

      $uuid = UUID::create();

      $m3_email = new M3Email;
      $m3_email->to = $email;
      $m3_email->cc = 'yemaozhao@foxmail.com';
      $m3_email->subject = '米塞斯书店验证';
      $m3_email->content = '请于24小时点击该链接完成验证. http://localhost/book/public/index.php/service/validate_email'
                        . '?member_id=' .$member->id
                        . '&code=' . $uuid;
      // $m3_email->content = '一封邮件测试';                 

      $tempEmail = new TempEmail;
      $tempEmail->member_id = $member->id;
      $tempEmail->code = $uuid;
      $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24*60*60);
      $tempEmail->save();
      ChromePhp::log('UUID-->'.$uuid);
      ChromePhp::log('$m3_email-->'.json_encode($m3_email, JSON_UNESCAPED_UNICODE));

      $sendRes=Mail::to($m3_email->to)->cc($m3_email->cc)->send(new OrderShipped($m3_email));

      if($sendRes)
      {
        // $m3_result->status = 0;
        // $m3_result->message = '注册成功';
        // ChromePhp::log('sendRes-->'.json_encode($sendRes, JSON_UNESCAPED_UNICODE));
        // ChromePhp::log('returnJson-->'.$m3_result->toJson());
      }
      else
      {
        $m3_result->status = 0;
        $m3_result->message = '发送邮件成功';
        $m3_result->email = $m3_email->subject;
        ChromePhp::log('sendRes-->success send');
      }

      return $m3_result->toJson();
    }
  }


  public function login(Request $request) {
    $username = $request->get('username', '');
    $password = $request->get('password', '');
    $validate_code = $request->get('validate_code', '');

    $m3_result = new M3Result;

    // 校验
    // ....

    // 判断
    // $validate_code_session = $request->session()->get('validate_code');
    // if($validate_code != $validate_code_session) {
    //   $m3_result->status = 1;
    //   $m3_result->message = '验证码不正确';
    //   return $m3_result->toJson();
    // }

    $member = null;
    if(strpos($username, '@') == true) {
      $member = Member::where('email', $username)->first();
    } else {
      $member = Member::where('phone', $username)->first();
    }

    if($member == null) {
      $m3_result->status = 2;
      $m3_result->message = '该用户不存在';
      return $m3_result->toJson();
    } else {
      
      if($member->active != 1 ) 
      {
        $m3_result->status = 4;
        $m3_result->message = '该用户尚未完成邮箱验证';
        return $m3_result->toJson();
      }

      if(md5('bk' + $password) != $member->password) {
        $m3_result->status = 3;
        $m3_result->message = '密码不正确';
        return $m3_result->toJson();
      }

    }

    $request->session()->put('member', $member);

    $m3_result->status = 0;
    $m3_result->message = '登录成功';
    return $m3_result->toJson();
  }
}
