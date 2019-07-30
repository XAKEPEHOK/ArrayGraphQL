<?php
/**
 * Created for ArrayGraphQL
 * Datetime: 30.07.2019 12:46
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace XAKEPEHOK\ArrayGraphQL;


class ArrayGraphQL
{

    /**
     * @param array $fields
     * @return string
     * @throws InvalidArrayException
     */
    public static function convert(array $fields): string
    {
        //Recursive remove field duplicates
        $fields = self::removeDuplicates($fields);

        //Convert array to json
        $fields = json_encode($fields, JSON_PRETTY_PRINT);

        //Remove array indexes
        $fields = preg_replace('~"\d+":\s~', '', $fields);

        //Remove quotes and colons
        $fields = str_replace(['"',':'], '', $fields);

        //Replace square brackets to curly brackets
        $fields = str_replace(['[',']'], ['{','}'], $fields);

        return $fields;
    }

    /**
     * @param array $array
     * @return array
     * @throws InvalidArrayException
     */
    private static function removeDuplicates(array $array): array
    {
        $existedKeys = [];
        foreach ($array as $key => $value) {

            $isIndexedKey = preg_match('~^\d+$~', $key);
            $isScalar = is_scalar($value);
            $isArray = is_array($value);
            $isEmpty = empty($value);

            if ($isIndexedKey) {
                if (!$isScalar) {
                    throw new InvalidArrayException('Indexed array values should be scalar', 1);
                }

                if (isset($existedKeys[$value])) {
                    unset($array[$key]);
                }
                $existedKeys[$value] = true;
            } else {
                if (!$isArray || $isEmpty) {
                    throw new InvalidArrayException('Associative array values should be non-empty arrays', 2);
                }
                $array[$key] = self::removeDuplicates($value);
            }
        }
        return $array;
    }

}