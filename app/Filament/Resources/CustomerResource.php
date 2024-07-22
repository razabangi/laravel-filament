<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(100),
                TextInput::make('email')->email()->required()->maxLength(100),
                TextInput::make('phone')->required()->numeric(),
                ToggleButtons::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'In Active',
                    ])
                    ->colors([
                        '1' => 'info',
                        '0' => 'danger',
                    ])
                    ->default(1)
                    ->grouped(),
                Select::make('Country')
                    ->options([
                        'US' => 'US',
                        'PAK' => 'PAK',
                        'UK' => 'UK',
                    ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable()->toggleable(),
                TextColumn::make('phone')->searchable()->sortable()->toggleable(),
                IconColumn::make('status')
                    ->boolean()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->searchPlaceholder('Search (Name, Email, Phone)')
            ->filters([
                Filter::make('status')
                    ->query(fn (Builder $query) => $query->where('status', true)),
                SelectFilter::make('country')
                    ->options([
                        'US' => 'US',
                        'UK' => 'UK',
                        'PAK' => 'PAK',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
