@section('navbar')
<nav class='page-navbar tw-fixed tw-float-left tw-block tw-w-3/4 tw-pb-2 tw-h-12 tw-mx-auto tw-overflow-visible tw-grid tw-auto-cols-max tw-grid-rows-2 tw-auto-cols-max tw-grid-flow-col tw-top-0 tw-inset-x-0 tw-px-5 tw-rounded-b-full tw-bg-teal-400 tw-gap-1' style='' showing-extra='-1'>
    @foreach($preNavLinks as $link)
    <div class='nav-item pre-link-wrapper tw-float-left tw-row-start-1' @mouseenter='showExtra' @mouseleave="hideExtra">
        <a href='{{ $link["url"] }}' class='pre-link tw-float-left'>
            <span class='tw-inline-block tw-p-3'>
                {{ $link["title"] }}
            </span>
        </a>
    </div>
    @endforeach
    <span class='nav-item currentPage tw-inline-block tw-p-3 tw-float-left  tw-row-start-1' @mouseenter='showExtra' @mouseleave="hideExtra">
        {{ $pageTitle }}
    </span>
    @foreach($postNavLinks as $link)
    <a href='{{ $link["url"] }}' class='nav-item post-link tw-float-left  tw-row-start-1' @mouseenter='showExtra' @mouseleave="hideExtra">
        <span class='tw-inline-block tw-p-3'>
            {{ $link["title"] }}
        </span>
    </a>
    @endforeach
    @foreach($extraNavLinks as $col => $navLinksArray)
    <div class='extra-link-wrapper tw-col-start-{{$col+1}} tw-row-start-2 tw-float-left tw-grid tw-h-auto tw-grid-flow-row' @mouseenter='showExtra' @mouseleave="hideExtra">
        @foreach($navLinksArray as $row => $link)
        <a href='{{ $link["url"] }}' class='post-link tw-float-left tw-row-start-{{$row+1}}'>
            <span class='tw-inline-block tw-p-3'>
                {{ $link["title"] }}
            </span>
        </a>
        @endforeach
    </div>
    @endforeach
</nav>
@endsection