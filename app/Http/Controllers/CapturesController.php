<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 12);
        $filters = urldecode(http_build_query($filters));

        $uri = "captures?page={$page}&limit={$limit}&{$filters}";
        $hash = md5('capturas_' . $uri);

        if (Cache::has($hash)) {
            return Cache::get($hash);
        }

        $estacoes = $this->getAllStations();
        $radiantes = $this->getRadiants();
        $capturas = $this->doRequest('GET', $uri);

        Cache::put($hash, $capturas, Carbon::now()->addMinutes(2));

        return view('capturas.index', ['estacoes' => $estacoes, 'capturas' => $capturas, 'radiantes' => $radiantes]);
    }
}
