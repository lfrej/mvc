<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Book.
 */
class BookTest extends TestCase
{
    /**
     * Test set and get title book.
     */
    public function testSetTitle()
    {
        $title = "Catching Fire";

        $book = new Book();
        $book->setTitle($title);

        $res = $book->getTitle();
        $this->assertEquals("Catching Fire", $res);
    }

    /**
     * Test set author book.
     */
    public function testSetAuthor()
    {
        $author = "Suzanne Collins";

        $book = new Book();
        $book->setAuthor($author);

        $res = $book->getAuthor();
        $this->assertEquals("Suzanne Collins", $res);
    }

    /**
     * Test set ISBN book.
     */
    public function testSetIsbn()
    {
        $isbn = "9781407132099";

        $book = new Book();
        $book->setIsbn($isbn);

        $res = $book->getIsbn();
        $this->assertEquals("9781407132099", $res);
    }

    /**
     * Test set id book.
     */
    public function testSetId()
    {
        $book = new Book();
        $book->setId(5);

        $res = $book->getId();
        $this->assertEquals("5", $res);
    }
}
