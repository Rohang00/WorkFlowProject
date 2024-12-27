<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $project;
    protected $task;

    protected function setUp(): void
    {
        // Arrange (Global setup for all tests)
        parent::setUp();

        $this->user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->project = Project::factory()->create();

        $this->token = $this->getAuthToken($this->user);

        // Create a task with the correct field names
        $this->task = Task::factory()->create([
            'assigned_to' => $this->user->id,  // This matches the migration field name
            'completed_by' => $this->user->id,
            'created_by' => $this->user->id,
            'project_id' => $this->project->id,
            'status' => 0,
            'due_at' => now()->addDays(7),
        ]);
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
    public function can_create_task()
    {
        // Arrange
        $taskData = [
            'title' => 'New Task Title',
            'description' => 'Task description here.',
            'assigned_to' => $this->user->id,
            'project_id' => $this->project->id,
            'status' => 0,
            'due_date' => now()->addDays(7)->toDateString(),
            'created_by' => $this->user->id,
        ];

        // Act
        $response = $this->postJson('/api/tasks', $taskData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

         // Assert
        $response->assertStatus(201) // Verifies creation success
            ->assertJsonFragment([
                'message' => 'Task Created Successfully',
            ])
            ->assertJsonStructure([ // Verifies response structure
                'message',
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_at',
                    'completed_at',
                    'assigned_to',
                    'project',
                ]
            ]);

         // Additional database assertion
        $this->assertDatabaseHas('tasks', [ // Verifies data was store
            'title' => 'New Task Title',
            'description' => 'Task description here.',
            'assigned_to' => $this->user->id,
            'project_id' => $this->project->id,
            'status' => 0,
        ]);
    }

    /** @test */
    public function can_view_task_by_id()
    {
        // Arrange
        $task = Task::factory()->create([
            'assigned_to' => $this->user->id,  // Using correct column name
            'project_id' => $this->project->id,
            'created_by' => $this->user->id,
            'status' => 0,
        ]);

         // Act
        $response = $this->getJson(route('tasks.show', $task->id), [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

         // Assert
        $response->assertOk()
            ->assertJsonStructure([ // Verifies response structure with relationships
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_at',
                    'completed_at',
                    'assigned_to' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'project' => [
                        'id',
                        'title',
                        'slug',
                        'description',
                    ],
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /** @test */
    public function can_delete_task()
    {
        // Arrange
        $task = Task::factory()->create([
            'assigned_to' => $this->user->id,
            'project_id' => $this->project->id,
            'created_by' => $this->user->id,
        ]);

        // Act
        $response = $this->deleteJson(route('tasks.destroy', $task->id), [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

         // Assert
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task Deleted Successfully',
            ]);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]); // Verifies soft delete
    }

    /** @test */
    public function can_update_task()
    {
         // Arrange
        $task = Task::factory()->create([
            'assigned_to' => $this->user->id,
            'project_id' => $this->project->id,
            'created_by' => $this->user->id,
        ]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description',
            'status' => 1,
            'due_at' => now()->addDays(5)->toDateString(),
        ];

        // Act
        $response = $this->putJson(route('tasks.update', $task->id), $updatedData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

         // Assert
        $response->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Task Updated Successfully',
            ]);

        $this->assertDatabaseHas('tasks', [  // Verifies update in database
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'description' => 'Updated Task Description',
            'status' => 1,
        ]);
    }

    /** @test */
    public function can_view_all_tasks_with_pagination()
    {
        // Create additional tasks, Arrange
        Task::factory()->count(3)->create([
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
            'created_by' => $this->user->id,
        ]);

        // Act
        $response = $this->getJson('/api/tasks?per_page=2', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Assert
        $response->assertOk()
            ->assertJsonStructure([   // Verifies pagination structure
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'due_at',
                        'completed_at',
                        'assigned_to' => [
                            'id',
                            'name',
                            'email',
                        ],
                        'project' => [
                            'id',
                            'title',
                            'slug',
                            'description',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ]);

        $response->assertJsonCount(2, 'data');  // Verifies pagination limit
    }
}
