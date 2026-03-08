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
            ->columns(3) // Mengatur grid utama form menjadi 3 kolom
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
                                    ->minLength(5) // Tugas validasi minimal 5 karakter
                                    ->columnSpan(2),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true) // Tugas validasi slug unik
                                    ->columnSpan(1),
                                Select::make('category_id')
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
                                FileUpload::make("image") // Sudah diperbaiki menjadi huruf kecil
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