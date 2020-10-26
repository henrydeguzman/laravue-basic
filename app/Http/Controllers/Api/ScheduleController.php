<?php

namespace App\Http\Controllers\Api;

use App\Laravue\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    public function index(Request $request){

    }

    public function personnelList(Request $request)
    {
        $searchParams = $request->all();
        $scheduleQuery = Schedule::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        return ScheduleResource::collection($scheduleQuery->paginate($limit));
    }
}
