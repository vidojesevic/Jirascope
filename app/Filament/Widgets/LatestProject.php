<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestProject extends BaseWidget
{
    /**
     * @var int|null
     */
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProjectResource::getEloquentQuery())
            ->defaultPaginationPageOption(1)
            ->defaultSort('created_at', 'updated_at')
            ->columns([
                Tables\Columns\ImageColumn::make('project_image')
                    ->label('Project image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('git_repository'),
                Tables\Columns\TextColumn::make('client')
                    ->getStateUsing(function (Project $project) {
                        return $project->client()->pluck('name');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50)
            ]);
    }
}
