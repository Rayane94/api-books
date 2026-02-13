<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_is_created_when_user_is_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', [
            'title' => 'Test Book',
            'author' => 'Author Name',
            'summary' => 'Résumé suffisamment long pour être valide.',
            'isbn' => '1234567890123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
        ]);
    }

    public function test_book_is_not_created_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', [
            'title' => 'AB', // trop court
            'author' => 'Author Name',
            'summary' => 'Résumé suffisamment long pour être valide.',
            'isbn' => '1234567890123',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('books', [
            'title' => 'AB',
        ]);
    }

    public function test_book_is_not_created_if_user_is_not_authenticated(): void
    {
        $response = $this->postJson('/api/v1/books', [
            'title' => 'Unauthorized Book',
            'author' => 'Author Name',
            'summary' => 'Résumé suffisamment long pour être valide.',
            'isbn' => '1234567890123',
        ]);

        $response->assertStatus(401);
    }
}
