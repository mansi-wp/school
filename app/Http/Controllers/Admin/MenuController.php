<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemMenu;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menu.view')->only(['index']);
        $this->middleware('permission:menu.create')->only(['create', 'store']);
        $this->middleware('permission:menu.edit')->only(['edit', 'update', 'reorder']);
        $this->middleware('permission:menu.delete')->only('destroy');
    }

    public function index()
    {
        $menus = SystemMenu::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = SystemMenu::whereNull('parent_id')->get();
        return view('admin.menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:60',
            'route_name' => 'nullable|string|max:150',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:system_menus,id',
            'sort_order' => 'required|integer',
            'is_visible' => 'required|boolean',
            'permission_code' => 'required|string|max:125',
        ]);

        SystemMenu::create($validated);
        MenuService::clearCache();

        return redirect()->route('admin.menus.index')->with('success', 'Navigation menu item created.');
    }

    public function edit(SystemMenu $menu)
    {
        $parents = SystemMenu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, SystemMenu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'required|string|max:60',
            'route_name' => 'nullable|string|max:150',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:system_menus,id',
            'sort_order' => 'required|integer',
            'is_visible' => 'required|boolean',
            'permission_code' => 'required|string|max:125',
        ]);

        $menu->update($validated);
        MenuService::clearCache();

        return redirect()->route('admin.menus.index')->with('success', 'Navigation menu item updated.');
    }

    public function destroy(SystemMenu $menu)
    {
        $menu->delete();
        MenuService::clearCache();

        return redirect()->route('admin.menus.index')->with('success', 'Navigation menu item removed.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:system_menus,id',
        ]);

        foreach ($request->order as $index => $id) {
            SystemMenu::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        MenuService::clearCache();

        return response()->json(['status' => 'success']);
    }
}
