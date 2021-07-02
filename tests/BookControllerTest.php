<?php

namespace App\Tests\Controller;

use App\Controller\BookController;
use PHPUnit\Framework\TestCase;

class BookControllerTest extends TestCase
{

    /**
     * @param array[] $books
     * @param array[] $expected
     * @param string|null $sorting
     * @dataProvider sortingProvider
     */
    public function testSorting(array $books, array $expected, string|null $sorting): void
    {
        $bookController = new BookController();
        $this->assertEquals($expected, $bookController->sortBooks($books, $sorting));
    }

    /**
     * @return array[]
     */
    public function sortingProvider(): array
    {
        $books = [
            [
                "id" => 15,
                "title" => "Lorem ipsum 14",
                "author" => "Author name 14",
                "summary" => "lorem ipsum summary 14",
                "pages" => 10
            ],
            [
                "id" => 2,
                "title" => "Lorem ipsum 1",
                "author" => "Author name 1",
                "summary" => "lorem ipsum summary 1",
                "pages" => 39
            ],
            [
                "id" => 12,
                "title" => "Lorem ipsum 11",
                "author" => "Author name 11",
                "summary" => "lorem ipsum summary 11",
                "pages" => 16
            ]

        ];
        $ascSortedBooks = [
            [
                "id" => 15,
                "title" => "Lorem ipsum 14",
                "author" => "Author name 14",
                "summary" => "lorem ipsum summary 14",
                "pages" => 10
            ],
            [
                "id" => 12,
                "title" => "Lorem ipsum 11",
                "author" => "Author name 11",
                "summary" => "lorem ipsum summary 11",
                "pages" => 16
            ],
            [
                "id" => 2,
                "title" => "Lorem ipsum 1",
                "author" => "Author name 1",
                "summary" => "lorem ipsum summary 1",
                "pages" => 39
            ]
        ];
        $descSortedBooks = [
            [
                "id" => 2,
                "title" => "Lorem ipsum 1",
                "author" => "Author name 1",
                "summary" => "lorem ipsum summary 1",
                "pages" => 39
            ],
            [
                "id" => 12,
                "title" => "Lorem ipsum 11",
                "author" => "Author name 11",
                "summary" => "lorem ipsum summary 11",
                "pages" => 16
            ],

            [
                "id" => 15,
                "title" => "Lorem ipsum 14",
                "author" => "Author name 14",
                "summary" => "lorem ipsum summary 14",
                "pages" => 10
            ]
        ];
        return [
            [$books, $ascSortedBooks, 'asc'],
            [$books, $descSortedBooks, 'desc'],
            [$books, $books, 'bad value']
        ];
    }

}