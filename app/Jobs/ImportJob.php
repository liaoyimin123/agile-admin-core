<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * excel路径
     */
    protected $excelPath;

    /**
     * 唯一编码
     */
    protected $unique_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($excelPath = '', $unique_code = '')
    {
        $this->excelPath = $excelPath;
        $this->unique_code = $unique_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new UsersImport($this->excelPath, $this->unique_code), storage_path('app/public/'.$this->excelPath));
    }
}
