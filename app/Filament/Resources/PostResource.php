<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Doctrine\DBAL\Schema\Column;
use Filament\Actions\Exports\ExportColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('content')->required(),
                Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                ])
                ->default('draft')
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()
            ])
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        \pxlrbt\FilamentExcel\Columns\Column::make('title'),
                        \pxlrbt\FilamentExcel\Columns\Column::make('status')
                    ])
                ])
            ]);
    }

    public static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published'
                ]),
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from'),
                    Forms\Components\DatePicker::make('created_until')
                ])
                ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                    return $query
                        ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                }),
        ];
    }

    public static function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('publish')
                ->label('Publish')
                ->action(fn(Post $record) => $record->update(['status' => 'published']))
                ->requiresConfirmation()
            ->visible(fn (Post $record) => $record->status !== 'published')
            ->color('success')
            ->icon('heroicon-o-check'),
            Tables\Actions\Action::make('viewComments')
                ->label('View comments')
                ->icon('heroicon-m-chat-bubble-left')
                ->url(fn ($record) => static::getUrl('viewComments', ['record' => $record]))
        ];
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'viewComments' => Pages\ViewPostComments::route('/{record}/comments')
        ];
    }

    public static function canViewAny(): bool
    {
       return auth()->user()?->hasAnyRole(['admin', 'editor']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole(['admin', 'editor']) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole(['admin']) ?? false;
    }

}
