@foreach($records as $record)
<ul>
	<li>{{$record->id}}</li>
	<li>{{$record->fname}} {{$record->lname}}</li>
	<li>{{$record->origin}}</li>
</ul>
@endforeach
{{ $records->links() }}