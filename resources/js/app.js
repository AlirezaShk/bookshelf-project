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
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        targetID: NaN,
        target: null,
        showModal: false,
        showDeleteModal: false,
        showEditModal: false,
        modalDetail: null,
        showBack: false,
        showNext: true
    },
    components: {
        ArchiveExporter,
        ArchiveCrawler,
        Modal
    },
    mounted() {
        this.$watch(
          () => {
            return this.$refs.ArchiveCrawler.resultPages
            },
          (val) => {
            if(val == undefined) return;
            this.paginateResults({
                total: val.total.slice(0),
                valid: val.valid.slice(0),
            });
          });
    },
    methods: {
        turnPage: function (page) {
            let table = $('.results-table')[0];
            switch(page) {
                case 0:
                    break;
                default:
                    page = parseInt($(table).attr('page')) + page;
                    break;
            }
            $(table).attr('page', page);
            $(table).find('tbody > tr').addClass('tw-hidden');
            $(table).find('tbody > tr[page='+page+']').removeClass('tw-hidden');
            $(table).find('tbody > tr#zero-result').addClass('tw-hidden');
            if ($(table).attr('total') == 1) {
                this.showBack = false;
                this.showNext = false;
                if ($(table).find('tbody > tr.tw-hidden').length == $(table).find('tbody > tr').length) {
                    $(table).find('tbody > tr#zero-result').removeClass('tw-hidden');
                }
            } else if ($(table).attr('total') == page+1) {
                this.showBack = true;
                this.showNext = false;
            } else if (page == 0) {
                this.showBack = false;
                this.showNext = true;
            } else {
                this.showBack = true;
                this.showNext = true;
            }
        },
        paginateResults: function (pages) {
            let table = $('.results-table')[0];
            $(table).find('tbody > tr:not(#zero-result)').remove();
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
                $(table).attr('total', 1);
            else
                $(table).attr('total', pages.valid.length);
            this.showBack = false;
            this.showNext = true;
            this.turnPage(0);
        },
    }
});
