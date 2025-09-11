<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Rekap Gaji') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
        <div class="p-6 lg:p-8">
          <div class="mb-4">
            <a href="{{ route('admin.gaji.create') }}" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700">
              + Tambah Gaji
            </a>
          </div>
          <a href="{{ route('admin.gaji.rekap', ['periode' => now()->format('Y-m')]) }}"
   class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
   Generate Gaji Bulan Ini
</a>


          <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
              <tr>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">No</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Nama</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Periode</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Gaji Pokok</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tunjangan</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Potongan</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Total</th>
                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($gaji as $row)
              <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $row->user->name }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $row->periode }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Rp {{ number_format($row->gaji_pokok, 0, ',', '.') }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Rp {{ number_format($row->tunjangan, 0, ',', '.') }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">Rp {{ number_format($row->potongan, 0, ',', '.') }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 font-semibold">Rp {{ number_format($row->total_gaji, 0, ',', '.') }}</td>
                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                  <a href="{{ route('admin.gaji.edit', $row->id) }}" class="px-2 py-1 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Edit</a>
                  <form action="{{ route('admin.gaji.destroy', $row->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-2 py-1 text-sm text-white bg-red-600 rounded hover:bg-red-700" onclick="return confirm('Yakin hapus?')">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
