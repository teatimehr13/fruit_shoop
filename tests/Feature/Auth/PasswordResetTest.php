<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHas('reset_link');
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $token = $this->extractTokenFromResetLink();

        $response = $this->get('/reset-password/'.$token);

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $token = $this->extractTokenFromResetLink();

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));
    }

    /**
     * PasswordResetLinkController 在 demo_mode 下不會真的寄信，而是把重設連結
     * 存進 session('reset_link') 直接顯示在畫面上，測試改從這裡取得 token
     * （phpunit.xml 已將 DEMO_RESET_LINK 固定設為 true）。
     */
    private function extractTokenFromResetLink(): string
    {
        $resetLink = session('reset_link');

        $this->assertNotNull($resetLink, 'demo_mode 沒有回傳 reset_link，請確認 DEMO_RESET_LINK 設定。');

        return Str::after(parse_url($resetLink, PHP_URL_PATH), '/reset-password/');
    }
}
