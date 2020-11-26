<?php

/*
 * Author : Maarten Verijdt (murtho@gmail.com)
 */

namespace Murtho\Utility;

/**
 * ArrayUtilities
 */
class ArrayUtilities
{
    /**
     * Mandatory Items Exist
     *
     * @param array $mandatoryItems
     * @param array $data
     * @return boolean
     */
    public static function mandatoryItemsExist(array $mandatoryItems, array $data): bool
    {
        return 0 === count(array_diff($mandatoryItems, $data));
    }

    /**
     * Mandatory Keys Exist
     *
     * @param array $mandatoryKeys
     * @param array $data
     * @return boolean
     */
    public static function mandatoryKeysExist(array $mandatoryKeys, array $data): bool
    {
        return 0 === count(array_diff_key(array_flip($mandatoryKeys), $data));
    }

    /**
     * Deepen
     *
     * @param array  $data
     * @param string $delimiter
     * @return array
     */
    public static function deepen(array $data, string $delimiter): array
    {
        $result = [];

        foreach ($data as $item) {

            $parts = explode($delimiter, $item);

            if (count($parts) > 1) {
                $key = array_shift($parts);

                if (count($parts) > 1) {
                    $result[$key][] = self::deepen([implode($delimiter, $parts)], $delimiter);
                } else {
                    $result[$key][] = $parts[0];
                }
            }
        }

        return $result;
    }

    /**
     * Flatten
     *
     * @param array  $data
     * @param string $glue
     * @return array
     */
    public static function flatten(array $data, string $glue): array
    {
        $result = [];

        foreach ($data as $key => $item) {

            if (is_array($item)) {
                foreach(self::flatten($item, $glue) as $subResult) {
                    $result[] = implode($glue, [$key, $subResult]);
                }
            }
        }

        return $result;
    }

    /**
     * Deepen Key
     *
     * @param array  $data
     * @param string $delimiter
     * @return array
     */
    public static function deepenKey(array $data, string $delimiter): array
    {
        $result = [];

        foreach ($data as $key => $item) {

            $parts = explode($delimiter, $key);

            if (count($parts) > 0) {
                $key = array_shift($parts);

                if (count($parts) > 0) {
                    if (!array_key_exists($key, $result) || !is_array($result[$key])) {
                        $result[$key] = [];
                    }

                    $result[$key] = array_merge(
                        $result[$key],
                        self::deepenKey([implode($delimiter, $parts) => $item], $delimiter)
                    );
                } else {
                    $result[$key] = $item;
                }
            }
        }

        return $result;
    }

    /**
     * Flatten Key
     *
     * @param array  $data
     * @param string $glue
     * @param string $keyPrefix
     * @return array
     */
    public static function flattenKey(array $data, string $glue, string $keyPrefix = ""): array
    {
        $result = [];

        foreach ($data as $key => $item) {

            if ("" !== $keyPrefix) {
                $subKey = implode($glue, [$keyPrefix, $key]);
            } else {
                $subKey = $key;
            }

            if (!is_array($item)) {
                $result[$subKey] = $item;
            } else {
                $result = array_merge($result, self::flattenKey($item, $glue, $subKey));
            }
        }

        return $result;
    }

    /**
     * Generate Combinations
     *
     * @param array   $data  A multidimensional array of arrays that will be combined
     * @param integer $index
     * @return array The array with all possible combinations
     */
    public static function generateCombinations(array $data, int $index = 0): array
    {
        if (!isset($data[$index])) {
            return [];
        }
        if ($index == count($data) - 1) {
            return $data[$index];
        }

        // get combinations from subsequent arrays
        $tmp = self::generateCombinations($data, $index + 1);

        $result = [];

        // concat each array from tmp with each element from $arrays[$index]
        foreach ($data[$index] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ? array_merge([$v], $t) : [$v, $t];
            }
        }

        return $result;
    }

    /**
     * Filter By Term
     *
     * @param array  $data
     * @param string $term
     * @param integer $flag
     * @return array
     */
    public static function filterByTerm(array $data, string $term, int $flag = 0): array
    {
        return array_filter($data, function ($item) use ($term) {
            return (false !== stripos($item, $term));
        }, $flag);
    }

    /**
     * Filter Sub Key By Term
     *
     * @param array  $data
     * @param string $key
     * @param string $term
     * @return array
     */
    public static function filterKeyByTerm(array $data, string $key, string $term): array
    {
        return array_filter($data, function ($item) use ($key, $term) {
            return (false !== stripos($item[$key], $term));
        });
    }
}