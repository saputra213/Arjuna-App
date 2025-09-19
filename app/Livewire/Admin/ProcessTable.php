<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\Process;

class ProcessTable extends Component
{
    public $order;
    public $processes;

    protected $listeners = ['openProcessDetail' => 'loadOrder'];

    public function loadOrder($orderId) {
        $this->order = Order::findOrFail($orderId);
        $this->loadProcesses();
    }

    public function loadProcesses() {
        $this->processes = Process::where('order_id',$this->order->id)->get();
    }

    public function updateOutput($processId, $value) {
        $process = Process::findOrFail($processId);
        $process->output_harian = $value;
        $process->save();
        $this->loadProcesses();
    }

    public function render() {
        return view('livewire.admin.process-table');
    }
}
