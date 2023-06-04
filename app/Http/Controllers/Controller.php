<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $page = 1;

    protected $pageSize = 20;

    protected $offset = 0;

    public function __construct(Request $request)
    {
        $this->page = $request->input('page', 1);
        $this->pageSize = $request->input('pageSize', 20);
        $this->offset = ($this->page-1)*$this->pageSize;
        // dd($request);
    }

    /**
     * 成功时返回
     */
    protected function success($msg, $array = [], $code = 200)
    {
        $data = [
            'msg' => $msg,
            'data' => $array,
            'code' => $code
        ];
        return response()->json($data);
    }

    /**
     * 失败时返回
     */
    protected function error($msg, $array = [], $code = 500)
    {
        $data = [
            'msg' => $msg,
            'data' => $array,
            'code' => $code
        ];
        return response()->json($data);
    }
}
