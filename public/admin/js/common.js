//异步提交
function option(shi,urls){
    $("#search_form").ajaxSubmit({
      type:'post',
      dataType: "json",
      url: urls,
      beforeSend: function() {
        //表单提交前做表单验证
      },
      success: function(result) {
      //提交成功后调用
        if (result.status == 0) {
          return dialog.error(result.message);
        }
        if (result.status == 1) {
          return dialog.success(result.message,location.href);
        }
      }
    });
  }

  //生成预览图
  function preview(id){
      var file = document.getElementById('uploadfile'+id).files[0];
      var url ;
      if (window.createObjectURL!=undefined) { // basic
          url = window.createObjectURL(file) ;
      } else if (window.URL!=undefined) { // mozilla(firefox)
          url = window.URL.createObjectURL(file) ;
      } else if (window.webkitURL!=undefined) { // webkit or chrome
          url = window.webkitURL.createObjectURL(file) ;
      }
      $("#img"+id).attr("src",url);
  }
