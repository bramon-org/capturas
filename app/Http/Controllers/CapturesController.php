<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CapturesController extends Controller
{
    /**
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $filters = [];

        if (!empty($request->get('date'))) {
            $filters['filter[captured_at]'] = $request->get('date');
        }

        if (!empty($request->get('station'))) {
            foreach ($request->get('station') as $station) {
                $filters['filter[station]'][] = $station;
            }
        }

        if (!empty($request->get('radiant'))) {
            $filters['filter[class]'] = $request->get('radiant');
        }

        if (!empty($request->get('analyzed'))) {
            $filters['filter[analyzed]'] = 'true';
        }

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 12);
        $filters = urldecode(http_build_query($filters));

        $uri = "captures?page={$page}&limit={$limit}&{$filters}";
        $hash = md5('capturas_' . $uri);

        $estacoes = $this->getAllStations();
        $radiantes = $this->getRadiants();

        $capturas = Cache::get($hash, function() use($uri) {
            return $this->doRequest('GET', $uri);
        });

        $capturas['data'] = array_filter($capturas['data'], function ($captura) {
            return array_key_exists('files', $captura);
        });

        return view('capturas.index', ['estacoes' => $estacoes, 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }
}
