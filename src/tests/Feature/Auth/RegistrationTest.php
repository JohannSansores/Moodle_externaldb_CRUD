<?php

namespace Tests\Feature\Auth;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\moodle_usuarios;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_requires_captcha(): void
    {
        $response = $this->post('/register', [
            'name' => 'Alex Ram',
            'surname' => 'Ramirez',
            'email' => 'alex.r4m2002@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'curp' => 'ABCD123456EFGH789',
            'id_dependencia' => 1,
            'id_programa' => 1,
            'id_rol' => 2,
            'id_semestre' => 1,
        ]);

        $response->assertSessionHasErrors(['g-recaptcha-response']);
    }

    public function test_registration_dispatches_registered_event(): void
    {
        NoCaptcha::shouldReceive('verifyResponse')->once()->andReturn(true);
        Event::fake();

        $response = $this->post('/register', [
            'name' => 'Alex Ram',
            'surname' => 'Ramirez',
            'email' => 'alex.r4m2002@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'curp' => 'ABCD123456EFGH789',
            'id_dependencia' => 1,
            'id_programa' => 1,
            'id_rol' => 2,
            'id_semestre' => 1,
            'g-recaptcha-response' => 'test-token',
        ]);

        Event::assertDispatched(Registered::class, function ($event) {
            return $event->user->email === 'alex.r4m2002@gmail.com';
        });
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_registration_sends_verification_email(): void
    {
        NoCaptcha::shouldReceive('verifyResponse')->once()->andReturn(true);
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Alex Ram',
            'surname' => 'Ramirez',
            'email' => 'alex.r4m2002@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'curp' => 'ABCD123456EFGH789',
            'id_dependencia' => 1,
            'id_programa' => 1,
            'id_rol' => 2,
            'id_semestre' => 1,
            'g-recaptcha-response' => 'test-token',
        ]);

        $expectedUser = new moodle_usuarios(['email' => 'alex.r4m2002@gmail.com']);
        $expectedUser->id = 1;
        Notification::assertSentTo($expectedUser, VerifyEmail::class);

        $response->assertRedirect(route('verification.notice'));
    }
}
