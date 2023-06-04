<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    /**
     * 图片上传
     */
    public function uploadImg(Request $request)
    {
        try {
            $params = $request->all(); //获取图片参数
            //定义验证规则
            $rules = [
                'img' => 'required|image|max:2048',
            ];
            //自定义规则提示语
            $message = [
                'img.required' => '请上传图片',
                'img.image' => '上传的文件不是图片类型',
                'img.max' => '只能上传2MB以内的图片'
            ];
            //规则验证
            $validate = Validator::make($params, $rules, $message);
            if ($validate->fails()) {
                return $this->error($validate->errors()->messages()['img'][0]);
            }
            $path = $request->file('img')->store('img/' . date('Y-m-d'), 'public');
            return $this->success('上传成功', ['img' => $path]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Excel上传
     */
    public function uploadExcel(Request $request)
    {
        try {
            $params = $request->all(); //获取Excel参数
            //定义验证规则
            $rules = [
                'excel' => 'required|mimes:xlsx',
            ];
            //自定义规则提示语
            $message = [
                'excel.required' => '请上传excel文件',
                'excel.mimes' => '上传的文件不是xlsx的格式',
            ];
            //规则验证
            $validate = Validator::make($params, $rules, $message);
            if ($validate->fails()) {
                return $this->error($validate->errors()->messages()['excel'][0]);
            }
            $path = $request->file('excel')->store('excel/' . date('Y-m-d'), 'public');
            return $this->success('上传成功', ['excel' => $path]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
