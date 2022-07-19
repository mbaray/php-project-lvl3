<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UrlControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();
        $this->url = 'https://' . $this->faker->unique()->domainName;
        $this->id = DB::table('urls')->insertGetId([
            'name' => $this->url,
            'created_at' => Carbon::now()
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }

    public function testUrlsIndex()
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testShow()
    {
        $response = $this->get(route('urls.show', ['id' => $this->id]));
        $response->assertOk();
    }

    public function testStore()
    {
        $response = $this->post(route('urls.store'), ['url' => $this->url]);
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', [
            'id' => $this->id,
            'name' => $this->url
        ]);
    }

    public function testStoreCheck()
    {
        Http::fake([
            '*' => Http::response(
                "<h1>Test h1</h1>
                <title>Test title</title>
                <meta name=description content='Test description'",
                200
            )
        ]);

        $response = $this->post(route('urls.store.check', $this->id), ['url' => $this->url]);
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', [
            'url_id' => $this->id,
            'status_code' => '200',
            'h1' => 'Test h1',
            'title' => 'Test title',
            'description' => 'Test description'
        ]);
    }
}
