namespace App\Observers;

use App\Models\IncidentReport;
use Illuminate\Support\Facades\Log;

class IncidentReportObserver
{
    public function updated(IncidentReport $report): void
    {
        if ($report->isDirty('status')) {
            Log::info("STATUS_CHANGE: Laporan #{$report->id} berubah dari [{$report->getOriginal('status')}] menjadi [{$report->status}] oleh User ID: " . auth()->id());
        }
    }
}