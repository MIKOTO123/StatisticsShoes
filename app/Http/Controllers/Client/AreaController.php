<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Good;
use App\Models\Shop;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::all();
        return $this->ajaxSuccessResponse('',$areas);
    }

    //

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
            $area = Area::create($data);
            $area->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->ajaxFaildResponse('已经存在的地址');
            }
            return $this->ajaxFaildResponse('未知错误');
        }
        return $this->ajaxSuccessResponse('');
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
        $data = $request->all();
        $area = Area::whereId($id)->firstOrFail();
        try {
            $area->area = $data['area'];
            $area->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->ajaxFaildResponse('已经存在的商店名称');
            }
            return $this->ajaxFaildResponse('未知错误');
        }
        return $this->ajaxSuccessResponse('保存成功');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //检测这个area是否被某些商店使用,有的话就不能删除
        $area = Area::findOrFail($id);
        $shop_name_c = Shop::whereAreaId($id)->pluck('shop_name');
        if ($shop_name_c->isNotEmpty()) {
            return $this->ajaxFaildResponse('商店' . $shop_name_c->implode(',') . '还在使用这个地区,请确认');
        }
        //删除
        $area->delete();
        return $this->ajaxSuccessResponse('删除成功');
    }


    public function checkExist()
    {
        $expectId = \request()->input('expectId', null);
        $area = new Area();
        $result = $area->where('area', request()->input('area'))
            ->where('id', '<>', $expectId)->first();
        if ($result) {
            return $this->ajaxFaildResponse('地区已经存在', [], 1);
        } else {
            return $this->ajaxSuccessResponse('', []);
        }
    }


}
