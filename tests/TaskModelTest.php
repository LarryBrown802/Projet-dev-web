<?php
namespace App\Tests;
use PHPUnit\Framework\TestCase;
use App\Models\TaskModel;
use App\Models\FileDatabase;

class TaskModelTest extends TestCase {

    public function testGetToDoTasks() {
        
        $connection = $this->createStub(FileDatabase::class);
        $connection->method('getAllRecords')->willReturn([
            ['task' => 'test task 1', 'status' => 'todo'],
            ['task' => 'test task 2', 'status' => 'todo'],
            ['task' => 'test task 3', 'status' => 'done'],
        ]);

        $model = new TaskModel($connection);
        $tasks = $model->getToDoTasks();

        $this->assertCount(2, $tasks);
        $this->assertContains( ['task' => 'test task 1', 'status' => 'todo'], $tasks);
        $this->assertContains( ['task' => 'test task 2', 'status' => 'todo'], $tasks);
    }

    public function testAddTask() {
        
        $connection = $this->createMock(FileDatabase::class);
        $connection->method('insertRecord')->willReturn(true);

        // TODO : We expect the insertRecord method to be called once with the following data array: ['task' => 'test task', 'status' => 'todo']

        $model = new TaskModel($connection);
        $result = $model->addTask('test task');

        $this->assertTrue($result);
    }

    public function testGetDoneTasks() {
        /* Add your code here */
        $this->assertTrue(true);
    }

}