<script>
    const ArchiveCrawlerCurrentFilters = require('./ArchiveCrawlerCurrentFilters.vue').default
    export default {
        props: {
            baseUrl: {
                type: String,
            },
            perPage: {
                type: Number,
                default: 15
            },
            resultPages: {
                types: Object,
            }
        },
        components: {
            CurrentFilters : ArchiveCrawlerCurrentFilters,
        },
        data: function() {
            return {
                filter: '',
            }
        },
        methods: {
            changeURL: function () {
                this.action_url = this.baseUrl + '/' + this.type;
            },
            beforeSubmit: function (event) {
                event.preventDefault();
                let data = {
                    filterType : $("#filterType").val(),
                    filterData : $("#search-form input[name='filter'").val(),
                    _method : 'PUT',
                    _token : $("#search-form input[name='_token']").val()
                };
                $.post('/books/filter', data, function (data) {
                    if(data.success) {
                        location.reload();
                    }
                }, 'json');
            },
            filterIt: _.debounce(function () {
                let input = $("#search-form input[name=filter]").val();
                let table = $(".results-table")[0];
                let rows = $(table).find("tbody > tr:not(#zero-result):not(.filtered)");
                $(rows).removeClass('included');
                let valid_rows = [], total_rows = [];
                for(let i = 0; i < rows.length; i++) {
                    //case insensitive search for the input value inside each row of the results table
                    if (this.apply(rows[i], input) != -1) {
                        valid_rows.push(rows[i]);
                        $(rows[i]).addClass('included');
                    }
                    total_rows.push(rows[i]);
                }
                let pages = [];
                let j = -1;
                let k = 0;
                do {
                    pages.push([]);
                    j++;
                    for(let i = 0; i < Math.min(this.perPage, valid_rows.length); i++) {
                        pages[j].push(valid_rows[i + j*this.perPage]);
                        k++;
                    }
                } while(valid_rows.length > k);
                this.resultPages = {total: total_rows, valid: pages};
            }, 500),
            apply: function (subject, input, type = undefined) {
                if (type == undefined) {
                    type = $("#filterType").val();
                }
                type = type.trim();
                switch(type) {
                    case 'f':
                        return subject.textContent.search(new RegExp(input, 'i'));
                    case 't':
                        let title = $(subject).find('[data-cat=name]')[0];
                        return title.textContent.search(new RegExp(input, 'i'));
                    case 'g':
                        let genre = $(subject).find('[data-cat=genre]')[0];
                        return genre.textContent.search(new RegExp(input, 'i'));
                    case 'au':
                        let author = $(subject).find('[data-cat=author]')[0];
                        return author.textContent.search(new RegExp(input, 'i'));
                    case 'rd':
                        let releaseD = $(subject).find('[data-cat=release_date]')[0];
                        return releaseD.textContent.search(new RegExp(input, 'i'));
                    case 'ud':
                        let updateD = $(subject).find('[data-cat=updated_at]')[0];
                        return updateD.textContent.search(new RegExp(input, 'i'));
                    case 'cd':
                        let createD = $(subject).find('[data-cat=created_at]')[0];
                        return createD.textContent.search(new RegExp(input, 'i'));
                }
            },
        },
        mounted: async function() {
            await (this.$refs.CurrentFilters.isMounted === true)
            this.filterIt();
        }
    }
</script>