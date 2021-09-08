<script>
    export default {
        props: {
            baseUrl: {
                type: String,
            },
            idListId: {
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
        data: function() {
            return {
                filter: '',
                originalIds: $('#'+this.idListId).val(),
            }
        },
        methods: {
            changeURL: function () {
                this.action_url = this.baseUrl + '/' + this.type;
            },
            beforeSubmit: function (event) {
                event.preventDefault();
                $('#'+this.idListId).val();
            },
            filterIt: _.debounce(function () {
                let input = $("#search-form input[name=filter]").val();
                let table = $(".results-table")[0];
                let rows = $(table).find("tbody > tr:not(#zero-result)");
                let valid_rows = [], total_rows = [];
                for(let i = 0; i < rows.length; i++) {
                    //case insensitive search for the input value inside each row of the results table
                    if (rows[i].textContent.search(new RegExp(input, "i")) != -1) {
                        valid_rows.push(rows[i]);
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
        },
        mounted: function() {
            this.filterIt();
        }
    }
</script>