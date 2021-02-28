<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Imports\GoodImport;
use App\Models\Good;
use App\Models\Shop;
use App\Models\Statistics;
use App\Models\StatisticsSingle;
use Illuminate\Http\Request;
use App\Custom\Utils\PathUtils;
use Maatwebsite\Excel\Facades\Excel;

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
        $statisticsSingle = StatisticsSingle::whereSId($id)->orderByDesc('g_name')->get()->groupBy('shop_name');
        $statisticsSingle = $statisticsSingle->toArray();
//        $tmpdata = array();
//        print_r($statisticsSingle);die;
//        foreach ($statisticsSingle as $key => $item) {
//            foreach ($item as $value) {
//                isset($tmpdata[$key][$value['g_name']][$value['color']]) ? $tmpdata[$key][$value['g_name']][$value['color']] .= $value['size'] . "*" . $value['count'] . " " : $tmpdata[$key][$value['g_name']][$value['color']] = $value['size'] . "*" . $value['count'] . " ";
//            }
//        }


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


    /**
     * 上传
     * @return false|string
     */
    public function uploadImport($s_id)
    {
//        $s_id = \request()->input("s_id");
        $file_path = request()->file('file')->store(PathUtils::getUploadImportPath());
        //开始进行导入,,应该进行不了非常长的时间,所以就不放到后台进行导入
//        $fliecontent = Storage::get($file_path);
//        $fliecontent =  file('D:\Users\ys-8564\workspace\StatisticsShoes\storage\app\upload\import\2021-02-23\hPZbPfMxds0y3dFnA8HNhDVuQWIYvVN8pBhmabU4.txt');
        $statistics = Statistics::whereId($s_id)->first();
        $contactImport = new GoodImport($statistics);
        Excel::import($contactImport, $file_path);
        return '导入成功';
    }


}
