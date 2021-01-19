<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Http\Redirector;

class StationsController extends Controller
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

        $estacoes = $this->doRequest('GET', "stations?limit={$limit}&page={$page}&{$filters}");

        return view('stations.index', ['estacoes' => $estacoes]);
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function new(Request $request): Renderable
    {
        $operadores = $this->doRequest('GET', "operators?limit=10000");

        return view('stations.new', ['operadores' => $operadores]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function add(Request $request)
    {
        $result = $this->doRequest('POST', "stations", $request->all());
        $id = $result['station']['id'];

        return redirect(route('stations.edit', ['id' => $id]));
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Renderable
     */
    public function edit(Request $request, string $id): Renderable
    {
        $estacao = $this->doRequest('GET', "stations/{$id}");

        return view('stations.edit', ['estacao' => $estacao]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return RedirectResponse|Redirector
     */
    public function save(Request $request, string $id)
    {
        $this->doRequest('PUT', "stations/{$id}", $request->all());

        return redirect(route('stations.edit', ['id' => $id]));
    }
}
