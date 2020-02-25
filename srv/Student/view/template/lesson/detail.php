<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付样例-支付</title>
    <script src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

    <script type="text/javascript">
        function payOrder() {
            var params = {
                'lsn': '58f5e26822252'
            };
            $.ajax({
                url: '/pay/order-create',
                type: 'POST',
                data: params,
                dataType: 'JSON',
                async: false,
                cache: false,
                success: function (json ) {
                    if (json.error == 0) {
                        var params = {
                            "order_sn": json['data']['sn'],
                            "body": json['data']['title']
                        };
                        jsApiParameters(params)
                    } else {
                        alert('生成订单失败');
                    }
                }
            });
        }
        function jsApiParameters(params) {

            $.ajax({
                url: '/pay/order-confirm',
                type: 'POST',
                data: params,
                dataType: 'JSON',
                async: false,
                cache: false,
                success: function (json) {
                    if (json.error == 0) {
                        var params = {
                            "appId": json['data']["appId"],     //公众号名称，由商户传入
                            "timeStamp": json['data']["timeStamp"],         //时间戳，自1970年以来的秒数
                            "nonceStr": json['data']["nonceStr"], //随机串
                            "package": json['data']["package"],
                            "signType": json['data']["signType"],         //微信签名方式：
                            "paySign": json['data']["paySign"] //微信签名
                        };
                        callpay(params);
                    } else {
                        alert('生成预支付订单失败');
                    }
                }
            });
        }
        //调用微信JS api 支付

        function jsApiCall(params) {

            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                params,
                function (res) {
                    WeixinJSBridge.log(res.err_msg);
                    if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:cancel") {
                        todo();
                    }
                }
            );
        }

        function callpay(params) {
            if (typeof WeixinJSBridge == "undefined") {
                if (document.addEventListener) {
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                } else if (document.attachEvent) {
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            } else {
                jsApiCall(params);
            }
        }
    </script>

</head>
<body>
<br/>
<div align="center">
    <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;"
            type="button" onclick="payOrder()">立即支付
    </button>

</div>
</body>
</html>