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

    protected const BASE_URL = '/api/income_sources';

    public function __construct()
    {
        parent::__construct();

        $this->faker = Faker::create();
    }

    /** @test  */
    public function unauthenticated_users_cannot_get_income_sources()
    {
        $this->getJson(self::BASE_URL)
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
            ->getJson(self::BASE_URL)
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
            ->getJson(self::BASE_URL)
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
            ->getJson(self::BASE_URL)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonMissing(['is_system' => 1]);
    }

    /** @test */
    public function income_sources_cannot_be_added_without_name_filled()
    {
        $this->actingAs($this->createUser())
            ->postJson(self::BASE_URL, $this->getPayload(['name']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function income_sources_cannot_be_added_without_color_filled()
    {
        $this->actingAs($this->createUser())
            ->postJson(self::BASE_URL, $this->getPayload(['color']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function duplicate_name_income_sources_cannot_be_added_by_same_user()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $data = $this->getPayload();
        $data['name'] = $income_source->name;

        $this->actingAs($user)
            ->postJson(self::BASE_URL, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function duplicate_color_income_sources_cannot_be_added_by_same_user()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $data = $this->getPayload();
        $data['color'] = $income_source->color;

        $this->actingAs($user)
            ->postJson(self::BASE_URL, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function duplicate_name_income_sources_from_different_users_can_be_added()
    {
        $income_source = IncomeSource::factory()->create([
            'user_id' => $this->createUser()->id
        ]);

        $data = $this->getPayload();
        $data['name'] = $income_source->name;

        $this->actingAs($this->createUser())
            ->postJson(self::BASE_URL, $data)
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function duplicate_color_income_sources_from_different_users_can_be_added()
    {
        $income_source = IncomeSource::factory()->create([
            'user_id' => $this->createUser()->id
        ]);

        $data = $this->getPayload();
        $data['color'] = $income_source->color;

        $this->actingAs($this->createUser())
            ->postJson(self::BASE_URL, $data)
            ->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function users_cannot_update_income_sources_added_by_others()
    {
        $income_source = IncomeSource::factory()->create([
            'user_id' => $this->createUser()->id
        ]);

        $this->actingAs($this->createUser())
            ->putJson(self::BASE_URL . '/' . $income_source->id, $this->getPayload())
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function income_sources_cannot_be_updated_without_name_filled()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->putJson(self::BASE_URL . '/' . $income_source->id, $this->getPayload(['name']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function income_sources_cannot_be_updated_without_color_filled()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->putJson(self::BASE_URL . '/' . $income_source->id, $this->getPayload(['color']))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function income_source_cannot_be_updated_with_name_similar_to_existing()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $other_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $data = $this->getPayload();
        $data['name'] = $other_source->name;

        $this->actingAs($user)
            ->putJson(self::BASE_URL . '/' . $income_source->id, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function income_source_cannot_be_updated_with_color_similar_to_existing()
    {
        $user = $this->createUser();

        $income_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $other_source = IncomeSource::factory()->create([
            'user_id' => $user->id
        ]);

        $data = $this->getPayload();
        $data['color'] = $other_source->color;

        $this->actingAs($user)
            ->putJson(self::BASE_URL . '/' . $income_source->id, $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function getPayload($exemptions = [])
    {
        $data = [];

        if (!in_array('name', $exemptions)) {
            $data['name'] = $this->faker->name;
        }

        if (!in_array('color', $exemptions)) {
            $data['color'] =  bin2hex(openssl_random_pseudo_bytes(3));
        }

        return $data;
    }
}
