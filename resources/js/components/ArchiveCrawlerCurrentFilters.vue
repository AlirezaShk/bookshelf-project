<template>
    <div id='currentFilters' class='tw-w-full tw-col-span-6 tw-h-max tw-float-left'>
        <span v-for='(obj, key) in filters' class='tw-p-2 tw-relative tw-float-left tw-rounded tw-bg-gray-200 hover:tw-bg-red-200 tw-duration-100 tw-cursor-pointer tw-mt-2 tw-mr-2' :data-key='key' @click='mark'>
	        {{ preFormatFilters(obj) }}
	    </span>
    </div>
</template>
<script>
	export default {
		props: [
			'apply',
			'filters'
		],
		data : function () {
			return {
				filters: this.filters,
				isMounted: false,
			}
		},
		methods: {
			mark: function(e) {
				let filter = e.target;
				if (!$(filter).hasClass('marked-for-delete')) {
					$(filter).addClass('marked-for-delete');
					setTimeout(function() {
						$(filter).removeClass('marked-for-delete');
					}, 2000);
				} else {
					$.post('books/filter', {
						_method: 'DELETE',
						responseType: 'json',
						key: $(filter).data('key'),
					}, function (data) {
						if (data.success) {
							location.reload();
						}
					}, 'json');
				}
			},
			preFormatFilters: function(filter) {
        		if ('f' in filter) {
					return "f: " + filter.f;
        		} else if ('g' in filter) {
					return "g: " + filter.g;
        		} else if ('au' in filter) {
					return "au: " + filter.au;
        		} else if ('t' in filter) {
					return "t: " + filter.t;
        		} else if ('cd' in filter) {
					return "cd: " + filter.cd;
        		} else if ('rd' in filter) {
					return "rd: " + filter.rd;
        		} else if ('ud' in filter) {
					return "ud: " + filter.ud;
        		}
			},
            formatFilters: function(filter) {
                let parts = filter.textContent.split(':');
                $(filter).attr('data-type', parts[0].trim());
                switch(parts[0].trim()) {
                    case 'f':
                        parts[0] = 'Freeword';
                        break;
                    case 't':
                        parts[0] = 'Title';
                        break;
                    case 'g':
                        parts[0] = 'Genre';
                        break;
                    case 'au':
                        parts[0] = 'Author\'s Name';
                        break;
                    case 'rd':
                        parts[0] = 'Release Date';
                        break;
                    case 'ud':
                        parts[0] = 'Last Update Date';
                        break;
                    case 'cd':
                        parts[0] = 'Create Date';
                        break;
                }
                $(filter).text(parts.join(':'));
            },
            hardFilterIt: function () {
                let filters = $("#currentFilters");
                let each = $("#currentFilters span");
                let table = $(".results-table")[0];
                let rows = $(table).find("tbody > tr:not(#zero-result)");
                $(rows).removeClass('filtered');
                for (let i = 0; i < each.length; i++) {
                    let parts = each[i].textContent.split(':');
                    let filterType;
                    if ($(each[i]).data('type') != undefined)
                    	filterType = $(each[i]).data('type');
                	else
                    	filterType = parts[0];
                    for(let j = 0; j < rows.length; j++) {
                        //case insensitive search for the input value inside each row of the results table
                        if (this.apply(rows[j], parts[1].trim(), filterType) == -1) {
                            $(rows[j]).addClass('filtered');
                        }
                    }
                    this.formatFilters(each[i]);
                }
            }
		},
		mounted() {
			this.hardFilterIt();
			this.isMounted = true;
		}
	}
</script>