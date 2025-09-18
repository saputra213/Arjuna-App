<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Gaji') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <form action="{{ route('admin.gaji.update', $gaji->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Karyawan (readonly) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nama Karyawan</label>
                        <input type="text" value="{{ $gaji->user->name }}" 
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                        <input type="hidden" name="user_id" value="{{ $gaji->user_id }}">
                    </div>

                    {{-- Periode (readonly) --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Periode</label>
                        <input type="text" value="{{ $gaji->periode }}" 
                            class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                        <input type="hidden" name="periode" value="{{ $gaji->periode }}">
                    </div>

                    {{-- Gaji Pokok --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" 
                            value="{{ old('gaji_pokok', $gaji->gaji_pokok) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- Tunjangan --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Tunjangan</label>
                        <input type="number" name="tunjangan" 
                            value="{{ old('tunjangan', $gaji->tunjangan) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    {{-- Potongan --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Potongan</label>
                        <input type="number" name="potongan" 
                            value="{{ old('potongan', $gaji->potongan) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </form>



            </div>
        </div>
    </div>
</x-app-layout>
