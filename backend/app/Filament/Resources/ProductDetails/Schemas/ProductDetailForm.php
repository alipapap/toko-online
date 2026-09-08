<?php

namespace App\Filament\Resources\ProductDetails\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('weight')
                    ->required()
                    ->numeric()
                    ->suffix('gram'),
            ]);
    }
}