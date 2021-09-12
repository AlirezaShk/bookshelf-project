<script>
    const ArchiveCrawlerCurrentFilters = require('./ArchiveCrawlerCurrentFilters.vue').default
    export default {
        /**
        * Archive Crawler
        * 
        * This Vue component is in charge of soft filtering (volatile) and
        * applying hard filters (submitted filters through api, read from session).
        * 
        **/
        props: {
            type: {
                type: String,
            },
            perPage: {
                type: Number,
                default: 15
            },
            resultPages: {
                type: Object,
            },
            applyFilter: {
            },
            isMounted: {
                type: Boolean,
                default: false
            },
            sortBy: {
                type: Number,
                default: 0
            },
            filterTypes: {
                type: Array
            },
        },
        components: {
            CurrentFilters : ArchiveCrawlerCurrentFilters,
        },
        data: function() {
            return {
                resultPages: this.resultPages,
                filterTypes: this.filterTypes,
                type: this.type,
            }
        },
        methods: {
            /**
             * Instead of submission, use app.apply() to connect to API
             * 
             * @function app.applyFilter()
             **/

            beforeSubmit: function (event) {
                event.preventDefault();
                this.applyFilter(
                    $("#filterType").val().trim(), 
                    $("#search-form input[name='filter']").val().trim(),
                    this.type
                );
            },

            /**
             * Soft filter the results table rows and form pages
             * 
             * @function ArchiveCrawler.apply()
             **/

            filterIt: function () {
                this.$emit('searching');
                let that = this;

                //Debounce it to ensure user has stopped typing which results in reduced workload
                let k = _.debounce(function () {
                    //Get Data
                    let input = $("#search-input").val();
                    let table = $(".results-table")[0];
                    let rows = $(table).find("tbody > tr:not(#zero-result):not(#table-loader):not(.filtered)");
                    $(rows).removeClass('included');
                    //Sort Data
                    let sortCol = $(".results-table th.sort-by")[0];
                    let sortByIndex = $(".results-table th").index(sortCol);
                    if(!$(sortCol).hasClass('sort-applied')) {
                        let reverse = $(sortCol).hasClass('sort-reversed');
                        let is_total_books = (
                            ($($(rows[0]).find("td:not(.tw-hidden)")[sortByIndex]).data('cat') == 'books') || 
                            ($($(rows[0]).find("td:not(.tw-hidden)")[sortByIndex]).data('cat') == 'id')
                        );
                        rows = rows.sort(function (a, b) {
                            let txta = $(a).find("td:not(.tw-hidden)")[sortByIndex].innerText;
                            let txtb = $(b).find("td:not(.tw-hidden)")[sortByIndex].innerText;
                            let r;
                            //Id Column
                            if(sortByIndex !== 0)
                                r = txta.localeCompare(txtb);
                            //Other columns
                            else{
                                txta = parseInt(txta);
                                txtb = parseInt(txtb);
                                if( txta > txtb ) r = -1;
                                else if ( txta < txtb ) r = 1;
                                else r = 0;
                            }
                            if (is_total_books) {
                                r *= -1;
                            }
                            if (reverse) {
                                r *= -1;
                            }
                            return r;
                        });
                        $(sortCol).addClass('sort-applied');
                    }
                    //Apply filter and extract valid and total rows
                    let valid_rows = [], total_rows = [];
                    for(let i = 0; i < rows.length; i++) {
                        //case insensitive search for the input value inside each row of the results table
                        if (that.apply(rows[i], input) != -1) {
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
                        for(let i = 0; i < Math.min(that.perPage, valid_rows.length); i++) {
                            pages[j].push(valid_rows[i + j*that.perPage]);
                            k++;
                        }
                    } while(valid_rows.length > k);
                    that.$emit('searched', {total: total_rows, valid: pages});
                }, 500);
                k();
            },

            /**
             * Apply the Soft Filter
             * 
             * @param Object subject
             * Table rows to apply the soft filteration to.
             * 
             * @param String | undefined type
             * By default, it will pick the value from #filterType, unless provided.
             * This is the attribute short code which is the type of filteration.
             * 
             * @return -1 | !(-1)
             **/

            apply: function (subject, input, type = undefined) {
                if(input.trim().length === 0) return 1;
                if (type == undefined) {
                    type = $("#filterType").val();
                }
                type = type.trim();
                switch(type) {
                    case 'f':
                        return subject.textContent.search(new RegExp(input, 'i'));
                    case 'id':
                        let id = $(subject).find('[data-cat=id]')[0];
                        return id.textContent == input ? 1 : -1;
                    case 'l':
                        let langs = $(subject).find('[data-cat=langs]')[0].textContent;
                        if ($(subject).find('[data-cat=olang]').length > 0) 
                            langs += $(subject).find('[data-cat=olang]')[0].textContent;
                        return langs.search(new RegExp(input, 'i'));
                    case 'ud':
                        let updateD = $(subject).find('[data-cat=updated_at]')[0];
                        return updateD.textContent.search(new RegExp(input, 'i'));
                    case 'cd':
                        let createD = $(subject).find('[data-cat=created_at]')[0];
                        return createD.textContent.search(new RegExp(input, 'i'));
                    // books
                    case 't':
                        let title = $(subject).find('[data-cat=name]')[0];
                        return title.textContent.search(new RegExp(input, 'i'));
                    case 'i':
                        let isbn = $(subject).find('[data-cat=isbn]')[0];
                        return isbn.textContent.search(new RegExp(input, 'i'));
                    case 'g':
                        let genre = $(subject).find('[data-cat=genre]')[0];
                        return genre.textContent.search(new RegExp(input, 'i'));
                    case 'au':
                        let author = $(subject).find('[data-cat=author]')[0];
                        return author.textContent.search(new RegExp(input, 'i'));
                    case 'ai':
                        let author_id = $(subject).find('[data-cat=author_id]')[0];
                        return author_id.textContent == input ? 1 : -1;
                    case 'rd':
                        let releaseD = $(subject).find('[data-cat=release_date]')[0];
                        return releaseD.textContent.search(new RegExp(input, 'i'));
                    // authors
                    case 'n':
                        let name = $(subject).find('[data-cat=name]')[0];
                        return name.textContent.search(new RegExp(input, 'i'));
                    case 'o':
                        let origin = $(subject).find('[data-cat=origin]')[0];
                        return origin.textContent.search(new RegExp(input, 'i'));
                    case 'b':
                        let birth = $(subject).find('[data-cat=birth]')[0];
                        return birth.textContent.search(new RegExp(input, 'i'));
                    case 'd':
                        let death = $(subject).find('[data-cat=death]')[0];
                        return death.textContent.search(new RegExp(input, 'i'));
                }
            },
        },

        /**
         * In the mount function, firstly await the hard filters to be
         * applied via ArchiveCrawler.CurrentFilters. Then set the 
         * onlick event handler on the headings of the table that will
         * manage the sorting process.
         * 
         * @function ArchiveCrawler.filterIt()
         **/

        mounted: async function() {
            await (this.$refs.CurrentFilters.isMounted === true)
            this.filterIt();
            this.isMounted = true;
            let headings = $('.results-table thead th:not(:last-child)');
            let that = this;
            $(headings).click(function(e){
                $('th.sort-applied').removeClass('sort-applied');
                if(!$(e.target).hasClass('sort-by')) {
                    $("th.sort-by").removeClass('sort-by');
                    $(e.target).addClass('sort-by');
                } else { 
                    $(e.target).toggleClass('sort-reversed'); 
                }
                that.filterIt();
            });
        },
    }
</script>