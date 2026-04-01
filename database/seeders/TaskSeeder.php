<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        Task::create([
            'title' => 'Finish Laravel project',
            'priority' => 'high',
            'due_date' => now()->addDays(1),
            'status' => 'pending'
        ]);

        Task::create([
            'title' => 'Buy groceries',
            'priority' => 'medium',
            'due_date' => now()->addDays(2),
            'status' => 'in_progress'
        ]);

        Task::create([
            'title' => 'Clean room',
            'priority' => 'low',
            'due_date' => now(),
            'status' => 'done'
        ]);

        Task::create([
            'title' => 'Prepare presentation',
            'priority' => 'high',
            'due_date' => now(),
            'status' => 'done'
        ]);
    }
}
