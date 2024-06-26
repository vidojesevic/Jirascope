<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Exception;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Work';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name'),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('git_branch'),
                Forms\Components\Select::make('status')
                    ->default('To do')
                    ->options([
                        'To do' => 'To do',
                        'In progress' => 'In progress',
                        'Code review' => 'Code review',
                        'Internal testing' => 'Internal testing',
                        'Done' => 'Done'
                    ])
                    ->native(false),
                Forms\Components\TextInput::make('start_date')
                    ->required()
                    ->type('date'),
                Forms\Components\TextInput::make('end_date')
                    ->required()
                    ->type('date'),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(function () {
                        $currentUser = auth()->user();
                        $teamIds = $currentUser->team->pluck('id');
                        return User::whereHas('team', function ($query) use ($teamIds) {
                            $query->whereIn('teams.id', $teamIds);
                        })->get()->pluck('name', 'id');
                    }),
                Forms\Components\FileUpload::make('task_image')
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->colors([
                        'primary' => 'To do',
                        'warning' => 'In progress',
                        'info' => 'Code review',
                        'success' => 'Internal testing',
                        'success' => 'Done',
                    ]),
                Tables\Columns\TextColumn::make('project_id')
                    ->label('Project')
                    ->getStateUsing(fn (Task $task) => $task->project()->pluck('name'))
                    ->searchable('name'),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User')
                    ->formatStateUsing(fn (Task $task) => $task->user()->pluck('name')[0]),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('git_branch'),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\TextColumn::make('end_date'),
            ])
            ->filters([
                Tables\Filters\Filter::make('all')
                    ->query(fn ($query) => $query),
                Tables\Filters\Filter::make('my_jobs')
                    ->query(fn ($query) => $query->where('user_id', Auth::user()->id))
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Task $task, User $user) => $task->user_id === $user->id),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
