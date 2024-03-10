<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-0">
                    <h2>Usted verá el 'Product 1' y otros productos geniales</h2>
                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md border dark:border-gray-600">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-600 text-left">
                                        <span class="text-xs leading-4 font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Name') }}</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-600 text-left">
                                        <span class="text-xs leading-4 font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Price') }}</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500 divide-solid">
                                @forelse($products as $product)
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white dark:bg-gray-900">
                                        <td colspan="2" class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-gray-100">
                                            {{ __('No products found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
