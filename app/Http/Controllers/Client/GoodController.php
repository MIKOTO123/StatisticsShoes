<?php

namespace App\Http\Controllers\Client;

use App\Custom\Utils\PathUtils;
use App\Http\Controllers\Controller;
use App\Imports\GoodToShopImport;
use App\Imports\ShopImport;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class GoodController extends Controller
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
        $g_name = \request()->input('g_name');

        $query = Good::whereBetween('goods.created_at', [$beginDate, $endDate])
            ->leftJoin('shops', 'shops.id', '=', 'goods.shop_id')
            ->select('goods.*', 'shops.shop_name');
        if ($searchValue) {
            $query->where('shop_name', 'like', "%" . $searchValue . "%");
        }
        if ($g_name) {
            $query->where('g_name', 'like', "%" . $g_name . "%");
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
            $good = Good::create($data);
            $good->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->ajaxFaildResponse('已经存在的商品名称');
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
        $data = $request->all();
        $good = Good::whereId($id)->firstOrFail();
        try {
            $good->g_name = $data['g_name'];
            $good->mark = $data['mark'];
            $good->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                return $this->ajaxFaildResponse('已经存在的商品名称');
            }
            return $this->ajaxFaildResponse($e->getMessage());
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
        //
        Good::whereId($id)->delete();
        return $this->ajaxSuccessResponse('删除成功');
    }


    public function checkExist()
    {
        $expectId = \request()->input('expectId', null);
        $good = new Good();
        $result = $good->where('g_name', request()->input('g_name'))
            ->where('shop_id', request()->input('shop_id'))
            ->where('id', '<>', $expectId)->first();
        if ($result) {
            return $this->ajaxFaildResponse('商品已经存在', [], 1);
        } else {
            return $this->ajaxSuccessResponse('', []);
        }
    }


    public function goodImport($shop_id)
    {
        $file_path = request()->file('file')->store(PathUtils::getUploadImportGoodPath());
        $shopImport = new GoodToShopImport($shop_id);
        Excel::import($shopImport, $file_path);
        return '导入成功';
    }


}
