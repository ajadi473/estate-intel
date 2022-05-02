<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Http\Resources\BooksResource;
use App\Models\Books;
use Database\Factories\booksFactory;
use Tests\TestCase;
use GuzzleHttp\Client as client;

class BookTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPopulateBookModel()
    {
        $posts = Books::factory()->count(25)->make();
        $this->assertTrue(true);
    }

    public function testCreateBooks()
    {
        $data = [
            'name' => "Ajadi Paul",
            'isbn' => "978-0553108033",
            'authors' => "George R. R. Martin",
            'country' => "Brazil",
            'number_of_pages' => "100",
            'publisher' => "Ajadi, P. O",
            'release_date' => "2023-03-23",
        ];

        $response = $this->json('POST', '/api/v1/books/create',$data);
        $response->assertStatus(200);
        $response->assertJson(['status_code' => 201]);
        $response->assertJson(['status' => "success"]);
    }

    public function testGetAllBooks()
    {
        $response = $this->json('GET', '/api/v1/books');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "data" => [
                [
                    'name',
                    'isbn',
                    'authors',
                    'number_of_pages',
                    'publisher',
                    'country',
                    'release_date',
                ]
            ]
        ]);
    }

    
    public function testUpdateBooks()
    {
            $response = $this->json('GET', '/api/v1/books');
            $response->assertStatus(200);

            $book = $response->getData()->data[0];


            $update = $this->json('PATCH', '/api/v1/books/update/'.$book->id,['isbn' => "986-2322-12121"]);
            $update->assertStatus(200);
            $update->assertJson(['message' => "The book $book->name was updated successfully"]);
    }
     
    public function testDeleteBooks()
    {
        $response = $this->json('GET', '/api/v1/books');
        $response->assertStatus(200);

        $book = $response->getData()->data[0];

        $response = $this->json('DELETE', '/api/v1/books/delete/'.$book->id);
        $response->assertStatus(200);
        $response->assertJson(['message' => "The book $book->name was deleted successfully"]);
  
    }

}
