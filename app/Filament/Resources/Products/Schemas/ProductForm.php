<?php
namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Actions\Action;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                // STEP 1: Info Dasar
                Wizard\Step::make('Product Info')
                    ->description('Masukkan detail nama dan SKU produk') // Tugas: Deskripsi
                    ->icon('heroicon-m-information-circle') // Tugas: Icon
                    ->schema([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('sku')->label('SKU')->required()->unique(ignoreRecord: true),
                        Textarea::make('description')->columnSpanFull(),
                    ]),

                // STEP 2: Harga & Stok
                Wizard\Step::make('Pricing & Stock')
                    ->description('Atur harga jual dan jumlah ketersediaan stok') // Tugas: Deskripsi
                    ->icon('heroicon-m-banknotes') // Tugas: Icon
                    ->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->prefix('IDR')
                            ->required()
                            ->minValue(1) // Tugas: Validasi Harga > 0
                            ->validationMessages(['min' => 'Harga harus lebih besar dari 0']),
                        TextInput::make('stock')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                    ])->columns(2),

                // STEP 3: Media & Status
                Wizard\Step::make('Media & Status')
                    ->description('Upload foto produk dan atur visibilitas') // Tugas: Deskripsi
                    ->icon('heroicon-m-photo') // Tugas: Icon
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->disk("public")
                            ->directory('products')
                            ->columnSpanFull(),
                        Toggle::make('is_active')->label('Aktifkan Produk')->default(true),
                        Toggle::make('is_featured')->label('Produk Unggulan'),
                    ]),
            ])
                ->columnSpanFull() // Membuat Wizard mengambil lebar penuh layar
                // ->submitAction(view('filament.admin.resources.products.submit-button')), // Mengatur tombol simpan di akhir
                ->submitAction(
                    Action::make('save')
                        ->label('Save Product')
                        ->color('primary')
                        ->submit('save')
                )
        ]);
    }
}