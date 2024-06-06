<?php

namespace App\Jobs;

use App\ExportDetail;
use App\Exports\UsersExport;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $selectedColumns;
    protected $searchParams;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($selectedColumns, $searchParams, $email)
    {
        $this->selectedColumns = $selectedColumns;
        $this->searchParams = $searchParams;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->selectedColumns = array_filter($this->selectedColumns, function ($column) {
            return ! in_array($column, ['checkbox', 'action']);
        });
        $users = User::query();

        foreach ($this->searchParams as $key => $value) {
            if ($value !== null && $value !== '') {
                if ($key === 'reg_from') {
                    $users->whereDate('created_at', '>=', date('Y-m-d', strtotime($value)));
                } elseif ($key === 'reg_till') {
                    $users->whereDate('created_at', '<=', date('Y-m-d', strtotime($value)));
                } else {
                    $users->where($key, $value);
                }
            }
        }
        $users->orderBy('created_at', 'desc');
        if (! empty($this->selectedColumns)) {
            $statusColumns = ['mobile_verified', 'active', 'is_2fa_enabled'];
            foreach ($statusColumns as $statusColumn) {
                if (! in_array($statusColumn, $this->selectedColumns)) {
                    $this->selectedColumns[] = $statusColumn;
                }
            }
        }

        $filteredUsers = $users->get()->map(function ($user) {
            $userData = [];
            foreach ($this->selectedColumns as $column) {
                switch ($column) {
                    case 'name':
                        $userData['name'] = $user->first_name.' '.$user->last_name;
                        break;
                    case 'mobile':
                        $userData['mobile'] = '+'.$user->mobile_code.' '.$user->mobile;
                        break;
                    case 'mobile_verified':
                    case 'active':
                    case 'is_2fa_enabled':
                        $userData[$column] = $user->$column ? 'Active' : 'Inactive';
                        break;
                    default:
                        $userData[$column] = $user->$column;
                }
            }

            return $userData;
        });

        $usersData = $filteredUsers;

        // Generate Excel file and create ExportDetail
        $export = new UsersExport($this->selectedColumns, $usersData);
        $id = User::where('email', $this->email)->value('id');
        $user = User::find($id);
        $timestamp = now()->format('Ymd_His');
        $fileName = 'users_'.$id.'_'.$timestamp.'.xlsx';
        $filePath = storage_path('app/public/export/'.$fileName);
        Excel::store($export, 'public/export/'.$fileName);

        $exportDetail = ExportDetail::create([
            'user_id' => $id,
            'file_path' => $filePath,
            'file' => $fileName,
            'name' => 'users',
        ]);

        $settings = \App\Model\Common\Setting::find(1);
        $from = $settings->email;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $downloadLink = route('download.exported.file', ['id' => $exportDetail->id]);
        $emailContent = 'Hello '.$user->first_name.' '.$user->last_name.','.
        '<br><br>User report is successfully generated and ready for download.'.
        '<br><br>Download link: <a href="'.$downloadLink.'">'.$downloadLink.'</a>'.
        '<br><br>Please note this link will be expired in 6 hours.'.
        '<br><br>Kind regards,<br>'.$user->first_name;

        $mail->SendEmail($from, $this->email, $emailContent, 'User report available for download');
    }
}
