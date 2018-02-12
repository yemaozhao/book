<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
  public function toLogin(Request $request)
  {
    $return_url = $request->input('return_url', '');

    if($return_url== '')
    {
    	$return_url =  '/book/public/index.php/category';	
    }
    return view('login')->with('return_url', urldecode($return_url));
  }

  public function toRegister($value='')
  {
    return view('register');
  }
}
