@extends ('layout')

@section ('title')
Books Archive | 
@endsection

@section ('content')
<div id='book-list-page' class='tw-inset-x-0 tw-mx-auto tw-absolute tw-container tw-h-full'>
	<div class='page-navbar tw-divide-x tw-float-left tw-block tw-h-max tw-w-full tw-mb-6'>
		@foreach($preNavLinks as $link)
		<a href='{{ $link["url"] }}' class='tw-float-left'>
			<span class='pre-link tw-inline-block tw-p-3'>
				{{ $link["title"] }}
			</span>
		</a>
		@endforeach
		<span class='tw-inline-block tw-p-3 tw-float-left last'>
			Books Archive
		</span>
		@foreach($postNavLinks as $link)
		<a href='{{ $link["url"] }}' class='tw-float-left'>
			<span class='post-link tw-inline-block tw-p-3'>
				{{ $link["title"] }}
			</span>
		</a>
		@endforeach
	</div>
	<x-search-bar fields='{{ json_encode(array_keys($records[0]->getAttributes())) }}' export-types='{{json_encode($export_types)}}'/>
	<table class='results-table tw-table-fixed tw-max-w-2xl md:tw-w-3/4 sm:tw-w-full tw-mx-auto tw-inset-x-0 tw-relative tw-border-collapse' page='0'>
	<colgroup>
		<col width="5">
		<col width="2">
		<col width="2">
	</colgroup>
	<thead class='tw-rounded-t'>
		<tr>
		@foreach($keys as $field)
			<th class='tw-rounded tw-border-2 tw-border-green-500 tw-bg-green-400 tw-p-2'>{{$field}}</th>
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
			@if (in_array(ucfirst($k), $keys))
			<td class='tw-overflow-auto' data-cat='{{$k}}'>{{$v}}</td>
			@else
			<td class='tw-hidden tw-overflow-auto' data-cat='{{$k}}'>{{$v}}</td>
			@endif
			@endforeach
			<td data-cat='author' class='tw-relative'>
				<a href='/author/{{$record->author->id}}' class='tw-table tw-absolute tw-inset-0 tw-h-full tw-w-full'>
					<span class='tw-table-cell tw-align-middle'>{{$record->author->fname . " " . $record->author->lname}}</span>
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
	</tbody>
	</table>
	<button class='tw-bg-gray-100 hover:tw-bg-gray-300' @click='turnPage(-1)' v-if='showBack'>Back</button>
	<button class='tw-bg-gray-100 hover:tw-bg-gray-300' @click='turnPage(+1)' v-if='showNext'>Next</button>
</div>
<modal v-if='showModal' @close='showModal = false' :modal-url='"/book/"' :target='target' :csrf-token='"{{csrf_token()}}"' :detail-mode='modalDetail'></modal>
@endsection