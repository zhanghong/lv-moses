<?php

namespace App\Http\Controllers\Common;

use App\Models\Base\Area;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Base\AreaListResource;
use App\Http\Resources\Base\AreaDetailResource;

class AreaController extends Controller
{
    public function list(Request $request)
    {
        $parent_id = $request->get('pid');
        $areas = Area::where('parent_id', intval($parent_id))->get();
        return AreaListResource::collection($areas);
    }

    public function districts()
    {
        return ['data' => Area::optionsView()];
    }
}
