<?php

namespace App\Admin\Widgets;

use App\Models\Category;
use TCG\Voyager\Widgets\AbstractWidget;

class CategoriesWidget extends AbstractWidget
{
    protected $options = [
        'icon' => 'voyager-tag',
        'color' => 'rgb(255, 159, 67)',
        'text' => 'Categories',
        'title' => 'Categories',
    ];

    public function run()
    {
        $count = Category::count();

        return view('voyager::dimmer', array_merge($this->options, [
            'title' => 'Categories',
            'text' => "Total categories: $count",
            'button' => [
                'text' => 'View',
                'link' => route('voyager.categories.index'),
            ],
            'image' => '',
        ]));
    }
}
