@include ('components.nav-bar')
@extends ('layout')

@section ('title')
{{$pageTitle}} | 
@endsection

@section ('content')
<div id='author-list-page' class='tw-inset-x-0 tw-mx-auto tw-absolute tw-container tw-h-auto tw-min-h-full tw-float-left tw-pt-16'>
	@yield('navbar')
	<x-search-bar 
	fields='{{ json_encode($export_fields) }}' 
	export-types='{{json_encode($export_types)}}' 
	type='author'
	/>
	<table class='results-table tw-table-fixed tw-max-w-2xl md:tw-w-3/4 sm:tw-w-full tw-mx-auto tw-inset-x-0 tw-relative tw-border-collapse' :page='archivePage' :total='totalPages'>
	<colgroup>
		<col width="1" style="min-width: 1.5px;">
		<col width="4">
		<col width="1" style='min-width: 1.6px;'>
		<col width="2">
	</colgroup>
	<thead class='tw-rounded-t'>
		<tr>
		@foreach($keys as $k => $field)
			<th class='tw-rounded tw-border-2 tw-border-green-500 tw-bg-green-400 tw-p-2' key-index='{{$k}}'>{{$field}}</th>
		@endforeach
		</tr>	
	</thead>
	<tbody>
	<tr id='zero-result' class='tw-hidden tw-h-10 tw-bg-gray-100 hover:tw-bg-gray-300'>
		<td colspan='{{ count($keys); }}'>
			No Matching Result Found
		</td>
	</tr>
	@forelse($authors as $author)
		<tr data-id='{{$author->id}}'>
			@foreach($author->getAttributes() as $k => $v)
			<td class='tw-hidden tw-overflow-auto' data-cat='{{$k}}'>{{$v}}</td>
			@endforeach
			<td class='tw-overflow-auto' data-cat='id'>{{ $author->id }}</td>
			<td class='tw-overflow-auto' data-cat='name'>{{ $author->name }}</td>
			<td class='tw-overflow-auto hover:tw-color-link hover:tw-underline tw-cursor-pointer' @click='applyFilter("ai", "{{ $author->id }}", "book")' data-cat='books'>Total: {{ count($author->books) }}</td>
			<x-list-actions :type='"author"' :record='$author'/>
		</tr>
	@empty
		<tr>
			<td colspan='{{ count($keys); }}'>
				No Book Exists In the Archive Yet
			</td>
		</tr>
	@endforelse
	<tr id='table-loader' v-if='resultsTableIsLoading'>
		<td colspan="{{count($keys)}}">
			<loader top='55px'></loader>
		</td>
	</tr>
	</tbody>
	</table>
	<div class='tw-max-w-2xl md:tw-w-3/4 sm:tw-w-full tw-mx-auto tw-inset-x-0 tw-relative tw-py-5 tw-h-24'>
		<button class='tw-rounded tw-border-2 tw-border-color-gray-300 tw-bg-gray-200 hover:tw-bg-gray-300 tw-px-4 tw-py-2 tw-float-left tw-duration-200' @click='turnPage(-1)' v-if='showBack'>Back</button>
		<div class='tw-absolute tw-inset-0 tw-m-auto tw-w-min tw-h-5'>@{{ archivePage + 1 }}/@{{ totalPages }}</div>
		<button class='tw-rounded tw-border-2 tw-border-color-gray-300 tw-bg-gray-200 hover:tw-bg-gray-300 tw-px-4 tw-py-2 tw-float-right tw-duration-200' @click='turnPage(+1)' v-if='showNext'>Next</button>
	</div>
</div>
<modal 
v-if='showModal' 
@close='showModal = false' 
:modal-url='"/author/a:"' 
:target='target' 
:csrf-token='"{{csrf_token()}}"' 
:detail-mode='modalDetail' 
:apply-filter='applyFilter' 
:type='"author"'
>
    <h2 class='tw-col-span-2 tw-font-bold tw-text-lg'>@{{ target.fname + " " + target.lname}}</h2>
    <div class='tw-col-span-1'>From @{{ target.birth }}</div>
    <div class='tw-col-span-1'>To @{{ target.death !== null ? target.death : 'Present' }}</div>
    <div class='tw-col-span-2'>Born in: <a :href='"https://www.google.com/search?q="+target.origin+"+country"'>@{{ target.origin }}</a></div>
</modal>
@endsection