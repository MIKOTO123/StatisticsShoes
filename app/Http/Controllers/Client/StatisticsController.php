<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Shop;
use App\Models\Statistics;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $beginDate = Carbon::parse(request()->input('beginDate', Carbon::parse('-12 months')->toDateString()))->toDateTimeString();
        $endDate = Carbon::parse(request()->input('endDate', Carbon::now()->toDateString()))->toDateTimeString();
        $searchValue = \request()->input('searchValue');

        $query = Statistics::whereBetween('created_at', [$beginDate, $endDate]);
        if ($searchValue) {
            $query->where('shop_name', 'like', "%" . $searchValue . "%");
        }

        $data = $query->orderByDesc('created_at')->paginate(10);
        return $this->ajaxSuccessResponse('', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        //
        try {
            $statistics = Statistics::create($data);
            $statistics->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->ajaxFaildResponse('已经存在的商店名称');
            }
            return $this->ajaxFaildResponse('未知错误');
        }
        return $this->ajaxSuccessResponse('');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function checkExist()
    {
        $expectId = \request()->input('expectId', null);
        $statistics = new Statistics();
        $result = $statistics->where('statistics_name', request()->input('statistics_name'))
            ->where('id', '<>', $expectId)->first();
        if ($result) {
            return $this->ajaxFaildResponse('商店已经存在', [], 1);
        } else {
            return $this->ajaxSuccessResponse('', []);
        }
    }





}
