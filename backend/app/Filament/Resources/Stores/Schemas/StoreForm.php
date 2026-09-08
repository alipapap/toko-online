<?php

namespace App\Filament\Resources\Stores\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
             Select::make('user_id')
             ->relationship('user', 'name') // 'name' = kolom yg mau ditampilin dari tabel users
             ->searchable()
             ->preload()
             ->required()
             ->unique(ignoreRecord: true)
             ->validationMessages([
                 'unique' => 'User ini sudah punya toko. Satu user hanya boleh memiliki satu toko.',
             ]),
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
            ]);
    }
}