<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Additional Information</h2>

    <div class="mb-4">
        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Order Notes (Optional)</label>
        <textarea id="notes"
                  name="notes"
                  rows="4"
                  placeholder="Add any special instructions or notes for your order..."
                  class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">{{ old('notes') }}</textarea>
        @error('notes')
            <p class="text-dark-brown text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>


</div>
