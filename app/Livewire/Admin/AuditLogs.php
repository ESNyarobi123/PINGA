<?php

namespace App\Livewire\Admin;

use App\Models\AdminAuditLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterAction = '';
    public string $filterModel = '';
    public string $filterAdmin = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterAction' => ['except' => ''],
        'filterModel' => ['except' => ''],
        'filterAdmin' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(7)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    private function getAuditLogsQuery()
    {
        return AdminAuditLog::query()
            ->with(['admin:id,name,email,avatar'])
            ->when($this->search, fn($query) => $query
                ->where(function ($q) {
                    $q->whereHas('admin', fn($sub) => $sub
                        ->where(function ($s) {
                            $s->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                    )
                    ->orWhere('action', 'like', '%' . $this->search . '%')
                    ->orWhere('model_type', 'like', '%' . $this->search . '%')
                    ->orWhere('ip_address', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->filterAction, fn($query) => $query->where('action', $this->filterAction))
            ->when($this->filterModel, fn($query) => $query->where('model_type', 'like', '%' . $this->filterModel . '%'))
            ->when($this->filterAdmin, fn($query) => $query->where('admin_id', $this->filterAdmin))
            ->when($this->dateFrom, fn($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection);
    }

    public function getAuditLogsProperty()
    {
        return $this->getAuditLogsQuery()->paginate(50);
    }

    public function getTotalLogsProperty(): int
    {
        return AdminAuditLog::count();
    }

    public function getTodayLogsProperty(): int
    {
        return AdminAuditLog::whereDate('created_at', today())->count();
    }

    public function getUniqueAdminsProperty(): int
    {
        return AdminAuditLog::distinct('admin_id')->count('admin_id');
    }

    public function getMostActiveAdminsProperty()
    {
        return AdminAuditLog::with('admin:id,name')
            ->selectRaw('admin_id, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('admin_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }

    public function getActionStatsProperty()
    {
        return AdminAuditLog::selectRaw('action, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('action')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
    }

    public function getAvailableActionsProperty(): array
    {
        return AdminAuditLog::distinct('action')
            ->orderBy('action')
            ->pluck('action')
            ->toArray();
    }

    public function getAvailableModelsProperty(): array
    {
        return AdminAuditLog::distinct('model_type')
            ->orderBy('model_type')
            ->pluck('model_type')
            ->map(fn($model) => class_basename($model))
            ->toArray();
    }

    public function getAdminsProperty()
    {
        return User::whereIn('role', ['admin', 'super_admin'])
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public function getLogDetails(AdminAuditLog $log): array
    {
        $details = [];

        if ($log->old_values) {
            $details['old_values'] = is_string($log->old_values) ? json_decode($log->old_values, true) : $log->old_values;
        }

        if ($log->new_values) {
            $details['new_values'] = is_string($log->new_values) ? json_decode($log->new_values, true) : $log->new_values;
        }

        if ($log->model) {
            $details['model'] = [
                'type' => class_basename($log->model_type),
                'id' => $log->model_id,
                'data' => $log->model->toArray(),
            ];
        }

        return $details;
    }

    public function getActionIcon(string $action): string
    {
        $icons = [
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            'approve' => '✅',
            'reject' => '❌',
            'suspend' => '⏸️',
            'activate' => '▶️',
            'ban' => '🚫',
            'unban' => '🔓',
            'login' => '🔑',
            'logout' => '🚪',
            'impersonate' => '👤',
            'stop_impersonate' => '👥',
            'export' => '📤',
            'import' => '📥',
            'backup' => '💾',
            'restore' => '♻️',
            'retry_withdrawal' => '🔄',
            'retry_job_payout' => '💼',
            'approve_withdrawal' => '💰',
            'reject_withdrawal' => '💸',
            'complete_withdrawal' => '✅',
            'update_settings' => '⚙️',
            'create_category' => '🏷️',
            'update_category' => '🏷️',
            'delete_category' => '🏷️',
            'toggle_category_status' => '🏷️',
            'reorder_categories' => '🏷️',
            'resolve_dispute' => '⚖️',
            'escalate_dispute' => '⚖️',
            'close_dispute' => '⚖️',
            'send_message' => '💬',
            'send_broadcast' => '📢',
            'mark_as_read' => '👁️',
            'grant_subscription' => '🎯',
            'credit_wallet' => '💳',
            'debit_wallet' => '💳',
            'verify_user' => '✅',
            'reset_password' => '🔐',
            'force_email_verification' => '📧',
            'toggle_user_status' => '🔄',
        ];

        return $icons[$action] ?? '📝';
    }

    public function getActionColor(string $action): string
    {
        $colors = [
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'approve' => 'green',
            'reject' => 'red',
            'suspend' => 'amber',
            'activate' => 'green',
            'ban' => 'red',
            'unban' => 'green',
            'login' => 'blue',
            'logout' => 'gray',
            'impersonate' => 'purple',
            'stop_impersonate' => 'purple',
            'export' => 'blue',
            'import' => 'blue',
            'backup' => 'green',
            'restore' => 'amber',
            'retry_withdrawal' => 'amber',
            'retry_job_payout' => 'amber',
            'approve_withdrawal' => 'green',
            'reject_withdrawal' => 'red',
            'complete_withdrawal' => 'green',
            'update_settings' => 'blue',
            'create_category' => 'green',
            'update_category' => 'blue',
            'delete_category' => 'red',
            'toggle_category_status' => 'amber',
            'reorder_categories' => 'blue',
            'resolve_dispute' => 'green',
            'escalate_dispute' => 'amber',
            'close_dispute' => 'blue',
            'send_message' => 'blue',
            'send_broadcast' => 'purple',
            'mark_as_read' => 'gray',
            'grant_subscription' => 'green',
            'credit_wallet' => 'green',
            'debit_wallet' => 'red',
            'verify_user' => 'green',
            'reset_password' => 'amber',
            'force_email_verification' => 'blue',
            'toggle_user_status' => 'amber',
        ];

        return $colors[$action] ?? 'gray';
    }

    public function exportAuditLogs(): void
    {
        $logs = $this->getAuditLogsQuery()->with(['admin', 'model'])->get();
        
        $csv = "ID,Admin,Action,Model Type,Model ID,IP Address,User Agent,Old Values,New Values,Created At\n";
        
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n",
                $log->id,
                str_replace(',', '', $log->admin->name ?? 'System'),
                $log->action,
                $log->model_type ? class_basename($log->model_type) : '',
                $log->model_id ?? '',
                $log->ip_address ?? '',
                str_replace(',', ';', $log->user_agent ?? ''),
                str_replace(',', ';', is_array($log->old_values) ? json_encode($log->old_values) : ($log->old_values ?? '')),
                str_replace(',', ';', is_array($log->new_values) ? json_encode($log->new_values) : ($log->new_values ?? '')),
                $log->created_at->format('Y-m-d H:i:s')
            );
        }

        $this->dispatch('download', data: $csv, filename: 'audit_logs_export.csv');
    }

    public function clearOldLogs(): void
    {
        $this->validate([
            'dateFrom' => 'required|date|before_or_equal:today',
        ]);

        $count = AdminAuditLog::whereDate('created_at', '<', $this->dateFrom)->delete();
        
        $this->dispatch('toast', message: "Deleted {$count} old audit logs", type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.audit-logs', [
            'logs' => $this->auditLogs,
            'totalLogs' => $this->totalLogs,
            'todayLogs' => $this->todayLogs,
            'uniqueAdmins' => $this->uniqueAdmins,
            'mostActiveAdmins' => $this->mostActiveAdmins,
            'actionStats' => $this->actionStats,
            'availableActions' => $this->availableActions,
            'availableModels' => $this->availableModels,
            'admins' => $this->admins,
        ])
            ->layout('layouts.admin')
            ->title('Audit Logs');
    }
}
