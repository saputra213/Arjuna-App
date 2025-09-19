<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;

class InventoryComponent extends Component
{
    public $orders;
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // properti untuk data form
    public $brand, $jumlah_order, $price_per_pcs;

    // modal control
    public $showCreateModal = false;
    public $showEditModal = false;

    // untuk edit
    public $orderIdBeingEdited;

    public function mount() {
        $this->orders = Order::with('processes')->latest()->get();
    }

    public function render() {
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.inventory-component',[
            'orders' => $orders,
        ]);
    }

    // ------------------------
    // Tambah Order Baru
    // ------------------------
    public function createOrder()
    {
        $this->resetInput();
        $this->showCreateModal = true;
    }

    public function store()
{
    $this->validate([
        'brand' => 'required|string|max:255',
        'jumlah_order' => 'required|integer|min:1',
        'price_per_pcs' => 'required|integer|min:0',
    ]);

    Order::create([
        'brand' => $this->brand,
        'jumlah_order' => $this->jumlah_order,
        'price_per_pcs' => $this->price_per_pcs,
        'cutting_qty' => 2,
        'produksi_qty' => 2,
        'finishing_qty' => 2,
    ]);

    $this->reset(['brand', 'jumlah_order', 'price_per_pcs', 'showCreateModal']);
    session()->flash('message', 'Order berhasil ditambahkan.');
}

    // ------------------------
    // Edit Order
    // ------------------------
    public function editOrder($id)
    {
        $order = Order::findOrFail($id);

        $this->orderIdBeingEdited = $id;
        $this->brand = $order->brand;
        $this->jumlah_order = $order->jumlah_order;
        $this->price_per_pcs = $order->price_per_pcs;

        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'brand'     => 'required|string|max:255',
            'jumlah_order' => 'required|integer|min:1',
            'price_per_pcs' => 'required|integer|min:0',
        ]);

        $order = Order::findOrFail($this->orderIdBeingEdited);

        $order->update([
            'brand'     => $this->brand,
            'jumlag_order' => $this->jumlah_order,
            'price_per_pcs' => $this->price_per_pcs,
        ]);

        $this->resetInput();
        $this->showEditModal = false;

        session()->flash('message', 'Order berhasil diperbarui!');
    }

    // ------------------------
    // Hapus Order
    // ------------------------
    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        session()->flash('message', 'Order berhasil dihapus!');
    }

    // ------------------------
    // Helper
    // ------------------------
    public function resetInput()
    {
        $this->brand = '';
        $this->jumlah_order = '';
        $this->price_per_pcs = '';
        $this->orderIdBeingEdited = null;
    }
}
