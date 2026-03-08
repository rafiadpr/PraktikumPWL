<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                // BAGIAN KIRI (2/3 Kolom)
                Group::make()
                    ->schema([
                        Section::make('Konten Utama')
                            ->description('Isi detail postingan Anda di sini')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->minLength(5) // Validasi minimal 5 karakter
                                    ->validationMessages([
                                        'required' => 'Judul postingan tidak boleh kosong.',
                                        'min' => 'Judul terlalu pendek, minimal harus 5 karakter.',
                                    ]) // Custom message 1
                                    ->columnSpan(2),

                                TextInput::make('slug')
                                    ->required()
                                    ->minLength(3) // Validasi minimal 3 karakter
                                    ->unique(ignoreRecord: true) // Slug unik (mengabaikan data saat edit)
                                    ->validationMessages([
                                        'unique' => 'Slug ini sudah digunakan, silakan cari nama lain.',
                                        'min' => 'Slug minimal terdiri dari 3 karakter.',
                                    ]) // Custom message 2
                                    ->columnSpan(1),

                                Select::make('category_id')
                                    ->required() // Category wajib dipilih
                                    ->relationship("category", "name")
                                    ->preload()
                                    ->columnSpanFull(),

                                MarkdownEditor::make('content')
                                    ->columnSpanFull(),
                            ])->columns(3),
                    ])->columnSpan(2),

                // BAGIAN KANAN (1/3 Kolom)
                Group::make()
                    ->schema([
                        Section::make('Media')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                FileUpload::make("image")
                                    ->required() // Image wajib diupload
                                    ->disk("public")
                                    ->directory("posts"),
                            ]),

                        Section::make('Meta & Status')
                            ->icon('heroicon-m-cog-6-tooth')
                            ->schema([
                                TagsInput::make('tags'),
                                Checkbox::make('published'),
                                DateTimePicker::make('published_at'),
                            ]),
                    ])->columnSpan(1),
            ]);
    }
}