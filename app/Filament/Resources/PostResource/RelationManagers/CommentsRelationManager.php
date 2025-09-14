<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $recordTitleAttribute = 'content';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('content')->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'spam' => 'Spam'
                    ])
                ->default('pending')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')->limit(50),
                Tables\Columns\TextColumn::make('author.name')->label('Author'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('replies_count')->counts('replies')->label('Replies'),
            ])
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ]);
    }

    public function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'spam' => 'Spam'
                ]),
        ];
    }

    public function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('approve')
                ->action(fn (Comment $record) => $record->update(['status' => 'approved']))
                ->requiresConfirmation()
                ->color('success'),
            Tables\Actions\Action::make('reply')
                ->form([
                    Forms\Components\Textarea::make('content')
                    ->required()
                    ->label('Reply content')
                ])
                ->action(function (Comment $record, array $data) {
                    Comment::create([
                        'post_id' => $record->post_id,
                        'parent_id' => $record->id,
                        'user_id' => auth()->id(),
                        'content' => $data['content'],
                        'status' => 'pending'
                    ]);
                })
                ->requiresConfirmation()
                ->color('primary')
                ->icon('heroicon-s-arrow-uturn-left')
        ];
    }
}
