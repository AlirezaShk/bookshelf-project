@include ('components.nav-bar')
@extends ('layout')

@section ('title')
{{$pageTitle}} | 
@endsection

@section ('content')
<div id='book-list-page' class='tw-inset-x-0 tw-mx-auto tw-absolute tw-container tw-h-auto tw-min-h-full tw-float-left tw-pt-16'>
	@yield('navbar')
	<x-search-bar 
	fields='{{ json_encode($export_fields) }}' 
	export-types='{{json_encode($export_types)}}' 
	type='book'
	/>
	<table class='results-table tw-table-fixed tw-max-w-2xl md:tw-w-3/4 sm:tw-w-full tw-mx-auto tw-inset-x-0 tw-relative tw-border-collapse' :page='archivePage' :total='totalPages'>
	<colgroup>
		<col width="1" style="min-width: 1.2px;">
		<col width="4">
		<col width="3">
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
	@forelse($records as $record)
		<tr data-id='{{$record->id}}'>
			@foreach($record->getAttributes() as $k => $v)
			@unless($k === 'descrip')
			@if (in_array(ucfirst($k), $keys))
			<td class='tw-overflow-auto' data-cat='{{$k}}'>{{$v}}</td>
			@else
			<td class='tw-hidden tw-overflow-auto' data-cat='{{$k}}'>{{$v}}</td>
			@endif
			@endunless
			@endforeach
			<td data-cat='author' class='tw-relative'>
				<a href='/author/a:{{$record->author->id}}' class='tw-relative tw-inset-0 tw-h-full tw-w-full'>
					<span>{{$record->author->fname . " " . $record->author->lname}}</span>
				</a>
			</td>
			<x-list-actions :type='"book"' :record='$record'/>
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
:modal-url='"/book/b:"' 
:target='target' 
:csrf-token='"{{csrf_token()}}"' 
:detail-mode='modalDetail' 
:apply-filter='applyFilter' 
:type='"book"'
>
    <h2 class='tw-col-span-2 tw-font-bold tw-text-lg'>@{{ target.name }}</h2>
    <div class='tw-col-span-1'>By 
        <a :href='"/author/"+target.author.id'>@{{ target.author.fname + " " + target.author.lname }}</a>
    </div>
    <div class='tw-col-span-1'>Released @{{ target.release_date }}</div>
    <div class='tw-col-span-2'>Genre: <span id='genreSearcher' class='hover:tw-underline hover:tw-color-link tw-cursor-pointer' @click='genreSearch(target.genre)'>@{{ target.genre }}</span></div>
    <div class='tw-col-span-2 tw-line-clamp-2'>@{{ target.descrip }}</div>
</modal>
@endsection