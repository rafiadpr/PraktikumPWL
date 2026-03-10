<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable(),
                TextColumn::make('sku'),
                TextColumn::make('price'),
                TextColumn::make('stock'),
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge() // Mengaktifkan tampilan badge
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger') // Hijau jika aktif, Merah jika tidak
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Non-Aktif'), // Mengubah teks 1/0 menjadi kata-kata

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
