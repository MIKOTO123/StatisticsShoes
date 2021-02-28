<?php

namespace App\Http\Controllers\Client;

use App\Custom\Utils\Logger;
use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\ImportError;
use App\Models\StatisticsSingle;
use Illuminate\Http\Request;

class ImportErrorController extends Controller
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
        $data = ImportError::whereSId($id)->get();
        return $this->ajaxSuccessResponse('刷新成功', $data);

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
        $importerror = ImportError::whereId($id)->first()->toArray();
        ImportError::whereId($id)->delete();
        Logger::debugLog("删除了:" . print_r($importerror, 1));
        return $this->ajaxSuccessResponse('删除成功');
    }
}
