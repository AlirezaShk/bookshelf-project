<div class='{{$prefix}}-{{$name}}-container tw-w-full tw-relative tw-float-left tw-grid 
{{ $type === "checkbox" ? "tw-grid-cols-2" : "tw-grid-rows-2"}} 
tw-mb-2' style='grid-template-rows: auto;'>
    <label for='{{$prefix}}-{{$name}}'>
        {{$label}}
        @if ($required)
        <span class='helper tw-text-red-600 tw-text-md'>*</span>
        @endif
        @if (config('app.debug') && $name === 'isbn')
        <span class='helper tw-bg-gray-300 tw-px-3 tw-py-1 hover:tw-bg-gray-400 tw-cursor-help tw-duration-100 tw-float-right' @click='ISBNFiller'>Auto Fill</span>
        @endif
        @if ($name === 'author_id')
        <a href='/author/list' class='helper tw-bg-gray-300 tw-px-3 tw-py-1 hover:tw-bg-gray-400 tw-cursor-help tw-duration-100 tw-float-right tw-ml-2 hover:tw-no-underline hover:tw-text-black'>Author List</a>
        @if (config('app.debug'))
        <span class='helper tw-bg-gray-300 tw-px-3 tw-py-1 hover:tw-bg-gray-400 tw-cursor-help tw-duration-100 tw-float-right' @click='AIDFillter'>Auto Fill</span>
        @endif
        @endif
    </label>
    @error($name)
    <label class='error tw-text-red-600' for='{{$prefix}}-{{$name}}'>
        {{ str_replace($name, $label, $message) }}
    </label>
    @enderror
    @if ($type === 'date')
    <input id='{{$prefix}}-{{$name}}' type="{{$type}}" name="{{$name}}" max="{{ date('Y-m-d') }}" @change='(e) => {resetError(e); {{ $changeFunc }}(e)}' @keydown='resetError' value='{{strlen($default) === 0 ? old($name) : $default}}'>
    @elseif ($type === 'textarea')
    <textarea id='{{$prefix}}-{{$name}}' name="{{$name}}" rows='3' @change='{{ $changeFunc }}' @keydown='resetError' value='{{strlen($default) === 0 ? old($name) : $default}}'>{{strlen($default) === 0 ? old($name) : $default}}</textarea>
    @elseif ($type === 'multiselect' || $type === 'select')
    <select id='{{$prefix}}-{{$name}}' name='{{$name}}{{$type === "multiselect" ? "[]" : ""}}' {{$type === "multiselect" ? "multiple" : ""}} @change='(e) => {resetError(e); {{ $changeFunc }}(e)}' @click=''>
        @foreach ($other as $code => $names)
        <option value='{{$code}}'
        @if ($code == $default)
        selected
        @elseif (is_array($default) && in_array($code, $default))
        selected
        @elseif ($code == old($name))
        selected
        @elseif (is_array(old($name)) && in_array($code, old($name)))
        selected
        @endif
        >{{$names}}</option>
        @endforeach
    </select>
    @elseif ($type === 'checkbox')
    <input id='{{$prefix}}-{{$name}}' type="{{$type}}" name="{{$name}}" @change='{{ $changeFunc }}' value='{{$value}}'
    @if ($default === $value)
        checked
    @elseif (old($name) === $value)
        checked
    @endif
    >
    @else
    <input id='{{$prefix}}-{{$name}}' type="{{$type}}" name="{{$name}}" min='1' @change='{{ $changeFunc }}' @keydown='resetError' value='{{strlen($default) === 0 ? old($name) : $default}}'>
    @endif
</div>