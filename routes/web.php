Route::get('/', function () {
    $menusLeft = \App\Menu::whereEnabled(true)->whereLeft(true)->get();
    $menusLeft->each(function($item) {
        $item->items;
    });
    $menusRight = \App\Menu::whereEnabled(true)->whereLeft(false)->get();
    $menusRight->each(function($item) {
        $item->items;
    });
    return view('laravue::app', compact('menusLeft', 'menusRight'));
});
