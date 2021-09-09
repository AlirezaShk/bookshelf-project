<td class='tw-grid tw-grid-cols-2 tw-gap-1'>
    <span class='tw-inset-x-0 tw-mx-auto tw-inline-block md:tw-w-3/4 sm:tw-w-full tw-h-12 tw-bg-center tw-bg-no-repeat tw-bg-gray-300 tw-rounded hover:tw-bg-red-400 tw-duration-200 tw-cursor-pointer tw-border tw-border-gray-400' style='background-image: url("icon/delete.svg"); background-size: 60%;' @click='showModal = true; target = {{$record}}; modalDetail = false;'>
    </span>
    <span class='tw-inset-x-0 tw-mx-auto tw-inline-block md:tw-w-3/4 sm:tw-w-full tw-h-12 tw-bg-center tw-bg-no-repeat tw-bg-gray-300 tw-rounded hover:tw-bg-gray-400 tw-duration-200 tw-cursor-pointer tw-border tw-border-gray-400' style='background-image: url("icon/information-sign.svg"); background-size: 60%;' @click='showModal = true; target = {{$record}}; modalDetail = true;'>
    </span>
</td>