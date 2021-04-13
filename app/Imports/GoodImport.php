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

class GoodImport extends DefaultValueBinder implements WithCustomValueBinder, SkipsOnError, WithEvents, SkipsOnFailure, OnEachRow
{

    use RegistersEventListeners;

    public $user = null;
    public $error_arr = [];
    public $error_count = 0;
    public $success_count = 0;
    public $statistics = null;


    public function __construct(Statistics $statistics)
    {
        $this->statistics = $statistics;
    }


    public function onRow(Row $row)
    {

        $rowIndex = $row->getIndex();
        $rowarr = $row->toArray();
        $rowstr = trim($rowarr[0]);
        try {
            $tmparr = explode(' ', $rowstr);
            $tmparr = array_values(array_filter($tmparr));
//        Log::debug("初始:" . print_r($tmparr, 1));
//'shop_name', 'g_name', 'color', 'count'
            if (empty($tmparr)) {
                return;
            }
//            Logger::debugLog(print_r($tmparr, 1));
            $g_name = $tmparr[0];
            $color = $tmparr[1];
            unset($data);
            $data = array();
            $data = [
                's_id' => $this->statistics->id,
                'g_name' => $g_name,
                'color' => $color,
            ];

            //先查找鞋子到底是否存在
            try {
                $goodsAndShop = Good::whereGName($g_name)
                    ->leftJoin('shops', 'shops.id', '=', 'goods.shop_id')
                    ->leftJoin('areas', 'areas.id', '=', 'shops.area_id')
                    ->select('goods.*', 'shops.shop_name', 'areas.area')->get();
                $shopcount = $goodsAndShop->count();
                if ($shopcount > 1) {
                    throw new \Exception('商品有多个店家');
                } elseif ($shopcount == 0) {
                    throw new \Exception('商品不存在');
                }
                $data['shop_name'] = $goodsAndShop->toArray()[0]['shop_name'];
                $data['area'] = $goodsAndShop->toArray()[0]['area'];//应该是null
            } catch (\Exception $e) {
                //添加错误
                $this->onError(new ImportException($e->getMessage(), 0, $rowstr));
                return;
            }


            unset($tmparr[0]);
            unset($tmparr[1]);
            $tmparr = array_values($tmparr);//上下的全是码数

            //$tmparr = [35*6, 40*3,41*2 , ... ]
            foreach ($tmparr as $item) {
                //每次循环的时候,,unsetcount
                unset($data['count']);
                $size_and_count = explode('*', $item);
                if (count($size_and_count) != 2) {
                    //格式不符合要求抛出异常
                    $this->onError(new ImportException('码数*数量不符合要求', 0, $rowstr));
                    continue;
                }
                $count = $size_and_count[1];
                $data['size'] = $size_and_count[0];
                try {
                    $statisticsSingle = StatisticsSingle::where($data)->first();
                    if (!$statisticsSingle) {
                        $data['count'] = $count;
                        $statisticsSingle = StatisticsSingle::create($data);
                    } else {
                        $statisticsSingle->increment('count', $count);
                    }
//                Logger::debugLog(print_r($data, 1));
                    $statisticsSingle->save();
                } catch (\Exception $e) {
                    //添加错误
                    $this->onError(new ImportException($e->getMessage(), 0, $rowstr));
                    return;
                }

            }
        } catch (\Exception $error1) {
            $this->onError(new ImportException($error1->getMessage(), 0, $rowstr));
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
        try {
            $data = [
                'error_reason' => $e->getMessage(),
                'error_row' => $e->getRaw(),
                's_id' => $this->statistics->id,
            ];
            ImportError::create($data);

        } catch (\Exception $error) {
            Logger::importLog('无法写入import_record数据库');
        }

        // Handle the exception how you'd like.
    }


    /**
     * 导入失败的时候发送通知
     * @param ImportFailed $failed
     */
    public static function importFailed(ImportFailed $failed)
    {
        Logger::importLog('导入失败,导入失败的原因' . $failed->getException()->getMessage());
    }


    /**
     * 导入完成之后写入日志
     * @param AfterImport $event
     */
    public static function afterImport(AfterImport $event)
    {
        //保存错误数量和成功数量
        //获取错误部分
        Logger::importLog($event->getConcernable()->statistics->id . '导入成功了');
        //
    }


}
