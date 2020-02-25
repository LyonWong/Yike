<?php


class functionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @param $attrString
     * @param $attrArray
     * @dataProvider dataAttrs
     */
    public function testEncodeAttr($attrString, $attrArray)
    {
        $attrEncode = encodeAttr($attrArray);
        $this->assertEquals($attrString, $attrEncode);
    }

    /**
     * @param $attrString
     * @param $attrArray
     * @dataProvider dataAttrs
     */
    public function testDecodeAttr($attrString, $attrArray)
    {
        $attrDecode = decodeAttr($attrString);
        $this->assertEquals($attrArray, $attrDecode, "Decode in regular format");
        $attrDecode = decodeAttr($attrString.';');
        $this->assertEquals($attrArray, $attrDecode, "Decode with extra `;`");
    }

    public function dataAttrs()
    {
        return [
            [
                'foo:Hello;bar:Kitty',
                [
                    'foo' => 'Hello',
                    'bar' => 'Kitty',
                ]
            ],
        ];
    }
}
