<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\CreatesUsers;
use Faker\Factory as Faker;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\ExpensesTracker\Models\IncomeSource;

class IncomeSourcesTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    public function __construct()
    {
        parent::__construct();

        $this->faker = Faker::create();
    }

    /** @test  */
    public function unauthenticated_users_cannot_get_income_sources()
    {
        $this->getJson('api/income_sources')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function authenticated_users_cannot_get_income_sources_others_added()
    {
        $owner_user = $this->createUser();
        $auth_user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $owner_user->id
        ]);

        $this->actingAs($auth_user)
            ->getJson('api/income_sources')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonMissing(['name' => $income_source->name]);
    }

    /** @test */
    public function authenticated_users_can_get_income_sources_they_added()
    {
        $user = $this->createUser();

        $income_source1 = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $income_source2 = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->getJson('api/income_sources')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['name' => $income_source1->name])
            ->assertJsonFragment(['name' => $income_source2->name]);
    }

    /** @test */
    public function income_sources_listings_dont_contain_system_added_sources()
    {
        $user = $this->createUser();

        $system_source = IncomeSource::factory()->create([
            'is_system' => 1
        ]);

        $this->actingAs($user)
            ->getJson('api/income_sources')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonMissing(['is_system' => 1]);
    }
}
