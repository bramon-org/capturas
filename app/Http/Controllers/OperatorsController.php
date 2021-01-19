<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class OperatorsController extends Controller
{
    /**
     * @return Renderable
     */
    public function index(): Renderable
    {
        $filter = [];
        $page = 1;
        $limit = 9;
        $filters = urldecode(http_build_query($filter));

        $operators = $this->doRequest('GET', 'operator/operators?limit=12');

        return view('operators.index', ['operadores' => $operators]);
    }
}
