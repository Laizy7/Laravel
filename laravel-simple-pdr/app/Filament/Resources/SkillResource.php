<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Filament\Resources\SkillResource\RelationManagers;
use App\Models\Skill;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Storage;

class SkillResource extends Resource
{
  protected static ?string $model = Skill::class;

  protected static ?string $navigationIcon = 'heroicon-o-collection';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('nama_skill')
          ->required()
          ->maxLength(255),
        Forms\Components\FileUpload::make('thumbnail')
          ->required()
          ->image()->disk('public'),
        Forms\Components\TextInput::make('level')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('keterangan')
          ->maxLength(255)
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('nama_skill')->sortable()->searchable(),
        Tables\Columns\ImageColumn::make('thumbnail'),
        Tables\Columns\TextColumn::make('level')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('keterangan')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime(),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make()->after(function (EloquentCollection $records) {
          foreach ($records as $key => $value) {
            if ($value->thumbnail) {
              Storage::disk('public')->delete($value->thumbnail);
            }
          }
        }),
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
      'index' => Pages\ListSkills::route('/'),
      'create' => Pages\CreateSkill::route('/create'),
      'edit' => Pages\EditSkill::route('/{record}/edit'),
    ];
  }
}
