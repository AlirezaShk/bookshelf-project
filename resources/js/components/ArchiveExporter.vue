<script>
    export default {
        props: ['baseUrl', 'idListId'],
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
                $("#ids").val(JSON.parse($('#'+this.idListId).val()).join(', '));
            }
        },
    }
</script>