<?php

namespace App\Filament\Admin\Resources\Products\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product Tabs')
                    ->tabs([

                        // Tab 1 – Product Info
                        Tab::make('Product Details')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('id')
                                    ->label('Product ID'),
                                TextEntry::make('sku')
                                    ->label('Product SKU')
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('description')
                                    ->label('Product Description'),
                                TextEntry::make('created_at')
                                    ->label('Product Creation Date')
                                    ->date('d M Y')
                                    ->color('info'),
                            ]),

                        // Tab 2 – Pricing & Stock
                        Tab::make('Product Price and Stock')
                            ->icon('heroicon-o-currency-dollar')
                            ->badge(fn($record) => $record->stock)
                            ->badgeColor(fn($record) => $record->stock > 10 ? 'success' : 'danger')
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Product Price')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->icon('heroicon-s-currency-dollar')
                                    ->formatStateUsing(
                                        fn($state) => 'Rp ' . number_format($state, 0, ',', '.')
                                    ),
                                TextEntry::make('stock')
                                    ->label('Product Stock')
                                    ->weight('bold')
                                    ->color('primary'),
                            ]),

                        // Tab 3 – Media & Status
                        Tab::make('Image and Status')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Product Image')
                                    ->disk('public'),
                                TextEntry::make('price')
                                    ->label('Product Price')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->icon('heroicon-s-currency-dollar')
                                    ->formatStateUsing(
                                        fn($state) => 'Rp ' . number_format($state, 0, ',', '.')
                                    ),
                                TextEntry::make('stock')
                                    ->label('Product Stock')
                                    ->weight('bold')
                                    ->color('primary'),
                                IconEntry::make('is_active')
                                    ->label('Is Active?')
                                    ->boolean(),
                                IconEntry::make('is_featured')
                                    ->label('Is Featured?')
                                    ->boolean(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->vertical(), // Hapus baris ini untuk tampilan horizontal
            ]);
    }
}