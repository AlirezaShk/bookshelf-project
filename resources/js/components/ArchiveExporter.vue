<script>
    const ArchiveExporterFieldIncluder = require('./ArchiveExporterFieldIncluder.vue').default
    export default {
        /**
        * Archive Exporter
        * 
        * This Vue component is in charge of determining the desirable
        * attributes and file type for api.{type}.export.
        **/
        props: ['baseUrl'],
        components: {
            FieldIncluder : ArchiveExporterFieldIncluder
        },
        data: function() {
            return {
                type: 'undefined',
                action_url: this.baseUrl + '/' + this.type,
            }
        },
        methods: {
            /**
            * Change the action url of the submission form for exportion.
            **/

            changeURL: function () {
                this.action_url = this.baseUrl + '/' + this.type;
            },
            
            /**
            * Before the actual form submission, store a JSON encoded 
            * string of the eligible rows; also, stores the selected
            * attributes for exportion.
            **/
            
            beforeSubmit: function () {
                let results = $('.results-table tbody tr.included');
                let arr = [];
                for(let i = 0; i < results.length; i++) {
                    arr.push($(results[i]).data('id'));
                }
                $("#export-ids").val(JSON.stringify(arr));
                let fields = $('select[name="export-fields-selector"] option.included');
                arr = [];
                for(let i = 0; i < fields.length; i++) {
                    arr.push(fields[i].value);
                }
                $("#export-fields").val(JSON.stringify(arr));
            }
        },
    }
</script>