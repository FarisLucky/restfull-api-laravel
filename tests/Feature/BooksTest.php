<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     * @test
     * an return resource book object
     */
    public function it_returns_an_book_as_resource_object()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        Passport::actingAs($user);

        $this->get('/api/v1/books/' . $book->id, [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => $book->id,
                    "type" => "books",
                    "attributes" => [
                        "title" => $book->title,
                        "description" => $book->description,
                        "publication_year" => $book->publication_year,
                        "created_at" => date("Y-m-d", strtotime($book->created_at)),
                        "updated_at" => date("Y-m-d", strtotime($book->updated_at)),
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_returns_all_books_as_a_collection_of_resource_objects()
    {
        $book = Book::factory()->count(3)->create();
        $user = User::factory()->create();

        Passport::actingAs($user);

        $this->get('/api/v1/books', [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => $book[0]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $book[0]->title,
                            "description" => $book[0]->description,
                            "publication_year" => $book[0]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($book[0]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($book[0]->updated_at)),
                        ]
                    ], [
                        "id" => $book[1]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $book[1]->title,
                            "description" => $book[1]->description,
                            "publication_year" => $book[1]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($book[1]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($book[1]->updated_at)),
                        ]
                    ], [
                        "id" => $book[2]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $book[2]->title,
                            "description" => $book[2]->description,
                            "publication_year" => $book[2]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($book[2]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($book[2]->updated_at)),
                        ]
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_create_an_book_from_resource_object()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_is_given_when_creating_an_book()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/books/', [
            'data' => [
                'type' => '',
                'attributes' => [
                    'title' => 'Ikigai',
                    'description' => 'Buku tentang penemuan makna hidup',
                    'publication_year' => '1992'
                ]
            ], [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
            ]
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Error',
                        'details' => 'The data.type field is required',
                        'source' => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);
        $this->assertDatabaseMissing('books', [
            'id' => 1,
            'title' => 'Ikigai',
            'description' => 'Buku tentang penemuan makna hidup',
            'publication_year' => '1992'
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_has_the_value_of_book_when_creating()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_attribute_member_has_been_given_when_creating_an()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_attributes_member_is_an_object_given_when_creating()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_title_attribute_is_given_when_creating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_title_attribute_is_a_string_when_creating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_description_attribute_is_given_when_creating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_description_attribute_is_a_string_when_creating_an()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_publication_year_attribute_is_a_string_when_creating()
    {

    }

    /**
     * Update Testing
     */

    /**
     * @test
     */
    public function it_can_update_an_book_from_a_resource_object()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_an_id_member_is_given_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_an_id_member_is_a_string_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_is_given_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_has_the_value_of_books_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_attributes_member_has_been_given_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_the_attributes_member_is_an_object_given_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_title_attribute_is_string_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_description_attribute_is_string_when_updating_an_book()
    {

    }

    /**
     * @test
     */
    public function it_validates_that_a_publication_year_attribute_is_string_when_updating_an_book()
    {

    }

    /**
     * Delete Testing
     */

    /**
     * @test
     */
    public function it_can_delete_an_book_through_a_delete_request()
    {

    }

    /**
     * @test
     */
    public function it_can_sort_books_by_title_through_a_sort_query_parameter()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $books = collect([
            'Poor Dad and Rich Dad',
            'You Do You',
            'How To Win People and Influence'
        ])->map(function ($book) {
            return Book::factory()->create([
                "title" => $book
            ]);
        });

        $this->get('/api/v1/books?sort=title', [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => $books[2]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $books[2]->title,
                            "description" => $books[2]->description,
                            "publication_year" => $books[2]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($books[2]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($books[2]->updated_at)),
                        ]
                    ], [
                        "id" => $books[0]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $books[0]->title,
                            "description" => $books[0]->description,
                            "publication_year" => $books[0]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($books[0]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($books[0]->updated_at)),
                        ]
                    ], [
                        "id" => $books[1]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $books[1]->title,
                            "description" => $books[1]->description,
                            "publication_year" => $books[1]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($books[1]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($books[1]->updated_at)),
                        ]
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_sort_books_by_title_in_descending_order_through_a_sort_query_parameter()
    {

        $user = User::factory()->create();
        Passport::actingAs($user);

        $books = collect([
            'Poor Dad and Rich Dad',
            'You Do You',
            'How To Win People and Influence'
        ])->map(function ($book) {
            return Book::factory()->create([
                "title" => $book
            ]);
        });

        $this->get('/api/v1/books?sort=-title', [
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [[
                    "id" => $books[1]->id,
                    "type" => "books",
                    "attributes" => [
                        "title" => $books[1]->title,
                        "description" => $books[1]->description,
                        "publication_year" => $books[1]->publication_year,
                        "created_at" => date("Y-m-d", strtotime($books[1]->created_at)),
                        "updated_at" => date("Y-m-d", strtotime($books[1]->updated_at)),
                    ]
                ], [
                    "id" => $books[0]->id,
                    "type" => "books",
                    "attributes" => [
                        "title" => $books[0]->title,
                        "description" => $books[0]->description,
                        "publication_year" => $books[0]->publication_year,
                        "created_at" => date("Y-m-d", strtotime($books[0]->created_at)),
                        "updated_at" => date("Y-m-d", strtotime($books[0]->updated_at)),
                    ]
                ],
                    [
                        "id" => $books[2]->id,
                        "type" => "books",
                        "attributes" => [
                            "title" => $books[2]->title,
                            "description" => $books[2]->description,
                            "publication_year" => $books[2]->publication_year,
                            "created_at" => date("Y-m-d", strtotime($books[2]->created_at)),
                            "updated_at" => date("Y-m-d", strtotime($books[2]->updated_at)),
                        ]
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_sort_books_by_multiple_attributes_through_a_sort_query_parameter()
    {

    }

    /**
     * @test
     */
    public function it_can_sort_books_by_multiple_attributes_in_descending_order_through_a_sort_query_parameter()
    {

    }

    /**
     * @test
     */
    public function it_can_paginate_books_through_a_page_query_parameter()
    {

    }

    /**
     * @test
     */
    public function it_can_paginate_books_through_a_page_query_parameter_and_show_different_things()
    {

    }
}
