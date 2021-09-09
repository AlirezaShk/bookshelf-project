<div class='tw-float-left tw-w-full tw-grid lg:tw-grid-cols-5 sm:tw-grid-cols-3 md:tw-grid-rows-2 sm:tw-grid-rows-2 tw-mb-2 md:tw-gap-2 sm:tw-gap-2' style="grid-template-rows: auto;">
<archive-exporter inline-template base-url='export'>
    <form class='tw-float-left tw-w-full tw-grid tw-grid-cols-3 lg:tw-col-start-2 md:tw-col-start-1 md:tw-col-span-3 sm:tw-col-start-1 sm:tw-col-span-3' method='post' :action='action_url' target="_blank" @submit="beforeSubmit"  style='min-width: 300px;'>
        @csrf
        <field-includer :fields='{{ json_encode($fields) }}'></field-includer>
        <select class='tw-col-span-1' name='export_type' id='exportTypeSelector' @change='changeURL' v-model='type'>
            <option value='' selected>Export Type</option>
            @foreach($exportTypes as $type)
            <option value="{{ strtolower($type) }}">{{ $type }}</option>
            @endforeach 
        </select>
        <button button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Export</button>
        <input type='hidden' id='export-ids' name='ids'>
        <input type='hidden' id='export-fields' name='fields'>
    </form>
</archive-exporter>
<archive-crawler inline-template ref="ArchiveCrawler">
    <form id='search-form' class='tw-float-left tw-w-full tw-grid tw-grid-cols-6 tw-col-span-3 lg:tw-col-start-2 md:tw-row-start-2 sm:tw-row-start-2 sm:tw-col-start-1' method='get' @submit='beforeSubmit' style="grid-template-rows: auto;">
        <span class='tw-relative tw-float-left tw-w-full tw-col-start-2 tw-col-span-3 md:tw-col-span-5 sm:tw-col-span-5 md:tw-col-start-1 sm:tw-col-start-1'>
            <input class='tw-w-full tw-relative tw-pl-48' type='text' name='filter' @input='filterIt' :value='filter'>
            <select class='tw-absolute tw-left-0 tw-w-44' name='filterType' id='filterType'>
                <option value='f'>Freeword</option>
                <option value='t'>Title</option>
                <option value='g'>Genre</option>
                <option value='au'>Author's Name</option>
                <option value='rd'>Release Date</option>
                <option value='ud'>Last Update Date</option>
                <option value='cd'>Create Date</option>
            </select>
        </span>
        @csrf
        <button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Apply</button>
        <current-filters :apply="apply" :filters='{{ json_encode(session("books.filter")) }}' ref='CurrentFilters'></current-filters>
    </form>
</archive-crawler>
</div>