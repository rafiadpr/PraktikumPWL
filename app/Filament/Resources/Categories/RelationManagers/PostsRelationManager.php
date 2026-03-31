<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

/**
 * PostsRelationManager
 *
 * Relation Manager ini memungkinkan pengelolaan Post langsung dari halaman edit Category.
 * Dengan ini, kita bisa melihat, membuat, mengedit, dan menghapus Post
 * tanpa harus berpindah ke halaman PostResource.
 */
class PostsRelationManager extends RelationManager
{
    /**
     * Nama relasi yang terdaftar di model Category.
     * Harus sama persis dengan nama method hasMany di model Category.
     */
    protected static string $relationship = 'posts';

    /**
     * Form schema untuk membuat/mengedit Post dari dalam Relation Manager.
     * Form ini lebih sederhana karena category_id otomatis diisi oleh Filament
     * berdasarkan Category yang sedang diedit.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                // Input judul post — wajib diisi, maksimal 255 karakter
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                // Input slug — wajib diisi, unik per post (abaikan record saat edit)
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpan(1),
            ]);
    }

    /**
     * Konfigurasi tabel yang menampilkan daftar Post milik Category ini.
     * Menampilkan kolom title, slug, dan tanggal dibuat.
     */
    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // Kolom judul post — bisa dicari dan diurutkan
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                // Kolom slug — bisa dicari dan diurutkan
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),

                // Kolom tanggal dibuat — format datetime, bisa diurutkan
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                // Tombol "Create" di header tabel untuk membuat Post baru
                // category_id otomatis terisi sesuai Category yang sedang diedit
                CreateAction::make(),
            ])
            ->recordActions([
                // Tombol edit dan hapus per baris
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // Aksi bulk delete untuk menghapus banyak post sekaligus
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
