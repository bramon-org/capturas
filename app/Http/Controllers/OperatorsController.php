<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class OperatorsController extends Controller
{
    /**
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $filter = [];
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 12);
        $filters = urldecode(http_build_query($filter));

        $operators = $this->doRequest('GET', "operators?limit={$limit}&page={$page}&{$filters}");

        return view('operators.index', ['operadores' => $operators]);
    }
}
