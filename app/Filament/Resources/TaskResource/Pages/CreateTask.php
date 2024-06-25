<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Image;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $imagePaths = $data['images'] ?? [];
        unset($data['images']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $task = $this->record;
        $imagePaths = $this->form->getState()['images'] ?? [];

        foreach ($imagePaths as $path) {
            $image = Image::create(['path' => $path]);
            $task->images()->save($image);
        }
    }
}
