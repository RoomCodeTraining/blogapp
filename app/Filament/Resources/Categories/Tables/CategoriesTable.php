<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->posts()->exists()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $nonDeletable = $records->filter(fn ($record) => $record->posts()->exists());

                            if ($nonDeletable->isNotEmpty()) {
                                Notification::make()
                                    ->title('Certaines catégories n\'ont pas été supprimées')
                                    ->body('Les catégories associées à des posts ne peuvent pas être supprimées.')
                                    ->danger()
                                    ->send();

                                // Ne supprimer que celles qui sont éligibles
                                $records = $records->reject(fn ($record) => $record->posts()->exists());
                            }

                            $records->each->delete();

                            if ($records->isNotEmpty()) {
                                Notification::make()
                                    ->title('Catégories supprimées')
                                    ->success()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }
}
