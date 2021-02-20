<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Shop;
use App\Models\Statistics;
use App\Models\StatisticsSingle;
use Illuminate\Http\Request;

class StatisticsSingleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $id = \request()->input('id');
        $statisticsSingle = StatisticsSingle::whereSId($id)->get()->groupBy('shop_name');
        return $this->ajaxSuccessResponse('刷新成功', $statisticsSingle);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $goods = Good::all();
        $shops = Shop::all();
        return $this->ajaxSuccessResponse('', [
            'goods' => $goods,
            'shops' => $shops,
        ]);
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
        isset($data['color']) ?: $data['color'] = '默认';
        $count = $data['count'];
        unset($data['count']);
        //
        try {
            $statisticsSingle = StatisticsSingle::where($data)->first();
            if (!$statisticsSingle) {
                $data['count'] = $count;
                $statisticsSingle = StatisticsSingle::create($data);
            } else {
                $statisticsSingle->increment('count', $count);
            }
            $statisticsSingle->save();
        } catch (\Exception $e) {
            return $this->ajaxFaildResponse($e->getMessage());
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


    /**
     * 减少数量
     * @return \Illuminate\Http\JsonResponse
     */
    public function decrement()
    {
        $data = request()->all();
        isset($data['color']) ?: $data['color'] = '默认';
        $count = $data['count'];
        unset($data['count']);
        //
        try {
            $statisticsSingle = StatisticsSingle::where($data)->first();
            if ($statisticsSingle) {
                if ($statisticsSingle->count > $count) {
                    $statisticsSingle->decrement('count', $count);
                    $statisticsSingle->save();
                } else {
                    $statisticsSingle->delete();
                }
            }
        } catch (\Exception $e) {
            return $this->ajaxFaildResponse($e->getMessage());
        }
        return $this->ajaxSuccessResponse('');
    }
}
