<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Akses;
use App\Models\Menu;

class CheckReadPermission
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Anda belum login.');
        }

        $segment = $request->segment(1);

        $menu = Menu::where('segment', $segment)->first();

        if (!$menu) {
            abort(403, 'Menu tidak ditemukan.');
        }

        $hasAccess = Akses::where('id_role', $user->id_role)->where('id_menu', $menu->id)->where('read', 1)->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak punya izin membaca menu ini.');
        }

        return $next($request);
    }
}
