<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;

/**
 * TagsRelationManager
 *
 * Relation Manager untuk relasi Many-to-Many antara Post dan Tag.
 * Menggunakan fitur Attach/Detach agar bisa menghubungkan dan
 * melepaskan Tag yang sudah ada tanpa harus membuat yang baru.
 * Ini berbeda dengan Create/Delete yang membuat/menghapus record Tag itu sendiri.
 */
class TagsRelationManager extends RelationManager
{
    /**
     * Nama relasi yang terdaftar di model Post.
     * Harus sama persis dengan nama method belongsToMany di model Post.
     */
    protected static string $relationship = 'tags';

    /**
     * Form schema untuk membuat/mengedit Tag dari dalam Relation Manager.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * Konfigurasi tabel yang menampilkan daftar Tag yang terhubung ke Post ini.
     * Menggunakan AttachAction dan DetachAction untuk relasi Many-to-Many.
     */
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    // ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                // Tombol "Create" untuk membuat Tag baru dan langsung menghubungkan ke Post
                CreateAction::make(),
                // Tombol "Attach" untuk menghubungkan Tag yang sudah ada ke Post
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->recordActions([
                EditAction::make(),
                // Tombol "Detach" untuk melepaskan hubungan Tag dari Post
                // (Tag tidak dihapus, hanya hubungannya yang dilepas)
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     // Bulk detach untuk melepaskan banyak hubungan sekaligus
                //     DetachBulkAction::make(),
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
