<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VerificationTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Campus Deals')
            ->assertSee('Offers and coupon codes are for demonstration.')
            ->assertSee('15% off notebooks');

        $this->get('/coupon')->assertOk();
        $this->get('/student-discount')->assertOk();
        $this->get('/search?q=Campus+Book+Nook')->assertOk()->assertSee('Campus Book Nook');
        $this->get('/stores/campus-book-nook')->assertOk();
        $this->get('/contact')->assertOk();
        $this->get('/privacy-policy')->assertOk();

        $this->assertSame(0, User::count());
        $this->assertSame(0, VerificationTag::count());
    }

    public function test_an_administrator_can_be_created_without_a_default_password(): void
    {
        $password = Str::random(24);
        $email = 'demo-admin@example.test';

        $this->artisan('demo:admin')
            ->expectsQuestion('Administrator name', 'Demo Administrator')
            ->expectsQuestion('Administrator email', $email)
            ->expectsQuestion('Administrator password', $password)
            ->assertSuccessful();

        $this->post('/login', ['email' => $email, 'password' => $password])
            ->assertRedirect('/admin/dashboard');

        $this->assertAuthenticated();
        $this->get('/admin/dashboard')->assertOk();
    }
}
