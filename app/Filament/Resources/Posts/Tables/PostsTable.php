<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ColorColumn::make('color')
                    ->toggleable(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->toggleable(),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('published')
                    ->label('Published')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Published At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at')
                            ->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at'],
                                fn(Builder $query, $date): Builder =>
                                $query->whereDate('created_at', '=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_at'] ?? null) {
                            $indicators['created_at'] = 'Tanggal: ' . Carbon::parse($data['created_at'])->translatedFormat('d M Y');
                        }

                        return $indicators;
                    }),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->preload()
                    ->label('Kategori'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
                ReplicateAction::make()
                    ->icon('heroicon-o-document-duplicate'),
                Action::make('status')
                    ->label('Status Change')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Status Published')
                    ->modalDescription('Apakah Anda yakin ingin mengubah status published postingan ini?')
                    ->form([
                        Checkbox::make('published')
                            ->label('Published')
                            ->default(fn ($record) => $record->published),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'published' => $data['published'],
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
