<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        //Arrange: Create a base user and get authentication token
        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->token = $this->getAuthToken($this->user);
    }

    private function getAuthToken($user)
    {
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        return $response->json('token');
    }

    /** @test */
    public function can_create_project()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();

        $data = [
            'title' => 'Test Project',
            'slug' => 'test-project',
            'description' => 'This is a test project description.',
            'deadline' => now()->addDays(30)->toDateString(),
            'created_by' => $user->id,
            'org_id' => $organization->id,
        ];

        $response = $this->postJson(route('projects.store'), $data, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'slug',
                'description',
                'created_by',
                'deadline',
                'organization',
                'tasks',
            ],
        ]);

        $this->assertDatabaseHas('projects', [
            'title' => 'Test Project',
            'slug' => 'test-project',
            'description' => 'This is a test project description.',
            'deadline' => now()->addDays(30)->toDateString(),
            'created_by' => $user->id,
            'org_id' => $organization->id,
        ]);
    }

    /** @test */
    public function can_view_project()
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create([
            'created_by' => $user->id,
            'org_id' => $organization->id,
        ]);

        $project->load(['creator', 'organization', 'tasks']);

        $response = $this->getJson(route('projects.show', $project->id), [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'slug',
                'description',
                'created_by',
                'deadline',
                'organization',
                'tasks',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $project->id,
                'title' => $project->title,
                'slug' => $project->slug,
                'description' => $project->description,
                'created_by' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    //other user fields as per the UserResource
                ],
                'organization' => [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    //other organization fields as per the OrganizationResource
                ],
                'tasks' => [],  //modify this based on whether you want to have tasks for this project in the test
            ]
        ]);
    }

    /** @test */
    public function can_view_all_projects_with_pagination()
    {
        // Create some projects in the database
        $projects = Project::factory()->count(10)->create();

        // Send a GET request to the /api/projects endpoint with the query parameter per_page=1
        $response = $this->getJson('/api/projects?per_page=1', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Assert that the response has a status code of 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the correct pagination structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'description',
                    'created_by',
                    'deadline',
                    'organization',
                    'tasks',
                ],
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'per_page',
                'to',
                'total',
            ],
        ]);

        // Assert that only 1 project is returned in the current page
        $response->assertJsonCount(1, 'data');

        // Assert that the pagination information is correct
        $response->assertJsonFragment([
            'per_page' => 1,
            'current_page' => 1,
        ]);
    }

    /** @test */
    public function can_delete_project()
    {
        // Create a project to delete
        $project = Project::factory()->create();

        // Send a DELETE request to the /api/projects/{project} endpoint
        $response = $this->deleteJson(route('projects.destroy', $project->id), [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Assert that the response has a status code of 200 (OK)
        $response->assertStatus(202);

        // Assert that the response contains the success message
        $response->assertJson([
            'message' => 'Project deleted successfully',
        ]);

        // Assert that the project is soft-deleted (i.e., deleted_at is not null)
        $project = Project::withTrashed()->find($project->id);  // This retrieves soft-deleted projects
        $this->assertNotNull($project->deleted_at);  // Check that the deleted_at field is not null
    }

    /** @test */
    public function can_update_project()
    {
        // Step 1: Create a project and an organization
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $project = Project::factory()->create(['org_id' => $organization->id, 'created_by' => $user->id]);

        // Data to update the project
        $updatedData = [
            'title' => 'Updated Project Title',
            'slug' => 'updated-project',
            'description' => 'Updated project description.',
            'deadline' => now()->addDays(45)->toDateString(),
            'org_id' => $organization->id,  // Ensure the same organization is assigned
        ];

        // Step 2: Send a PUT request to update the project
        $response = $this->putJson(route('projects.update', $project->id), $updatedData, [
            'Authorization' => 'Bearer ' . $this->token, // Assuming you have a valid token
        ]);

        // Step 3: Assert that the response has a status code of 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the success message from the controller
        $response->assertJson([
            'message' => 'Project Updated Successfully',
        ]);

        // Step 4: Assert the structure of the response
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'slug',
                'description',
                'created_by',
                'deadline',
                'organization' => [
                    'id',
                    'name',
                ],
                'tasks',  // Assuming tasks are included
            ]
        ]);

        // Step 5: Fetch the updated project from the database and verify the update
        $project->refresh();  // Reload the project from the database
        $this->assertEquals('Updated Project Title', $project->title);
        $this->assertEquals('updated-project', $project->slug);
        $this->assertEquals('Updated project description.', $project->description);
        $this->assertEquals(now()->addDays(45)->toDateString(), $project->deadline);
    }

    
}
