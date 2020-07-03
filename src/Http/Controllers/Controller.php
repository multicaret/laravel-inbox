<?php

namespace Multicaret\Inbox\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->composer('inbox::*', function ($view) {
            $view->with([
                'recipients' => config('auth.providers.users.model')::where('id', '!=', auth()->id())->get(),
            ]);
        });
    }
}
