<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class StationsController extends Controller
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

        $estacoes = $this->doRequest('GET', 'operator/stations?limit=1000');

        return view('stations.index', ['estacoes' => $estacoes]);
    }
}
