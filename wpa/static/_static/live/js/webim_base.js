//tls登录
function tlsLogin() {
  //跳转到TLS登录页面
  TLSHelper.goLogin({
    sdkappid: loginInfo.sdkAppID,
    acctype: loginInfo.accountType,
    url: window.location.href
  });
};

//第三方应用需要实现这个函数，并在这里拿到UserSig
function tlsGetUserSig(res) {
  //成功拿到凭证
  if (res.ErrorCode == webim.TLS_ERROR_CODE.OK) {
    //从当前URL中获取参数为identifier的值
    loginInfo.identifier = webim.Tool.getQueryString("identifier");
    //拿到正式身份凭证
    loginInfo.userSig = res.UserSig;
    //从当前URL中获取参数为sdkappid的值
    loginInfo.sdkAppID = loginInfo.appIDAt3rd = Number(webim.Tool.getQueryString("sdkappid"));
    //从cookie获取accountType
    var accountType = webim.Tool.getCookie('accountType');
    if (accountType) {
      loginInfo.accountType = accountType;
      sdkLogin();//sdk登录
    } else {
      location.href = location.href.replace(/\?.*$/gi,"");
    }
  } else {
    //签名过期，需要重新登录
    if (res.ErrorCode == webim.TLS_ERROR_CODE.SIGNATURE_EXPIRATION) {
      tlsLogin();
    } else {
      alert("[" + res.ErrorCode + "]" + res.ErrorInfo);
    }
  }
};
