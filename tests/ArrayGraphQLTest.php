<?php
/**
 * Created for ArrayGraphQL
 * Datetime: 30.07.2019 13:43
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\ArrayGraphQL;

use PHPUnit\Framework\TestCase;

class ArrayGraphQLTest extends TestCase
{

    public function testConvert()
    {
        $fields = [
            'id',
            'id',
            'registeredAt',
            'name' => [
                'firstName',
                'firstName',
                'middleName',
                'lastName',
            ],
            'history' => [
                'count',
                'count',
                'records' => [
                    'id',
                    'name' => [
                        'firstName',
                        'middleName',
                        'lastName',
                    ],
                ]
            ],
        ];

        $expected = <<<QUERY
{
    id,
    registeredAt,
    name {
        firstName,
        middleName,
        lastName
    },
    history {
        count,
        records {
            id,
            name {
                firstName,
                middleName,
                lastName
            }
        }
    }
}
QUERY;

        $actual = ArrayGraphQL::convert($fields);

        $this->assertEquals(
            $this->normalize($expected),
            $this->normalize($actual)
        );
    }

    public function testConvertInvalidArrayScalarValue()
    {
        $this->expectException(InvalidArrayException::class);
        $this->expectExceptionCode(1);

        ArrayGraphQL::convert([
            'id',
            [
                'name'
            ]
        ]);
    }

    public function testConvertInvalidArray()
    {
        $this->expectException(InvalidArrayException::class);
        $this->expectExceptionCode(2);

        ArrayGraphQL::convert([
            'hello' => 'world'
        ]);
    }

    private function normalize(string $string): string
    {
        return  str_replace("\r\n", "\n", $string);
    }
}
