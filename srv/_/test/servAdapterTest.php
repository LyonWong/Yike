<?php


namespace _;


class servAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataClient
     * @param $client
     * @param $userAgent
     */
    public function testClient($client, $userAgent)
    {
        $_client = servAdaptor::client($userAgent);
        $this->assertSame($client, $_client);
    }

    public function dataClient()
    {
        return [
            [
                servAdaptor::CLIENT_WXA,
                'Mozilla/5.0 (Linux; Android 7.1.1; MIX 2 Build/NMF26X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043632 Safari/537.36 MicroMessenger/6.6.1.1220(0x26060134) NetType/4G Language/zh_CN MicroMessenger/6.6.1.1220(0x26060134) NetType/4G Language/zh_CN miniProgram',
            ],
            [
                servAdaptor::CLIENT_WXA,
                'Mozilla/5.0 (Linux; Android 7.1.1; MIX 2 Build/NMF26X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.49 Mobile MQQBrowser/6.2 TBS/043632 Safari/537.36 MicroMessenger/6.6.1.1220(0x26060134) NetType/4G Language/zh_CN MicroMessenger/6.6.1.1220(0x26060134) NetType/4G Language/zh_CN miniProgram'
            ]
        ];
    }

}
