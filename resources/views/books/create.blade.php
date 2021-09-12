@include ('components.nav-bar')
@extends ('layout')

@section ('title')
Edit Book | 
@endsection

@section ('content')
<div id='book-create-page' class='tw-inset-x-0 tw-mx-auto tw-absolute tw-container tw-h-full tw-pt-16'>
	@yield('navbar')
	<form-handler inline-template id='create-book'>
	<form :id='id' method='POST' class='tw-grid tw-grid-rows-auto tw-w-full tw-inset-x-0 tw-relative tw-float-left'>
		@csrf
		@method('PUT')
		@foreach ($fields as $key => $field)
		<x-form-field 
		prefix='book' 
		name='{{$key}}' 
		label='{{$field["label"]}}' 
		type='{{$field["type"]}}' 
		change-func='{{ $field["change"] }}'
		other='{{json_encode($field["other"])}}'
		required='{{$field["required"]}}'
		value='{{$field["value"] ?? NULL}}'
		></x-form-field>
		@endforeach
		<div class="modal-footer tw-relative tw-h-12 tw-border-0">
		  <button class="tw-rounded tw-bg-gray-200 hover:tw-bg-gray-400 tw-rounded tw-absolute tw-left-3/4 tw-m-auto tw-h-3/4 tw-w-1/5 tw-duration-200" type='submit'>
		    Submit
		  </button>
		</div>
	</form>
	</form-handler>
</div>
@endsection