<?php

namespace Database\Factories;

use App\Models\SiteAnnouncement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteAnnouncement>
 */
class SiteAnnouncementFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'body' => $this->faker->paragraph(2),
            'type' => $this->faker->randomElement(SiteAnnouncement::TYPES),
            'audiences' => [SiteAnnouncement::AUDIENCE_PUBLIC],
            'is_active' => true,
            'is_dismissible' => true,
            'min_view_seconds' => 0,
            'cta_label' => null,
            'cta_url' => null,
            'starts_at' => null,
            'ends_at' => null,
            'created_by' => null,
        ];
    }

    public function inactive(): self
    {
        return $this->state(['is_active' => false]);
    }

    public function modal(int $minSeconds = 3): self
    {
        return $this->state(['min_view_seconds' => $minSeconds]);
    }

    /**
     * @param  list<string>  $audiences
     */
    public function targeting(array $audiences): self
    {
        return $this->state(['audiences' => $audiences]);
    }

    public function expired(): self
    {
        return $this->state([
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDay(),
        ]);
    }

    public function scheduled(): self
    {
        return $this->state([
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addWeek(),
        ]);
    }
}
