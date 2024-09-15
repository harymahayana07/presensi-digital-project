<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfficeResource\Pages;
use App\Filament\Resources\OfficeResource\RelationManagers;
use App\Models\Office;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Humaidem\FilamentMapPicker\Fields\OSMMap;

class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_kantor')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat_kantor')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email_kantor')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi_kantor')
                            ->columnSpanFull(),
                        ])
                    ]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make()
                            ->schema([
                                OSMMap::make('location')
                                    ->label('Location')
                                    ->showMarker()
                                    ->draggable()
                                    ->extraControl([
                                        'zoomDelta'           => 1,
                                        'zoomSnap'            => 0.25,
                                        'wheelPxPerZoomLevel' => 60
                                    ])
                                    ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, $record) {
                                        $latitude = $record->latitude ?? null;
                                        $longitude = $record->longitude ?? null;
                
                                        if ($latitude && $longitude) {
                                            $set('location', ['lat' => $latitude, 'lng' => $longitude]);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $set('latitude', $state['lat']);
                                        $set('longitude', $state['lng']);
                                    })
                                    ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
                                    Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                        ->required()
                                        ->numeric(),
                                    Forms\Components\TextInput::make('longitude')
                                        ->required()
                                        ->numeric(),
                                    ])->columns(2),
                                Forms\Components\TextInput::make('radius')
                                    ->required()
                                    ->numeric(),
                            ])
                        ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kantor')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('alamat_kantor')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('email_kantor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('radius')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOffices::route('/'),
            'create' => Pages\CreateOffice::route('/create'),
            'edit' => Pages\EditOffice::route('/{record}/edit'),
        ];
    }
}
