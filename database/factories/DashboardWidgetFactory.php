<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DashboardWidget>
 */
class DashboardWidgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $widgetTypes = [
            'stats_card',
            'recent_activities',
            'quick_actions',
            'calendar',
            'notifications',
            'chart',
            'todo_list',
            'weather'
        ];

        $configurations = [
            'stats_card' => [
                'title' => $this->faker->randomElement(['Total Students', 'Total Teachers', 'Active Classes', 'Current GPA']),
                'metric' => $this->faker->randomElement(['students_count', 'teachers_count', 'classes_count', 'gpa']),
                'color' => $this->faker->randomElement(['primary', 'success', 'info', 'warning'])
            ],
            'recent_activities' => [
                'limit' => $this->faker->numberBetween(5, 15),
                'show_timestamps' => $this->faker->boolean(80)
            ],
            'quick_actions' => [
                'actions' => $this->faker->randomElements(['create_school', 'manage_users', 'add_student', 'add_teacher', 'mark_attendance', 'enter_grades'], $this->faker->numberBetween(2, 4))
            ],
            'calendar' => [
                'view' => $this->faker->randomElement(['month', 'week', 'day']),
                'show_events' => $this->faker->boolean(90)
            ],
            'notifications' => [
                'limit' => $this->faker->numberBetween(3, 10),
                'auto_refresh' => $this->faker->boolean(70)
            ],
            'chart' => [
                'type' => $this->faker->randomElement(['line', 'bar', 'pie', 'doughnut']),
                'data_source' => $this->faker->randomElement(['attendance', 'grades', 'enrollment'])
            ]
        ];

        $widgetType = $this->faker->randomElement($widgetTypes);

        return [
            'user_id' => \App\Models\User::factory(),
            'widget_type' => $widgetType,
            'position' => $this->faker->numberBetween(0, 10),
            'configuration' => $configurations[$widgetType] ?? [],
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
