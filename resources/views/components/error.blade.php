@props(['name' => ''])
@error($name)
    <span class="text-sm text-red-500 font-medium">{{ $message }}</span>
@enderror
