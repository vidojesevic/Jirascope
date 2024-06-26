<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends Resource
{

    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Work';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('git_repository'),
                Forms\Components\TextInput::make('end_date')
                    ->required()
                    ->type('date'),
                Forms\Components\Textarea::make('description')
                    ->rows(5),
                Forms\Components\FileUpload::make('project_image')
                    ->directory('project-image')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('project_image')
                    ->label('Project image'),
//                    ->defaultImageUrl(function (Project $project): string {
//                        return storage_path($project->project_image);
//                    }),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('git_repository'),
                Tables\Columns\TextColumn::make('client')
                    ->getStateUsing(function (Project $project) {
                        return $project->client()->pluck('name');
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('team')
                ->getStateUsing(function (Project $project) {
                    return $project->team()->pluck('name');
                }),
                Tables\Columns\TextColumn::make('description')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
