<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CityService
{
    private const FILE_PATH = 'cities.json';

    public function __construct()
    {
        // Ensure file exists
        if (!Storage::exists(self::FILE_PATH)) {
            Storage::put(self::FILE_PATH, json_encode([]));
        }
    }

    /**
     * Get all cities.
     */
    public function getAll(): array
    {
        return $this->load();
    }

    /**
     * Find a city by ID.
     */
    public function find(string $id): ?array
    {
        $cities = $this->load();
        return $cities[$id] ?? null;
    }

    /**
     * Store a new city.
     */
    public function create(array $data): array
    {
        $cities = $this->load();
        
        $id = (string) Str::uuid();

        $city = [
            'id' => $id,
            'name' => $data['name'],
            'country' => $data['country'],
            'created_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ];

        $cities[$id] = $city;
        
        $this->save($cities);

        return $city;
    }

    /**
     * Update an existing city.
     */
    public function update(string $id, array $data): ?array
    {
        $cities = $this->load();

        if (!isset($cities[$id])) {
            return null;
        }

        $city = $cities[$id];

        $city['name'] = $data['name'] ?? $city['name'];
        $city['country'] = $data['country'] ?? $city['country'];
        $city['updated_at'] = now()->toIso8601String();

        $cities[$id] = $city;

        $this->save($cities);

        return $city;
    }

    /**
     * Delete a city.
     */
    public function delete(string $id): bool
    {
        $cities = $this->load();

        if (!isset($cities[$id])) {
            return false;
        }

        unset($cities[$id]);

        $this->save($cities);

        return true;
    }

    /**
     * Load cities from the JSON file.
     */
    private function load(): array
    {
        $content = Storage::get(self::FILE_PATH);
        return json_decode($content, true) ?? [];
    }

    /**
     * Save cities to the JSON file.
     */
    private function save(array $cities): void
    {
        Storage::put(self::FILE_PATH, json_encode($cities, JSON_PRETTY_PRINT));
    }
}
