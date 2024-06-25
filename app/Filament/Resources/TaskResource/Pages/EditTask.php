<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Image;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $imagePaths = $data['images'] ?? [];
        unset($data['images']);
        return $data;
    }

    protected function afterSave(): void
    {
        $task = $this->record;
        $imagePaths = $this->form->getState()['images'] ?? [];

        $task->images()->delete(); // Remove existing associations

        foreach ($imagePaths as $path) {
            $image = Image::firstOrCreate(['path' => $path]);
            $task->images()->save($image);
        }
    }
}
