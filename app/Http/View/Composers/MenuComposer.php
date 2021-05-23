<?php

namespace App\Http\View\Composers;

use App\Models\Checklist;
use App\Services\MenuService;
use Carbon\Carbon;
use Illuminate\View\View;

class MenuComposer
{

    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     * @return void
     */
    public function compose(View $view)
    {
        $menu = (new MenuService())->get_menu();
        $view->with('admin_menu', $menu['admin_menu']);
        $view->with('user_menu', $menu['user_menu']);
        $view->with('user_tasks_menu', $menu['user_tasks_menu']);
    }
}
