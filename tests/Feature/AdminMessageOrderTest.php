<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessageOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_message_appears_first_on_index_page(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $old = Message::factory()->create([
            'first_name' => 'OldFirst',
            'last_name' => 'OldLast',
        ]);
        $new = Message::factory()->create([
            'first_name' => 'NewFirst',
            'last_name' => 'NewLast',
        ]);

        $response = $this->actingAs($user)->get(route('admin.messages.index'));
        $response->assertSeeInOrder([
            'NewFirst NewLast',
            'OldFirst OldLast',
        ]);
    }

    public function test_sidebar_refresh_shows_latest_message_first(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $old = Message::factory()->create([
            'first_name' => 'OldFirst',
            'last_name' => 'OldLast',
        ]);

        // Initial load should show the old message
        $this->actingAs($user)
            ->get(route('admin.messages.sidebar'))
            ->assertSee('OldFirst OldLast');

        // Create new message after initial load
        $new = Message::factory()->create([
            'first_name' => 'NewFirst',
            'last_name' => 'NewLast',
        ]);

        // Refresh sidebar and ensure new message is first
        $response = $this->actingAs($user)->get(route('admin.messages.sidebar'));
        $response->assertSeeInOrder([
            'NewFirst NewLast',
            'OldFirst OldLast',
        ]);
    }
}
