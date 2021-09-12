<div class='tw-float-left tw-w-full tw-grid lg:tw-grid-cols-5 sm:tw-grid-cols-3 md:tw-grid-rows-2 sm:tw-grid-rows-2 tw-mb-2 md:tw-gap-2 sm:tw-gap-2' style="grid-template-rows: auto;">
<archive-exporter inline-template base-url='/{{$type}}/export'>
    <form class='tw-float-left tw-w-full tw-grid tw-grid-cols-3 lg:tw-col-start-2 md:tw-col-start-1 md:tw-col-span-3 sm:tw-col-start-1 sm:tw-col-span-3' method='post' :action='action_url' target="_blank" @submit="beforeSubmit"  style='min-width: 300px;'>
        @csrf
        @error('fields')
        <label class='error tw-text-red-600 tw-row-start-2 tw-col-start-1' for='export-fields-selector'>
            {{ $message }}
        </label>
        @enderror
        <field-includer :fields='{{ json_encode($fields) }}'></field-includer>
        @error('export_type')
        <label class='error tw-text-red-600 tw-row-start-2 tw-col-start-2' for='exportTypeSelector'>
            {{ $message }}
        </label>
        @enderror
        <select class='tw-col-span-1' name='export_type' id='exportTypeSelector' @change='changeURL' v-model='type'>
            <option value='undefined' selected>Export Type</option>
            @foreach($exportTypes as $eType)
            <option value="{{ strtolower($eType) }}">{{ $eType }}</option>
            @endforeach 
        </select>
        <button button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Export</button>
        <input type='hidden' id='export-ids' name='ids'>
        <input type='hidden' id='export-fields' name='fields'>
    </form>
</archive-exporter>
<archive-crawler inline-template ref="ArchiveCrawler" :apply-filter='applyFilter' :type='"{{ $type }}"' :filter-types='{{ json_encode($allSearchFilters) }}' @searching='resultsTableLoading' @searched='searchCallBack'>
    <form id='search-form' class='tw-float-left tw-w-full tw-grid tw-grid-cols-6 tw-col-span-3 lg:tw-col-start-2 md:tw-row-start-2 sm:tw-row-start-2 sm:tw-col-start-1' method='get' @submit='beforeSubmit' style="grid-template-rows: auto;">
        <span class='tw-relative tw-float-left tw-w-full tw-col-start-2 tw-col-span-3 md:tw-col-span-5 sm:tw-col-span-5 md:tw-col-start-1 sm:tw-col-start-1'>
            <input class='tw-w-full tw-relative tw-pl-48' id='search-input' type='text' name='filter' @input='filterIt'>
            <select class='tw-absolute tw-left-0 tw-w-44' name='filterType' id='filterType'>
                <option v-for='ft in filterTypes' :value='ft.value'>@{{ ft.label }}</option>
            </select>
        </span>
        @csrf
        <button class='tw-h-full tw-w-full tw-mx-2 tw-rounded tw-p-2 tw-bg-gray-200 tw-cursor-pointer hover:tw-bg-gray-300 tw-duration-200' type='submit' name='export_submit'>Apply</button>
        @error('ids')
        <label class='error tw-text-red-600 tw-row-start-2 tw-col-span-6' for='search-input'>
            {{ $message }}
        </label>
        @enderror
        <current-filters :apply="apply" :filters='{{ json_encode($sessionFilters) }}' ref='CurrentFilters' :type='type'></current-filters>
    </form>
</archive-crawler>
</div>