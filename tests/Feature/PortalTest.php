<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dev_login_as_student(): void
    {
        $student = User::where('role', 'student')->first();
        $this->assertNotNull($student);

        $response = $this->post(route('dev.login', $student->id));
        $response->assertRedirect(route('portal'));
        $this->assertAuthenticatedAs($student);

        $portalResponse = $this->get(route('portal'));
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee($student->name);
    }

    public function test_dev_login_as_teacher(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        $this->assertNotNull($teacher);

        $response = $this->post(route('dev.login', $teacher->id));
        $response->assertRedirect(route('portal'));
        $this->assertAuthenticatedAs($teacher);

        $portalResponse = $this->get(route('portal'));
        $portalResponse->assertStatus(200);
        $portalResponse->assertSee($teacher->name);
    }
}
