<?php

namespace Tests\Feature;

use App\Models\Bill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_generate_qr_code_from_bill_data(): void
    {
        $billData = [
            'items' => [
                ['name' => 'Pizza', 'price' => 15.99],
                ['name' => 'Soda', 'price' => 2.50],
                ['name' => 'Salad', 'price' => 8.99],
            ]
        ];

        $response = $this->postJson('/api/bills/generate-qr', $billData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'uuid',
                'url',
                'qr_code',
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('bills', [
            'uuid' => $response->json('uuid'),
        ]);

        $bill = Bill::where('uuid', $response->json('uuid'))->first();
        $this->assertEquals($billData['items'], $bill->bill_data);
    }

    public function test_bill_generation_requires_items(): void
    {
        $response = $this->postJson('/api/bills/generate-qr', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items']);
    }

    public function test_bill_items_must_have_name_and_price(): void
    {
        $response = $this->postJson('/api/bills/generate-qr', [
            'items' => [
                ['name' => 'Pizza'],
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.price']);
    }

    public function test_can_view_bill_page(): void
    {
        $bill = Bill::create([
            'uuid' => 'test-uuid-123',
            'bill_data' => [
                ['name' => 'Pizza', 'price' => 15.99],
                ['name' => 'Soda', 'price' => 2.50],
            ],
        ]);

        $response = $this->get("/bill/{$bill->uuid}");

        $response->assertStatus(200)
            ->assertSee('Pizza')
            ->assertSee('15.99')
            ->assertSee('Soda')
            ->assertSee('2.50');
    }

    public function test_bill_page_returns_404_for_invalid_uuid(): void
    {
        $response = $this->get('/bill/invalid-uuid');

        $response->assertStatus(404);
    }
}
