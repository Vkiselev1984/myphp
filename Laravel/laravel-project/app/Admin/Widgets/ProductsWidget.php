<?php

namespace App\Admin\Widgets;

use App\Models\Product;
use TCG\Voyager\Widgets\AbstractWidget;

class ProductsWidget extends AbstractWidget
{
    protected $options = [
        'icon' => 'voyager-basket',
        'color' => 'rgb(71, 119, 255)',
        'text' => 'Products',
        'title' => 'Products',
    ];

    public function run()
    {
        $count = Product::count();

        return view('voyager::dimmer', array_merge($this->options, [
            'title' => 'Products',
            'text' => "Total products: $count",
            'button' => [
                'text' => 'View',
                'link' => route('voyager.products.index'),
            ],
            'image' => '',
        ]));
    }
}
