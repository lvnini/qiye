var login = {
  check : function(){
    //获取用户名和密码
    var username =$("input[name='username']").val();
    var password =$("input[name='password']").val();
    var captcha =$("input[name='captcha']").val();

    if(!password && !username){
      dialog.error("用户名和密码不能为空");
      return;
    }else if(!username){
      dialog.error("用户名不能为空");
      return;
    }else if(!password){
      dialog.error("密码不能为空");
      return;
    }

    var url="/Login/check";
    //alert(url);
    var data={'username':username,'password':password,'captcha':captcha};
    $.post(url,data,function(result) {
      //alert("result2");
      if (result.status == 0) {
        if (result.message != '验证码错误') {
          reloadcode('#captcha_img');
        }
        return dialog.error(result.message);

      }
      if (result.status == 1) {
        // return dialog.success(result.message,'');
        window.location.href = "/index/index.html";
      }
    },'JSON');
  }
}
