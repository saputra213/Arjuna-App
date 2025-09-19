<div class="p-6">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Inventory Produksi
        </h2>
        <button wire:click="createOrder"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            + Tambah Order
        </button>
    </div>

    {{-- List Orders --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="border px-4 py-2 text-left text-sm font-semibold">Brand</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Cutting</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Produksi</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Finishing</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Target / Hari</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Status</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Penghasilan Hari Ini</th>
                    <th class="border px-4 py-2 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border px-4 py-2 text-sm">{{ $order->brand }}</td>
                        <td class="border px-4 py-2 text-center text-sm">{{ $order->cutting_qty ?? 2 }}</td>
                        <td class="border px-4 py-2 text-center text-sm">{{ $order->produksi_qty ?? 2 }}</td>
                        <td class="border px-4 py-2 text-center text-sm">{{ $order->finishing_qty ?? 2 }}</td>
                        <td class="border px-4 py-2 text-center text-sm">{{ $order->target_per_day }}</td>
                        <td class="border px-4 py-2 text-center text-sm">
                            @if ($order->finishing_qty >= $order->target_per_day)
                                <span class="rounded bg-green-200 px-2 py-1 text-xs font-semibold text-green-800">✔
                                    Tercapai</span>
                            @else
                                <span class="rounded bg-red-200 px-2 py-1 text-xs font-semibold text-red-800">✘
                                    Kurang</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-center text-sm">
                            Rp {{ number_format(($order->finishing_qty ?? 0) * ($order->price_per_pcs ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <button wire:click="editOrder({{ $order->id }})"
                                    class="rounded bg-yellow-400 px-2 py-1 text-xs text-white hover:bg-yellow-500">
                                    Edit
                                </button>
                                <button wire:click="deleteOrder({{ $order->id }})"
                                    class="rounded bg-red-500 px-2 py-1 text-xs text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                            Belum ada data order
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Create / Edit --}}
    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {{ $showEditModal ? 'Edit Order' : 'Tambah Order' }}
                </h3>

                <div class="mb-3">
                    <label class="block text-sm">Brand</label>
                    <input type="text" wire:model="brand"
                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                    @error('brand') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Jumlah Order</label>
                    <input type="number" wire:model="jumlah_order"
                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                    @error('jumlah_order') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Harga per Pcs</label>
                    <input type="number" wire:model="price_per_pcs"
                        class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                    @error('price_per_pcs') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="$set('showCreateModal', false); $set('showEditModal', false)"
                        class="rounded bg-gray-400 px-4 py-2 text-white hover:bg-gray-500">Batal</button>
                    @if ($showEditModal)
                        <button wire:click="update"
                            class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">Update</button>
                    @else
                        <button wire:click="store"
                            class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
