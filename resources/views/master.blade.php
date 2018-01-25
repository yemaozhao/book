<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
	<title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="/book/public/css/weui.css" >
	<link rel="stylesheet" type="text/css" href="/book/public/css/book.css" >
  <style type="text/css">
      .bk_title_bar {position: relative;height: 50px;width: 100%; background-color: #25201f}
      .bk_title_content {position: absolute;left: 50px;right: 50px;line-height: 50px;text-align: center;font-size: 18px;color: #FFFFFF}
      .bk_back, .bk_menu {position: absolute;width: 30px;height: 30px;top: 10px;}
      .bk_back {left: 10px;}
      .bk_menu {right: 10px;}

      .bk_content {margin-bottom: 50px;}
      .bk_fix_bottom {position: fixed; bottom: 0; width: 100%; background-color: #eeeeee;}
      .bk_half_area {width: 45%; padding: 5px 2%; display: inline-block;}
      .bk_btn_area {width: 96%; padding: 5px 2%; display: inline-block;}
  </style>
</head>
<body>
  
  <div class="bk_title_bar" >
    {{-- <a href="#">返回</a> --}}
     <img onclick="history.go(-1);" class="bk_back" src="/book/public/images/back.png" alt="">  
    <p class="bk_title_content" > </p>
    <img onclick="onMenuClick();" class="bk_menu" src="/book/public/images/menu.png" alt="" > 
    {{-- <a href="#" style="position: absolute;width: 30px;height: 30px;top: 10px;">菜单</a> --}}

  </div>
  
	<div class="page">
	@yield('content')
	</div>

	<!-- tooltips -->
<div class="bk_toptips"><span></span></div>



<!--BEGIN actionSheet-->
<div id="actionSheet_wrap">
    <div class="weui_mask_transition" id="mask"></div>
    <div class="weui_actionsheet" id="weui_actionsheet">
        <div class="weui_actionsheet_menu">
            <div class="weui_actionsheet_cell" onclick="onMenuItemClick(1)">登录/注册@if(Session::has('member')) (已登录) 
            @else
            (未登录) 
          @endif</div>
            <div class="weui_actionsheet_cell" onclick="onMenuItemClick(2)">书籍类别</div>
            <div class="weui_actionsheet_cell" onclick="onMenuItemClick(3)">购物车</div>
            <div class="weui_actionsheet_cell" onclick="onMenuItemClick(4)">我的订单</div>
        </div>
        <div class="weui_actionsheet_action">
            <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
        </div>
    </div>
</div>
</body>

<script src="/book/public/js/jquery-1.11.2.min.js"></script>

<script type="text/javascript">
function hideActionSheet(weuiActionsheet, mask) {
    weuiActionsheet.removeClass('weui_actionsheet_toggle');
    mask.removeClass('weui_fade_toggle');
    weuiActionsheet.on('transitionend', function () {
        mask.hide();
    }).on('webkitTransitionEnd', function () {
        mask.hide();
    })
}

function onMenuClick () {
    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    weuiActionsheet.addClass('weui_actionsheet_toggle');
    mask.show().addClass('weui_fade_toggle').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    $('#actionsheet_cancel').click(function () {
        hideActionSheet(weuiActionsheet, mask);
    });
    weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
}

function onMenuItemClick(index) {
  var mask = $('#mask');
  var weuiActionsheet = $('#weui_actionsheet');
  hideActionSheet(weuiActionsheet, mask);
  if(index == 1) {

    location.href='/book/public/index.php/login';

    // $('.bk_toptips').show();
    // $('.bk_toptips span').html("敬请期待!");
    // setTimeout(function() {$('.bk_toptips').hide();}, 2000);
  } else if(index == 2) {
    location.href='/book/public/index.php/category';
  } else if(index == 3){
    location.href='/book/public/index.php/cart';

  }else if(index == 4){
    location.href='/book/public/index.php/order_list';
    
  } else {
    $('.bk_toptips').show();
    $('.bk_toptips span').html("敬请期待!");
    setTimeout(function() {$('.bk_toptips').hide();}, 2000);
  }
}

  $('.bk_title_content').html(document.title);
</script>

@yield('my-js')
</html>