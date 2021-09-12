/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap.js');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('archive-exporter', require('./components/ArchiveExporter.vue').default);
const ArchiveExporter = require('./components/ArchiveExporter.vue').default;
const ArchiveCrawler = require('./components/ArchiveCrawler.vue').default;
const Modal = require('./components/Modal.vue').default;
const FormHandler = require('./components/FormHandler.vue').default;
const SingleEntry = require('./components/SingleEntry.vue').default;
const Loader = require('./components/Loader.vue').default;
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
$.fn.extend({
    toggleText: function(a, b){
        return this.text(this.text() == b ? a : b);
    }
});

const app = new Vue({
    el: '#app',
    props: {
        isLoading: {
            type: Boolean,
            default: true
        },
        resultsTableIsLoading: {
            type: Boolean,
            default: true
        }
    },
    data: {
        targetID: NaN,
        target: null,
        showModal: false,
        showDeleteModal: false,
        showEditModal: false,
        modalDetail: null,
        showBack: false,
        showNext: true,
        archivePage: 0,
        totalPages: 0,
    },
    components: {
        ArchiveExporter,
        ArchiveCrawler,
        Modal,
        FormHandler,
        SingleEntry,
        Loader
    },
    mounted() {
        $('.results-table thead th:eq(0)').click();
        this.$set(this, 'isLoading', false);
    },
    methods: {
        /**
        * Turn the pages of the table rows of the results table.
        * 
        * @param Number page
        * An integer in [-1, 0, +1]. 
        * +1/-1 turn page of the results table to the next/previous one
        * 0 go  to the first page
        **/

        turnPage: function (page) {
            let table = $('.results-table')[0];
            switch(page) {
                case 0:
                    break;
                default:
                    this.$set(this, 'resultsTableIsLoading', true);
                    page = this.archivePage + page;
                    break;
            }
            this.archivePage = page;
            $(table).find('tbody > tr:not(#table-loader)').addClass('tw-hidden');
            $(table).find('tbody > tr[page='+page+']').removeClass('tw-hidden');
            $(table).find('tbody > tr#zero-result').addClass('tw-hidden');
            if (this.totalPages == 1) {
                this.showBack = false;
                this.showNext = false;
                if ($(table).find('tbody > tr.tw-hidden').length == $(table).find('tbody > tr:not(#table-loader)').length) {
                    $(table).find('tbody > tr#zero-result').removeClass('tw-hidden');
                }
            } else if (this.totalPages == page+1) {
                this.showBack = true;
                this.showNext = false;
            } else if (page == 0) {
                this.showBack = false;
                this.showNext = true;
            } else {
                this.showBack = true;
                this.showNext = true;
            }
            this.$set(this, 'resultsTableIsLoading', false);
        },

        /**
        * Apply the pagination of the results on the results table and
        * its rows/
        * 
        * @param Object page
        * Has two keys: 
        *   1- valid: 
        *       Paginated valid table rows
        *   2- total:
        *       All table rows
        * 
        * @function app.turnPage()
        **/

        paginateResults: function (pages) {
            let table = $('.results-table')[0];
            $(table).find('tbody > tr:not(#zero-result):not(#table-loader)').remove();
            let j = 0, k = 0;
            for(let i = 0; i < pages.total.length; i++) {
                $(pages.total[i]).removeClass('odd').removeClass('even');
                try {
                    if (pages.valid[j].includes(pages.total[i])){
                        $(pages.total[i]).attr('page', j);
                        $(pages.total[i]).addClass(k % 2 ? 'even' : 'odd');
                        if (++k == pages.valid[j].length) {
                            k = 0;
                            j++;
                        }
                    } else {
                        $(pages.total[i]).attr('page','');
                    }
                } catch (e) {
                    $(pages.total[i]).attr('page','');
                }
                $(table).find('tbody').append(pages.total[i]);
            }
            if (pages.valid == undefined)
                this.totalPages = 1;
            else
                this.totalPages = pages.valid.length;
            this.showBack = false;
            this.showNext = true;
            this.turnPage(0);
        },

        /**
        * Apply a hard filter (store in session) via api.{type}.filter.
        * 
        * @param String type
        * Name (key) of the filtered attribute
        * 
        * @param String keyword
        * Value of the filtered attribute
        *
        * @param String archive
        * Type of the resource
        **/

        applyFilter: function(type, keyword, archive){
            let data = {
                filterType : type,
                filterData : keyword,
            };
            let url = "/api/" + archive + "/filter";
            $.ajax({
                url: url,
                type: 'PUT',
                data: data,
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        window.location = '/' + archive + '/list';
                    }
                }
            });
        },

        /**
        * Display navigation bar extra links
        **/

        showExtra: function (e) {
            let nav = $('nav.page-navbar')[0];
            let target = e.target;
            if (!$(target).hasClass('extra-link-wrapper')) {
                let index = $(nav).find(".nav-item").index(e.target);
                let extraLinks = $(nav).find('.extra-link-wrapper.tw-col-start-'+(index+1));
                if (extraLinks) {
                    $(nav).attr('showing-extra', index);
                    extraLinks.addClass('shown');
                }
            } else {
                let extraLinks = $(target);
                if (!extraLinks.hasClass('shown')) {
                    extraLinks.addClass('shown');
                }
                extraLinks.addClass('locked');
            }
        },

        /**
        * Hide navigation bar extra links
        **/

        hideExtra: function (e) {
            let nav = $('nav.page-navbar')[0];
            let target = e.target;
            if (!$(target).hasClass('extra-link-wrapper')) {
                let index = $(nav).find(".nav-item").index(e.target);
                let prevIndex = parseInt($('nav').attr('showing-extra'));
                if (index === prevIndex) {
                    let extraLinks = $(nav).find('.extra-link-wrapper.tw-col-start-'+(index+1));
                    if(!extraLinks.hasClass('locked'))
                        extraLinks.removeClass('shown');
                }
            } else {
                let extraLinks = $(target);
                extraLinks.removeClass('shown').removeClass('locked');
            }
        },

        /**
        * Callback to set the app.resultsTableIsLoading state to true.
        * This will display the results-table Loader.
        **/
        
        resultsTableLoading: function () {
            this.$set(this, 'resultsTableIsLoading', true);
        },

        /**
        * After searching and fliteration process is done, (sorted)
        * data is transfered here to undergo pagination.
        * 
        * @function app.pageinateResults
        **/

        searchCallBack: function (e) {
            this.paginateResults({
                total: e.total.slice(0),
                valid: e.valid.slice(0),
            });
        }
    }
});
