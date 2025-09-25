<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post')
                    ->schema([
                          TextInput::make('title')
                    ->required(),
                TextInput::make('sub_title')
                    ->default(null),
                Select::make('status')
                    ->options(['published' => 'Published', 'draft' => 'Draft', 'pending' => 'Pending'])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
                Select::make('categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                  Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('cover_photo_path')
                    ->disk('public')
                    ->directory('posts')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('photo_alt_text')
                    ->default(null),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
