<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $users = [
            ['id' => 1, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 2, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 3, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
            ['id' => 4, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 5, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 6, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
            ['id' => 7, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 8, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 9, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
            ['id' => 10, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 11, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 12, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
            ['id' => 13, 'type' => 'A', 'name' => 'Raquel Fonseca', 'pass' => 'he123'],
            ['id' => 14, 'type' => 'S', 'name' => 'Mônica Vargas', 'pass' => 'he123'],
            ['id' => 15, 'type' => 'R', 'name' => 'Fábio Martins', 'pass' => 'he123'],
        ];

        return view('maintenance.index', ['users' => $users]);
    }
}
