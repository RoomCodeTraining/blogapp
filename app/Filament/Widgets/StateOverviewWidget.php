<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Categorie;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StateOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Statistiques des posts
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();

        // Statistiques des catégories
        $totalCategories = Categorie::count();

        // Posts par mois (derniers 6 mois)
        $postsThisMonth = Post::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Posts les plus récents
        $recentPosts = Post::latest()->take(3)->get();

        return [
            Stat::make('Total des Articles', $totalPosts)
                ->description('Tous les articles du blog')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Articles Publiés', $publishedPosts)
                ->description('Articles visibles publiquement')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Brouillons', $draftPosts)
                ->description('Articles en cours de rédaction')
                ->descriptionIcon('heroicon-m-pencil')
                ->color('warning'),

            Stat::make('Catégories', $totalCategories)
                ->description('Nombre total de catégories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),

            Stat::make('Articles ce Mois', $postsThisMonth)
                ->description('Nouveaux articles ce mois-ci')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('secondary'),
        ];
    }
}


