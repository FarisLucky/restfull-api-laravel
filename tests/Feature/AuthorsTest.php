<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Faker\Factory;
use Faker\Guesser\Name;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthorsTest extends TestCase
{
//    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_insert_500_authors()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        Author::factory()->count(500)->create();
        $this->get("api/v1/authors", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_returns_an_author_as_a_resource_object()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->getJson('/api/v1/authors/1', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => "1",
                    "type" => "authors",
                    "attributes" => [
                        "name" => "devlay",
                        'created_at' => "2021-01-13 12:25:38",
                        'updated_at' => "2021-01-13 12:25:38"

                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_returns_all_authors_as_a_collection_of_resource_objects()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $authors = Author::factory()->count(1)->create();
        $this->get("api/v1/authors", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_create_an_author_from_resource_object()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'authors',
                'attributes' => [
                    'id' => '61',
                    'name' => 'Anggi'
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "id" => '61',
                    "type" => "authors",
                    "attributes" => [
                        'name' => 'Anggi',
                        'created_at' => date('Y-m-d H:i:s', strtotime(now())),
                        'updated_at' => date('Y-m-d H:i:s', strtotime(now()))
                    ]
                ]
            ])->assertHeader('Location', url('/api/v1/authors/61'));
    }

    /**
     * @test
     */
    public function it_can_update_an_author_from_a_resource_object()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $author = Author::factory()->create();

        $creationTimestamp = now();
        sleep(1);

        $this->patchJson('/api/v1/authors/1', [
            'data' => [
                'id' => '1',
                'type' => 'authors',
                'attributes' => [
                    'name' => 'devlay',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => '1',
                    'type' => 'authors',
                    'attributes' => [
                        'name' => 'devlay',
                        'created_at' => "2021-01-13 12:25:38",
                        'updated_at' => "2021-01-13 12:25:38"
                    ]
                ]
            ]);
    }

    /**
     * this method for delete resource specific user
     * @test
     */
    public function it_can_delete_author_throught_a_delete_request()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $author = Author::factory()
            ->create();
        $this->delete('/api/v1/authors/' . $author->id, [], [
            'Accept' => 'application/vnd.api+json'
        ])->assertStatus(200);

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
            'name' => $author->name,
            'created_at' => date('Y-m-d H:i:s', strtotime($author->updated_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($author->created_at))
        ]);
    }

    /**
     * this method for validation data create authors
     * @test
     */
    public function it_validates_that_the_type_member_is_given_when_creating_an_author()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'customer',
                'attributes' => [
                    'name' => 'Salman'
                ]
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The selected data.type is invalid.',
                        'source' => [
                            'pointer' => '/data/type'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'name' => 'Anggi Dwi Febrian'
        ]);
    }

    /**
     * This method for testing validation of attributes key or value given or not
     * @test
     */
    public function it_validates_that_the_attributes_member_has_been_given_when_creating_an_author()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'authors'
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The data.attributes field is required.',
                        'source' => [
                            'pointer' => '/data/attributes'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'name' => 'Anggi Dwi Febrian'
        ]);
    }

    /**
     * This method for testing validation of attribute value must be object or Array (PHP)
     * @test
     */
    public function it_validates_that_the_attributes_member_is_an_object_given_when_creating_an_author()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'authors',
                'attributes' => 'Attributes not Object'
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The data.attributes must be an array.',
                        'source' => [
                            'pointer' => '/data/attributes'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'name' => 'Anggi Dwi Febrian'
        ]);
    }

    /**
     * this method for testing validation of attribute name must be value
     * @test
     */

    public function it_validates_that_a_name_attribute_is_given_when_creating_an_author()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'authors',
                'attributes' => [
                    'name' => ''
                ]
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The data.attributes.name field is required.',
                        'source' => [
                            'pointer' => '/data/attributes/name'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'name' => 'Anggi Dwi Febrian'
        ]);
    }

    /**
     * this method for testing validation of attribute (name) is a string
     * @test
     */
    public function it_validates_that_a_name_attribute_is_a_string_when_creating_an_author()
    {
        $user = User::factory()
            ->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'data' => [
                'type' => 'authors',
                'attributes' => [
                    'name' => 20
                ]
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The data.attributes.name must be a string.',
                        'source' => [
                            'pointer' => '/data/attributes/name'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('authors', [
            'name' => 'Anggi Dwi Febrian'
        ]);
    }

    public function it_validates_that_a_name_attribute_is_a_string_when_updating_an_author()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $author = Author::factory()->create();

        $this->patchJson("/api/v1/authors/1", [
            'data' => [
                'id' => '1',
                'type' => 'authors',
                'attributes' => [
                    'name' => 47
                ]
            ]
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title' => 'Validation Errors',
                        'details' => 'The data.attributes.name must be a string',
                        'source' => [
                            'pointer' => '/data/attribtues/name'
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('authors', [
            'id' => 1,
            'name' => $author->name
        ]);
    }

    /**
     * Authors Sort By Field
     * @test
     */
    public function it_can_sort_authors_by_name_in_descending_order_throught_a_sort_query_param()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $authors = collect([
            'Salman',
            'Faris',
            'Farid'
        ])->map(function ($name) {
            return Author::factory()->create([
                'name' => $name
            ]);
        });

        $this->get('api/v1/authors/',[
            'accept' => 'application/vnd.api+json'
        ])->assertJson([
            "data" => [
                [
                    "id" => $authors[2]->id,
                    "type" => "authors",
                    "attributes" => [
                        'name' => $authors[2]->name,
                        'created_at' => date("Y-m-d H:i:s",strtotime($authors[2]->created_at)),
                        'updated_at' => date("Y-m-d H:i:s",strtotime($authors[2]->updated_at))
                    ]

                ],[
                    "id" => $authors[1]->id,
                    "type" => "authors",
                    "attributes" => [
                        'name' => $authors[1]->name,
                        'created_at' =>  date("Y-m-d H:i:s",strtotime($authors[1]->created_at)),
                        'updated_at' =>  date("Y-m-d H:i:s",strtotime($authors[1]->updated_at)),
                    ]

                ],[
                    "id" => $authors[0]->id,
                    "type" => "authors",
                    "attributes" => [
                        'name' => $authors[0]->name,
                        'created_at' => $authors[0]->created_at,
                        'updated_at' => $authors[0]->updated_at,
                    ]

                ],
            ]
        ]);
    }
}
