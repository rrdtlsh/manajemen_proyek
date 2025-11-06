<?php

namespace App\Controllers;

class Owner extends BaseController
{
    public function dashboard()
    {
        $data = [
            'user' => [
                'name' => session()->get('username') ?: 'Owner',
                'role' => session()->get('role') ?: 'owner',
            ],
            'stats' => [
                'orders_paid' => 2,
                'orders_unpaid' => 1,
                'out_of_stock' => 0,
                'in_stock' => 50,
                'items' => 200,
                'sales' => 451,
                'transactions' => 597,
                'revenue' => '415.13K',
            ],
        ];

        return view('dashboardowner', $data);
    }
}


