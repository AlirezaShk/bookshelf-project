<script>
    const ArchiveExporterFieldIncluder = require('./ArchiveExporterFieldIncluder.vue').default
    export default {
        props: ['baseUrl'],
        components: {
            FieldIncluder : ArchiveExporterFieldIncluder
        },
        data: function() {
            return {
                type: '',
                action_url: 'Hello',
            }
        },
        methods: {
            changeURL: function () {
                this.action_url = this.baseUrl + '/' + this.type;
            },
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