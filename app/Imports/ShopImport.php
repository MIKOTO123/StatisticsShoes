<?php

namespace App\Imports;


use App\Custom\Exceptions\ImportException;
use App\Custom\Utils\ConnectionUtils;
use App\Custom\Utils\Logger;
use App\Exports\ImportResultExport;
use App\Models\ContactInfo;
use App\Models\Good;
use App\Models\GroupInfo;
use App\Models\GroupMember;
use App\Models\ImportContactsJob;
use App\Models\ImportError;
use App\Models\ImportRecord;
use App\Models\Shop;
use App\Models\Statistics;
use App\Models\StatisticsSingle;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithReadFilter;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Exceptions\RowSkippedException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Events\AfterImport;
use mysql_xdevapi\Exception;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use App\Custom\Utils\StringUtils;

class ShopImport extends DefaultValueBinder implements WithCustomValueBinder, SkipsOnError, WithEvents, SkipsOnFailure, OnEachRow, WithChunkReading
{

    use RegistersEventListeners;


    public function __construct()
    {

    }


    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $rowarr = $row->toArray();
        $rowstr = trim($rowarr[0]);//直接是店铺名称

        try {
            $shop = Shop::create(['shop_name' => $rowstr]);
            $shop->save();
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                Logger::importLog('店铺名称已经存在');
                return;
            }
            Logger::importLog('未知错误');
            return;
        }

    }


    /**
     * @return int 一次读取多少条
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * 处理验证错误
     * @param Failure ...$failures
     */
    public function onFailure(Failure ...$failures)
    {

    }


    /**
     * 处理一些抛出的错误,比如数据库错误
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }


    /**
     * 导入失败的时候发送通知
     * @param ImportFailed $failed
     */
    public static function importFailed(ImportFailed $failed)
    {
        Logger::importLog('商店导入失败,导入失败的原因' . $failed->getException()->getMessage());
    }


    /**
     * 导入完成之后写入日志
     * @param AfterImport $event
     */
    public static function afterImport(AfterImport $event)
    {
        //保存错误数量和成功数量
        //获取错误部分
        Logger::importLog('商店导入成功了');
        //
    }


}
