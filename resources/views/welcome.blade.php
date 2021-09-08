@extends ('layout')

@section ('content')
<div class="tw-fixed tw-inset-x-0 tw-w-screen tw-h-screen" id='welcome-page-bg'>
	@for ($i = 0; $i < 20; $i++)
    <span></span>
    @endfor
</div>
<div class='tw-fixed tw-inset-x-0 tw-top-10 tw-w-max tw-mx-auto'>
	<h1 id='site-title' class='lg:tw-text-9xl tw-text-7xl tw-tracking-widest tw-text-center'>
		@foreach (str_split(config('app.name')) as $letter)
			<span class='tw-inline-block tw-bg-green-300 tw-rounded'>{{ $letter }}</span>
		@endforeach
	</h1>
</div>
<div class='tw-inline-block tw-fixed tw-inset-x-0 tw-h-max tw-w-max tw-mx-auto tw-bottom-2/4 tw-mt-auto tw-float-left'>
	<a id='archive_link'
	class='
	tw-inline-block 
	tw-text-2xl 
	tw-relative 
	tw-float-left 
	tw-border-l-2
	tw-border-r-2
	tw-border-solid 
	tw-border-transparent 
	hover:tw-no-underline 
	hover:tw-text-black 
	hover:tw-border-gray-300 
	hover:tw-rounded
	tw-duration-200 
	tw-p-4 
	' href="{{ url('/books') }}">Explore The Archive</a>
</div>
@endsection
