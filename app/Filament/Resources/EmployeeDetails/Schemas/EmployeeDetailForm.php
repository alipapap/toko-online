<?php

namespace App\Filament\Resources\EmployeeDetails\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('employee_number')
                    ->required(),
                DatePicker::make('date_of_joining')
                    ->required(),
            ]);
    }
}