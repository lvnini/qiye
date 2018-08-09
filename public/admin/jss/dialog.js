var dialog = {
    //错误弹框
    error: function(message){
      layer.open({
        title: '错误提示',
        icon:2,
        content: message,
      });
    },

    //成功弹框
    success: function(message,url){
      layer.open({
        icon:1,
        content: message,
        yes:function(){
          location.href=url;
        },
      });
    },
    //确定弹框
    // confirm: function(message,url){
    //   layer.open({
    //     content: message,
    //     yes:function(){
    //       location.href=url;
    //     },
    //   });
    // }

    //
    confirm: function(message,url,ico){
      layer.confirm(message, {
        icon: ico,
        btn: ['确定','取消'] //按钮
      }, function(){
        location.href=url;
      }, function(){
        layer.msg('已取消');
      });
    }

    // layer.confirm('您是如何看待前端开发？', {
    //   btn: ['重要','奇葩'] //按钮
    // }, function(){
    //   layer.msg('的确很重要', {icon: 1});
    // }, function(){
    //   layer.msg('也可以这样', {
    //     time: 20000, //20s后自动关闭
    //     btn: ['明白了', '知道了']
    //   });
    // });


}
