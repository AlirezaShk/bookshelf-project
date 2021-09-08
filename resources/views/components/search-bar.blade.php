<div class='tw-float-left tw-w-full tw-grid tw-grid-cols-5 md:tw-grid-rows-2 sm:tw-grid-rows-2 tw-mb-2 md:tw-gap-2 sm:tw-gap-2'>
<archive-exporter inline-template base-url='export' id-list-id='{{ $idListId }}'>
    <form class='tw-float-left tw-w-full tw-grid tw-grid-cols-3 md:tw-col-start-2 md:tw-col-span-3 sm:tw-col-start-2 sm:tw-col-span-3' method='post' :action='action_url' target="_blank" @submit="beforeSubmit"  style='min-width: 300px;'>
        @csrf
        <select class='tw-col-span-2' name='export_type' id='exportTypeSelector' @change='changeURL' v-model='type'>
            <option value='' selected>Select an option</option>
            @foreach($exportTypes as $type)
            <option value="{{ strtolower($type) }}">{{ $type }}</option>
            @endforeach 
        </select>
        <button button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Export</button>
        <input type='hidden' id='ids' name='ids'>
    </form>
</archive-exporter>
<archive-crawler inline-template id-list-id='{{ $idListId }}' ref="ArchiveCrawler">
    <form id='search-form' class='tw-float-left tw-w-full tw-grid tw-grid-cols-6 tw-col-span-3 tw-col-start-2 md:tw-row-start-2 sm:tw-row-start-2' method='get' @submit='beforeSubmit'>
        <span class='tw-relative tw-float-left tw-w-full tw-col-start-2 tw-col-span-3 md:tw-col-span-5 sm:tw-col-span-5 md:tw-col-start-1 sm:tw-col-start-1'>
            <input class='tw-w-full tw-relative tw-pl-48' type='text' name='filter' @input='filterIt' :value='filter'>
            <select class='tw-absolute tw-left-0 tw-w-44' name='filterType' id='filterType'>
                <option value='f'>Freeword</option>
                <option value='g'>Genre</option>
                <option value='au'>Author's Name</option>
                <option value='rd'>Release Date</option>
                <option value='ud'>Update Date</option>
                <option value='cd'>Create Date</option>
            </select>
        </span>
        <button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Apply</button>
    </form>
</archive-crawler>
</div>