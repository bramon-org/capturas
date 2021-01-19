<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Http\Redirector;

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

    /**
     * @param Request $request
     * @return Renderable
     */
    public function new(Request $request): Renderable
    {
        return view('operators.new');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function add(Request $request)
    {
        $result = $this->doRequest('POST', "operators", $request->all());

        $id = $result['operator']['id'];

        return redirect(route('operators.edit', ['id' => $id]));
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Renderable
     */
    public function edit(Request $request, string $id): Renderable
    {
        $operador = $this->doRequest('GET', "operators/{$id}");

        return view('operators.edit', ['operador' => $operador]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return RedirectResponse|Redirector
     */
    public function save(Request $request, string $id)
    {
        $this->doRequest('PUT', "operators/{$id}", $request->all());

        return redirect(route('operators.edit', ['id' => $id]));
    }
}
