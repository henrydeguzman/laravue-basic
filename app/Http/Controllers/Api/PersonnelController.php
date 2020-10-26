<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonnelResource;
use App\Laravue\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class PersonnelController extends BaseController
{
    const ITEM_PER_PAGE = 15;
    public function getList(Request $request) {
        $searchParams = $request->all();
        $scheduleQuery = Personnel::with('user')->orderBy('id', 'desc');
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        // return Personnel::with('user')->orderBy('id', 'desc')->paginate(5);
        return PersonnelResource::collection(($scheduleQuery->paginate($limit)));
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => ['required']
            ]
        );

        if ($validator->fails()) {
            return response()->json(['erros' => $validator->errors()], 403);
        } else {
            $params = $request->all();
            $user = Personnel::create([
                'user_id' => $params['id']
            ]);
            return new PersonnelResource($user);
        }
    }
    public function destroy($id) {
        // try{
        //     $personnel->delete();
        // } catch (\Exception $ex) {
        //     return response()->json(['error' => $ex->getMessage()], 403);
        // }
        // return response()->json(null, 204);
        $isDelete = Personnel::find($id)->delete();
        return $isDelete;
    }
}
