<?php

/*
 * Application    : Shop4raad
 * Author         : Maarten Verijdt (mverijdt@gmail.com)
 */

namespace Murtho\Tests\Unit\Utility;

use Murtho\Utility\ArrayUtilities;
use PHPUnit\Framework\TestCase;

/**
 * Array Utilities Test
 */
class ArrayUtilitiesTest extends TestCase
{
    /**
     * @param array   $mandatoryKeys
     * @param array   $providedData
     * @param boolean $expectedOutput
     *
     * @test
     * @dataProvider provideMandatoryItemsData
     */
    public function mandatoryItemsArePresent(array $mandatoryKeys, array $providedData, $expectedOutput)
    {
        $output = ArrayUtilities::mandatoryItemsExist($mandatoryKeys, $providedData);

        static::assertEquals($expectedOutput, $output);
        static::assertIsBool($output);
    }

    /**
     * @return array
     */
    public function provideMandatoryItemsData() : array
    {
        return [
            [["all", "items", "are", "present"], ["all", "items", "are", "present"], true],
            [["these", "items", "are", "present"], ["these", "items", "are", "present", "and", "a", "few", "more"], true],
            [["not", "all", "items", "are", "present"], ["all", "items", "are", "present"], false],
            [["not", "even", "one", "item", "is", "present"], [], false],
            [["ordered", "differently"], ["differently", "ordered"], true]
        ];
    }

    /**
     * @param array   $mandatoryKeys
     * @param array   $providedData
     * @param boolean $expectedOutput
     *
     * @test
     * @dataProvider provideMandatoryKeysData
     */
    public function mandatoryKeysArePresent(array $mandatoryKeys, array $providedData, $expectedOutput)
    {
        $output = ArrayUtilities::mandatoryKeysExist($mandatoryKeys, $providedData);

        static::assertEquals($expectedOutput, $output);
        static::assertIsBool($output);
    }

    /**
     * @return array
     */
    public function provideMandatoryKeysData() : array
    {
        return [
            [["all", "keys", "are", "present"], array_flip(["all", "keys", "are", "present"]), true],
            [["these", "keys", "are", "present"], array_flip(["these", "keys", "are", "present", "and", "a", "few", "more"]), true],
            [["not", "all", "keys", "are", "present"], array_flip(["all", "keys", "are", "present"]), false],
            [["not", "even", "one", "key", "is", "present"], array_flip([]), false],
            [["ordered", "differently"], array_flip(["differently", "ordered"]), true]
        ];
    }

    /**]
     * @param array  $providedData
     * @param string $delimiter
     * @param array $expectedOutput
     *
     * @test
     * @dataProvider deepensArrayCorrectlyData
     */
    public function deepensArrayCorrectly(array $providedData, $delimiter, array $expectedOutput)
    {
        $output = ArrayUtilities::deepen($providedData, $delimiter);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function deepensArrayCorrectlyData() : array
    {
        return [
            [
                ["this_item", "that_item"],
                "_",
                [
                    ["this" => ["item"]], ["that" => ["item"]]
                ]
            ],
            [
                ["descend-into-darkness"],
                "-",
                [
                    ["descend" => ["into" => ["darkness"]]]
                ]
            ],
        ];
    }

    /**]
     * @param array  $providedData
     * @param string $glue
     * @param array $expectedOutput
     *
     * @test
     * @dataProvider deepensArrayCorrectlyData
     */
    public function flattensArrayCorrectly(array $providedData, $glue, array $expectedOutput)
    {
        $output = ArrayUtilities::flatten($providedData, $glue);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function flattensArrayCorrectlyData() : array
    {
        return [
            [
                [
                    ["this" => ["item"]], ["that" => ["item"]]
                ],
                "_",
                ["this_item", "that_item"]
            ],
            [
                [
                    ["ascend" => ["from" => ["darkness"]]]
                ],
                "-",
                ["ascend-from-darkness"]
            ],
        ];
    }

    /**
     * @param array  $providedData
     * @param string $delimiter
     * @param array  $expectedOutput
     *
     * @test
     * @dataProvider deepensArrayKeyCorrectlyData
     */
    public function deepensArrayKeyCorrectly(array $providedData, $delimiter, array $expectedOutput)
    {
        $output = ArrayUtilities::deepenKey($providedData, $delimiter);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function deepensArrayKeyCorrectlyData() : array
    {
        return [
            [
                ["nothing" => "happens"],
                ".",
                ["nothing" => "happens"]
            ],
            [
                ["something.happens" => "here"],
                ".",
                ["something" => ["happens" => "here"]]
            ],
            [
                ["it_even_works" => "recursively"],
                "_",
                ["it" => ["even" => ["works" => "recursively"]]]
            ],
            [
                ["btn.add" => "toevoegen", "btn.edit" => "bewerken"],
                ".",
                ["btn" => ["add" => "toevoegen", "edit" => "bewerken"]]
            ],
        ];
    }

    /**
     * @param array  $providedData
     * @param string $glue
     * @param array  $expectedOutput
     *
     * @test
     * @dataProvider flattensArrayKeyCorrectlyData
     */
    public function flattensArrayKeyCorrectly(array $providedData, $glue, array $expectedOutput)
    {
        $output = ArrayUtilities::flattenKey($providedData, $glue);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function flattensArrayKeyCorrectlyData() : array
    {
        return [
            [
                ["nothing" => "happens"],
                ".",
                ["nothing" => "happens"]
            ],
            [
                ["something" => ["happens" => "here"]],
                ".",
                ["something.happens" => "here"]
            ],
            [
                ["it" => ["even" => ["works" => "recursively"]]],
                "_",
                ["it_even_works" => "recursively"]
            ],
            [
                ["btn" => ["add" => "toevoegen", "edit" => "bewerken"]],
                ".",
                ["btn.add" => "toevoegen", "btn.edit" => "bewerken"]
            ],
        ];
    }
    
    /**
     * @param array $providedData
     * @param array $expectedOutput
     *
     * @test
     * @dataProvider provideGenerateCombinationsData
     */
    public function generatesAllCombinations(array $providedData, array $expectedOutput)
    {
        $output = ArrayUtilities::generateCombinations($providedData);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function provideGenerateCombinationsData() : array
    {
        return [
            [
                [["A1", "A2"], ["B1", "B2"]],
                [
                    ["A1", "B1"], ["A1", "B2"],
                    ["A2", "B1"], ["A2", "B2"],
                ],
            ],
            [
                [["C1", "C2", "C3"], ["F1", "F2", "F3"]],
                [
                    ["C1", "F1"], ["C1", "F2"], ["C1", "F3"],
                    ["C2", "F1"], ["C2", "F2"], ["C2", "F3"],
                    ["C3", "F1"], ["C3", "F2"], ["C3", "F3"],
                ],
            ],
            [
                [["E1", "E2", "E3", "E4"], ["F1", "F2"], ["G1"]],
                [
                    ["E1", "F1", "G1"], ["E1", "F2", "G1"], 
                    ["E2", "F1", "G1"], ["E2", "F2", "G1"], 
                    ["E3", "F1", "G1"], ["E3", "F2", "G1"], 
                    ["E4", "F1", "G1"], ["E4", "F2", "G1"],
                ],
            ],
            [
                [["H1", "H2"], ["I1", "I2"], ["J1", "J2"], ["K1", "K2"]],
                [
                    ["H1", "I1", "J1", "K1"], ["H1", "I1", "J1", "K2"], ["H1", "I1", "J2", "K1"], ["H1", "I1", "J2", "K2"],
                    ["H1", "I2", "J1", "K1"], ["H1", "I2", "J1", "K2"], ["H1", "I2", "J2", "K1"], ["H1", "I2", "J2", "K2"],
                    ["H2", "I1", "J1", "K1"], ["H2", "I1", "J1", "K2"], ["H2", "I1", "J2", "K1"], ["H2", "I1", "J2", "K2"],
                    ["H2", "I2", "J1", "K1"], ["H2", "I2", "J1", "K2"], ["H2", "I2", "J2", "K1"], ["H2", "I2", "J2", "K2"],
                ]
            ],
            [
                [["L1"], ["M1"], ["N1"], ["O1"], ["P1"]],
                [
                    ["L1", "M1", "N1", "O1", "P1"],
                ],
            ],
            [
                [["Q1"], ["R1"], ["S1"], ["T1"], ["U1", "U2"]],
                [
                    ["Q1", "R1", "S1", "T1", "U1"], ["Q1", "R1", "S1", "T1", "U2"],
                ],
            ],
            [
                [["V1"], ["W1"], ["X1", "X2"], ["Y1", "Y2", "Y3"], ["Z1"]],
                [
                    ["V1", "W1", "X1", "Y1", "Z1"], ["V1", "W1", "X1", "Y2", "Z1"], ["V1", "W1", "X1", "Y3", "Z1"],
                    ["V1", "W1", "X2", "Y1", "Z1"], ["V1", "W1", "X2", "Y2", "Z1"], ["V1", "W1", "X2", "Y3", "Z1"],
                ],
            ],
        ];
    }

    /**
     * Filters By Term
     *
     * @param array  $data
     * @param string $term
     * @param integer $flag
     * @param array $expectedOutput
     *
     * @test
     * @dataProvider provideFilterByTermData
     */
    public function filtersByTerm(array $data, $term, $flag, array $expectedOutput)
    {
        $output = ArrayUtilities::filterByTerm($data, $term, $flag);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function provideFilterByTermData() : array
    {
        return [
            [
                [0 => "The Glass Prison", 1 => "Blind Faith", 2 => "Misunderstood", 3 => "The Great Debate", 4 => "Disappear", 5 => "Six Degrees Of Inner Turbulence"],
                "The",
                0,
                [0 => "The Glass Prison", 3 => "The Great Debate"]
            ],
            [
                ["one" => "The Glass Prison", "two" => "Blind Faith", "three" => "Misunderstood", "four" => "The Great Debate", "five" => "Disappear", "six" => "Six Degrees Of Inner Turbulence"],
                "ea",
                0,
                ["four" => "The Great Debate", "five" => "Disappear"]
            ],
            [
                ["Episode I" => "The Phantom Menace", "Episode II" => "Attack Of The Clones", "Episode III" => "Revenge Of The Sith"],
                "III",
                ARRAY_FILTER_USE_KEY,
                ["Episode III" => "Revenge Of The Sith"]
            ],
            [
                ["Episode IV" => "A New Hope", "Episode V" => "The Empire Strikes Back", "Episode VI" => "Return Of The Jedi"],
                "IV",
                ARRAY_FILTER_USE_KEY,
                ["Episode IV" => "A New Hope"]
            ],
            [
                ["Episode VII" => "The Force Awakens", "Episode VIII" => "The Last Jedi", "Episode IX" => "The Rise of Skywalker"],
                "II",
                ARRAY_FILTER_USE_KEY,
                ["Episode VII" => "The Force Awakens", "Episode VIII" => "The Last Jedi"]
            ],
        ];
    }

    /**
     * Filters Key By Term
     *
     * @param array  $data
     * @param mixed  $key
     * @param string $term
     * @param array $expectedOutput
     *
     * @test
     * @dataProvider provideFilterKeyByTermData
     */
    public function filtersKeyByTerm(array $data, $key, $term, array $expectedOutput)
    {
        $output = ArrayUtilities::filterKeyByTerm($data, $key, $term);

        static::assertEquals($expectedOutput, $output);
        static::assertIsArray($output);
    }

    /**
     * @return array
     */
    public function provideFilterKeyByTermData() : array
    {
        return [
            [
                [0 => ["artist" => "Underoath", "album" => "Define The Great Line"], 1 => ["artist" => "Polaris", "album" => "The Mortal Coil"]],
                "artist",
                "Under",
                [0 => ["artist" => "Underoath", "album" => "Define The Great Line"]]
            ],
            [
                [0 => ["artist" => "Underoath", "album" => "Define The Great Line"], 1 => ["artist" => "Polaris", "album" => "The Mortal Coil"]],
                "album",
                "Mort",
                [1 => ["artist" => "Polaris", "album" => "The Mortal Coil"]]
            ],
        ];
    }
}