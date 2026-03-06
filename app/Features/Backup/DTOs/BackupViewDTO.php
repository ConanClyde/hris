<?php

declare(strict_types=1);

namespace App\Features\Backup\DTOs;

use App\Features\Backup\Models\Backup;

readonly class BackupViewDTO
{
    public function __construct(
        public int $id,
        public string $filename,
        public string $createdAt,
        public ?string $notes,
        public ?string $size,
        public ?string $status,
        public ?string $completedAt,
    ) {}

    public static function fromModel(Backup $backup): self
    {
        return new self(
            id: (int) $backup->id,
            filename: $backup->filename,
            createdAt: $backup->created_at?->toISOString() ?? '',
            notes: $backup->notes,
            size: $backup->size_formatted,
            status: $backup->status,
            completedAt: $backup->completed_at?->toISOString(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'created_at' => $this->createdAt,
            'notes' => $this->notes,
            'size' => $this->size,
            'status' => $this->status,
            'completed_at' => $this->completedAt,
        ];
    }
}
