<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SystemMenu;

class SidebarComposer
{
    /**
     * Bind dynamic sidebar navigation menus from the database to the view.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();

        if (!$user) {
            $view->with('sidebarMenus', collect());
            return;
        }

        $cacheKey = 'sidebar_'.$user->id.'_'.md5($user->getAllPermissions()->pluck('name')->implode(','));

        $menus = Cache::remember($cacheKey, 3600, function () use ($user) {
            return SystemMenu::where('is_visible', 1)
                ->when($user->hasRole('super-admin') === false, function ($q) use ($user) {
                    $q->whereIn('permission_code', $user->getAllPermissions()->pluck('name'));
                })
                ->orderBy('sort_order')
                ->get();
        });

        $view->with('sidebarMenus', $menus);
    }
}
