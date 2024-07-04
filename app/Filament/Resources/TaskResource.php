<?php

namespace App\Filament\Resources;

use App\Enums\Level;
use App\Enums\Type;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Forms\Components\Wizard;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\Section::make('Task Details')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->columns(2)
                            ->hiddenLabel()
                            ->placeholder('Task name')
                            ->required(),
                        Forms\Components\TextInput::make('git_branch')
                            ->hiddenLabel()
                            ->placeholder('Git branch'),
                        Forms\Components\Select::make('project_id')
                            ->placeholder('Select the project')
                            ->hiddenLabel()
                            ->required()
                            ->columns(2)
                            ->relationship('project', 'name'),
                        Forms\Components\Select::make('parent_id')
                            ->placeholder('Select the parent task')
                            ->columns(2)
                            ->hiddenLabel()
                            ->options(function (Task $task) {
                                $projectId = $task->project_id;

                                return Task::where('project_id', $projectId)->pluck('name', 'id');
                            }),
                        Forms\Components\Select::make('user_id')
                            ->hiddenLabel()
                            ->placeholder('Assign to')
                            ->options(function () {
                                $currentUser = auth()->user();
                                $teamIds = $currentUser->team->pluck('id');
                                return User::whereHas('team', function ($query) use ($teamIds) {
                                    $query->whereIn('teams.id', $teamIds);
                                })->get()->pluck('full_name_and_role', 'id');
                            }),
                    ]),
                Forms\Components\Section::make('Task Attributes')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\TextInput::make('start_date')
                            ->columns(1)
                            ->prefix('Start date')
                            ->hiddenLabel()
                            ->required()
                            ->type('date'),
                        Forms\Components\TextInput::make('end_date')
                            ->columns(1)
                            ->hiddenLabel()
                            ->prefix('End date')
                            ->required()
                            ->type('date'),
                        Forms\Components\Select::make('level')
                            ->hiddenLabel()
                            ->placeholder('Select level')
                            ->options(Level::class),
                        Forms\Components\Select::make('type')
                            ->hiddenLabel()
                            ->placeholder('Select type of task')
                            ->options(Type::class),
                        Forms\Components\Select::make('status')
                            ->hiddenLabel()
                            ->placeholder('Select an status')
                            ->options([
                                'To do' => 'To do',
                                'In progress' => 'In progress',
                                'Code review' => 'Code review',
                                'Internal testing' => 'Internal testing',
                                'Done' => 'Done'
                            ])
                            ->native(false),
                    ]),
                Forms\Components\Section::make('Additional Information')
                    ->columns(2)
                    ->schema([
                    Forms\Components\MarkdownEditor::make('description'),
                    Forms\Components\FileUpload::make('task_image')
                ])
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
                    ->icon('heroicon-s-clipboard-document-check')
                    ->badge()
                    ->colors([
                        'primary' => 'To do',
                        'warning' => 'In progress',
                        'info' => 'Code review',
                        'success' => 'Internal testing',
                        'success' => 'Done',
                    ]),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->getStateUsing(fn (Task $task) => $task->project()->pluck('name'))
                    ->searchable('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User')
                    ->formatStateUsing(function (Task $task) {
                        return $task->user()->pluck('name')[0] . ' ' . $task->user()->pluck('surname')[0];
                    }),
                Tables\Columns\TextColumn::make('git_branch')
                    ->formatStateUsing(
                        function ($state) {
                            $prefixes = [
                                'https://github.com/',
                                'https://bitbucket.org/',
                                'https://gitlab.com/'
                            ];

                            foreach ($prefixes as $prefix) {
                                if (str_starts_with($state, $prefix)) {
                                    return str_replace($prefix, '', $state);
                                }
                            }

                            return $state;
                        }
                    ),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\TextColumn::make('end_date'),
            ])
            ->filters([
                Tables\Filters\Filter::make('my_jobs')
                    ->query(fn ($query) => $query->where('user_id', Auth::user()->id)),
                // TODO: Filter by status
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
