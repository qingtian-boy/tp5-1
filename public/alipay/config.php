<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016090900468384",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAuWG+Yr6uGS3bnD8sxXuzf3z4IJvhoep6a/VYJKa6wrMccgZbi493jOXDs+SIhBeAuTe8N+mKvFSXhCaLFcTq4s9skT4OvOEO2nAjCLbP+6s5t/r52jeaCTJiqcShT0XuDSseDeO67+aPf5eKhUP35rxCjBjT4zAcDfUoK3DDtWHH8QRbMKW777qD185uy80dOUTxqXrCbOVviLYo1Cz0M1eN5rEirUQ1Cq+p2mxk34aQ4TD5aX8SssMR+uN47N1AjvxgIr7c8pVnLPRS0qstYGiBISEoXnIHX4ypgidaTfxXVZnvyVUKpOpryIsIT6JGzPbBwBEtFuficw8mR+SGZQIDAQABAoIBAQCfRq/BP3tt/syVLmJVOrc6bjDyM3xlFYUhhKa6ttX3utRR08yL2DQrRq4ivdPdSiQ6/vwkPfAJZ9TWohcWGR6GyRjKobsf5W5Rl89CCXAhFzh8LpLFPsiF5liG7H58EeU6QzWLUYKn5Dyer6FAZulzwYSbwikrJYa1J1HgIP7Fs0oZneKf2F3quenoLdN1voptUAadOdb2UATA0tCG+iqCcq4KzhkDMXmhIYX99yJurNSgkUPCWUtkaVPJLhHPn+NNXESIhbR75E6oehoFavqlZK3cvvFAuQxfwXOSsTQdHE1fGcRvR+b/TP5iDxms+JmtBqTZvYNeWZdDggVzz5kBAoGBAOsnkvhVBeyX4sQHXr3kW/6PUV2Eb6vkuqDkSkZPdCszi3BXUT81cM/lAL/wELK5JwbVVHxywPZGBnwvK6g0AQajoY/xYp1GmNya/GIQzTPG5tWmFmGIEMDv4dRUrk9BoVyQ0ODcwl95jnMLIE//xByp/8ZgQQ+ZHyC/4Tv0j2KVAoGBAMnQqabPWn8KC460DLU79bqkqp42wQ2extOVv16EvvYmyDQMi8ol0K6DvlbU93kFOVo604oPLe1MQPU5D2b28EDqSKMbXRlPmX2LsOVSF+DBm+pJnBbpGczlxmxowTD0dR6K7CP/HuytiFGiyUlqUVKS/LhXDgz2D11GBeJeuvCRAoGAKiy81UEagOZ0y0evHEuanDtEEouwI5owRq7rC1UwtpbWCV3/umBxTTf7PHFn7UX70+Pq9S73oLPKcfdb0x0Qmgo67cATObXxsI8zeLsesoyX1fWlyfRHXPMpA5X0dU6GDD4E2G9/hPQeMk/qia6Lk8CBmSimQo/p30vKr6AbH0kCgYAnKr7yJ8XpZ9qsXFcnCTb8fR67y+aVrR3rTGGyh1LEVOx/5XtvxP0GEX1sPrK0owXpdAdAAQgpyKXGscdCdWRVROLJ/PKJigBA9E5lgxQtQ4Xt51FKxPyD3ogu2/m6dYcRYWbI6adnyZnuNgiYnfCgdPRlFkgczdhXgtJ7h2UCcQKBgGU+jj+jVDWFbVSb77zm/UWKhvEBkvK10hTFgfaRUcrlTJxRSeQTK46wM+kVY6ekW/5jnAMpcDPY4dOGqoj1PyCH6mhBgQ5Jtzj9s3EPI4uF+qlK/gWQE8VF7viDGN/airg2TubkBg5KO1CEhHln1vFuc01Bd5Y3bge7izXUgwA3",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关(就是支付宝支付请求的外网地址)
		//线上（生产）环境网关：https://openapi.alipay.com/gateway.do
		//支付宝沙箱环境网关：https://openapi.alipaydev.com/gateway.do
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuAOpKiizg/WmN15gYAsFoalJ2i4855gwj0TPI7kVZEFFBbrDwZ61ct1kmASp9FHzCbxy6ovS4lTRG/D6Zc+B56Y1adufxe/UUAkVHEg0fPo90aod8lE81XS79rNpYmZ2Hw2qjOrTYoXzpofQJrRPyz9K/AhMWgKkNORXJWaxhUG3bRsFFPICwC3v0PyMx3LLJ+yDOgZJnR9xqboklJ6Gl+tTFl9vUmAXk9Y8ECX/ja+vCyxPI5uVNOhsBIT/LbfNpI6DS6M7d/w5FgJ4kzBG7BR3/ff5j2/Tqz3dKRGxMnq++3likWbEGUbpdofHQOMiI+xYz5rfj2RXHKCoX2ki6QIDAQAB",
);