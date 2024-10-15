<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * 測試送出成功.
     */
    public function test_order_submission_success()
    {
        $payload = [
            'id' => 'A0000001',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '1500',
            'currency' => 'USD'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => 'A0000001',
                         'name' => 'Melody Holiday Inn',
                         'price' => 46500, // 1500 * 31
                         'currency' => 'TWD'
                     ]
                 ]);
    }

    /**
     * 測試送出失敗: 超過 2000 元.
     */
    public function test_order_submission_fail_price_over_2000()
    {
        $payload = [
            'id' => 'A0000002',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '2001',
            'currency' => 'TWD'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'errors' => [
                         'price' => ['Price is over 2000']
                     ]
                 ]);

    }

    /**
     * 測試送出失敗: 貨幣錯誤.
     */
    public function test_order_submission_fail_currency_format_wrong()
    {
        $payload = [
            'id' => 'A0000003',
            'name' => 'Melody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '1500',
            'currency' => 'JPY'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'errors' => [
                         'currency' => ['Currency format is wrong']
                     ]
                 ]);

    }
    
    /**
     * 測試送出失敗: 名稱有不是英文.
     */
    public function test_order_submission_fail_name_contains_non_english_characters()
    {
        $payload = [
            'id' => 'A0000003',
            'name' => 'Melody 哈囉Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '1500',
            'currency' => 'USD'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'errors' => [
                         'name' => ['Name contains non-English characters']
                     ]
                 ]);
    }

    /**
     * 測試送出失敗: 名稱沒有首字大寫.
     */
    public function test_order_submission_fail_name_is_not_capitalized()
    {
        $payload = [
            'id' => 'A0000004',
            'name' => 'melody holiday inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road'
            ],
            'price' => '1500',
            'currency' => 'USD'
        ];

        $response = $this->postJson('/orders', $payload);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'errors' => [
                         'name' => ['Name is not capitalized']
                     ]
                 ]);
    }
}
