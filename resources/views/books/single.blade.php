@include ('components.nav-bar')
@extends ('layout')

@section ('title')
{{$pageTitle}} | 
@endsection

@section ('content')
<div id='book-single-page' class='tw-inset-x-0 tw-mx-auto tw-absolute tw-container tw-h-auto tw-min-h-full tw-float-left tw-pt-16'>
	@yield('navbar')
	<single-entry inline-template id='edit-book'>
	<form-handler inline-template :id='id' :toggle-details='toggleDetails' :begin-edit='beginEdit' :cancel-edit='cancelEdit' :confirm-edit='confirmEdit'>
	<form :id='id' method='POST' class='preview record-details tw-grid tw-auto-rows-auto tw-max-w-2xl md:tw-w-3/4 sm:tw-w-full tw-mx-auto tw-inset-x-0 tw-relative '>
		@csrf
		@method('PUT')
		@foreach ($fields as $key => $field)
		<div class='author-{{$key}}-wrapper tw-relative tw-grid  
		{{ !in_array($field["label"], $shownFirst) ? "tw-hidden extra" : "" }}
		'>
		@unless(in_array($key, ['created_at', 'updated_at', 'id']))
		<x-form-field 
		prefix='book' 
		name='{{$key}}' 
		label='{{$field["label"]}}' 
		type='{{$field["type"]}}' 
		change-func='{{ $field["change"] }}'
		other='{{json_encode($field["other"])}}'
		required='{{$field["required"]}}'
		value='{{$field["value"] ?? NULL}}'
		default='{{is_array($field["default"]) ? json_encode($field["default"]) : $field["default"]}}'
		></x-form-field>
		@else
		<div class='book-{{$key}}-container tw-w-full tw-relative tw-float-left tw-grid "tw-grid-rows-2" tw-mb-2' style='grid-template-rows: auto;'>
    		<label>
				{{$field["label"]}}
			</label>
    		<span class='tw-pl-4 tw-text-lg'>
				{{$field["default"]}}
			</span>
		</div>
		@endunless
			</div>
		@endforeach
		<div class='tw-w-full tw-mx-auto tw-inset-x-0 tw-relative tw-py-5 tw-h-auto tw-grid tw-grid-cols-2 tw-gap-4'>
			<button class='expand-btn tw-rounded tw-border-2 tw-border-gray-300 tw-bg-gray-200 hover:tw-bg-gray-300 tw-px-4 tw-py-2 tw-float-left tw-duration-200' @click='toggleDetails' type='button'>Expand</button>
			<button class='begin-edit-btn tw-rounded tw-border-2 tw-border-blue-300 tw-bg-blue-200 hover:tw-bg-blue-300 tw-px-4 tw-py-2 tw-float-left tw-duration-200' @click='beginEdit' type='button'>Edit</button>
			<button class='cancel-edit-btn tw-rounded tw-border-2 tw-border-red-300 tw-bg-red-200 hover:tw-bg-red-300 tw-px-4 tw-py-2 tw-float-left tw-duration-200' @click='cancelEdit' type='button'>Cancel</button>
			<button class='confirm-edit-btn tw-rounded tw-border-2 tw-border-green-300 tw-bg-green-200 hover:tw-bg-green-300 tw-px-4 tw-py-2 tw-float-left tw-duration-200' @click='confirmEdit' type='button'>Confirm</button>
		</div>
	</form>
	</form-handler>
	</single-entry>
</div>
@endsection