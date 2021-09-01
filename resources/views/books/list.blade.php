@foreach($records as $record)
<ul>
	<li>{{$record->id}}</li>
	<li>{{$record->name}}</li>
	<li>{{$record->author_id}}</li>
</ul>
@endforeach
{{ $records->links() }}
{{ json_encode($ids) }}