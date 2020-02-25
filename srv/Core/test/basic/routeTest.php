<?php


class routeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @param $URI
     * @param $expect
     * @dataProvider dataRequest
     */
    public function testRequest($URI, $expect)
    {
        $actual = router::request($URI);
        $this->assertEquals($expect, $actual);
    }

    public function dataRequest()
    {
        return [
            [
                '/',
                [
                    'ctrl' => '\Core\ctrl',
                    'args' => [],
                    'attr' => [
                        'URI' => '/',
                        'WAY' => '/',
                        'EXT' => '',
                    ],
                ]
            ],
            [
                '/foo',
                [
                    'ctrl' => '\Core\ctrlFoo',
                    'args' => [],
                    'attr' => [
                        'URI' => '/foo',
                        'WAY' => '/foo',
                        'EXT' => '',
                    ]
                ]
            ],
            [
                '/foo-a1-a2',
                [
                    'ctrl' => '\Core\ctrlFoo',
                    'args' => ['a1', 'a2'],
                    'attr' => [
                        'URI' => '/foo-a1-a2',
                        'WAY' => '/foo-a1-a2',
                        'EXT' => '',
                    ]
                ]
            ],
            [
                '/foo.json',
                [
                    'ctrl' => '\Core\ctrlFoo',
                    'args' => [],
                    'attr' => [
                        'URI' => '/foo.json',
                        'WAY' => '/foo',
                        'EXT' => 'json',
                    ]
                ]
            ],
            [
                '/foo/bar',
                [
                    'ctrl' => '\Core\foo\ctrlBar',
                    'args' => [],
                    'attr' => [
                        'URI' => '/foo/bar',
                        'WAY' => '/foo/bar',
                        'EXT' => '',
                    ]
                ],
            ],
            [
                '/foo/bar/',
                [
                    'ctrl' => '\Core\foo\bar\ctrl',
                    'args' => [],
                    'attr' => [
                        'URI' => '/foo/bar/',
                        'WAY' => '/foo/bar/',
                        'EXT' => '',
                    ]
                ],
            ]
        ];
    }

}
