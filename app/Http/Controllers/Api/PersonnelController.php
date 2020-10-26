<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonnelResource;
use App\Laravue\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PersonnelController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    public function index(Request $request) {
        $searchParams = $request->all();
        $scheduleQuery = Personnel::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        return PersonnelResource::collection(($scheduleQuery->paginate($limit)));
    }
}
