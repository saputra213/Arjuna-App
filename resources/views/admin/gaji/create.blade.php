<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Tambah Data Gaji') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 lg:p-8">
          <form action="{{ route('admin.gaji.store') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Karyawan</label>
              <select name="user_id" id="user_id" class="w-full mt-1 border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white">
                <option value="">-- Pilih Karyawan --</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-4">
              <label for="periode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periode</label>
              <input type="text" name="periode" id="periode" class="w-full mt-1 border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white" placeholder="Misal: September 2025">
            </div>

            <div class="mb-4">
              <label for="gaji_pokok" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Pokok</label>
              <input type="number" name="gaji_pokok" id="gaji_pokok" class="w-full mt-1 border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
              <label for="tunjangan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tunjangan</label>
              <input type="number" name="tunjangan" id="tunjangan" class="w-full mt-1 border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
              <label for="potongan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Potongan</label>
              <input type="number" name="potongan" id="potongan" class="w-full mt-1 border-gray-300 rounded shadow-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="flex justify-end">
              <a href="{{ route('admin.gaji.index') }}" class="px-4 py-2 mr-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
              <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
