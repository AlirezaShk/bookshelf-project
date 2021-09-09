<template>
    <select class='tw-col-span-1' name='export-fields-selector' @change='toggleField'>
        <option value='' selected>Included Fields</option>
        <option v-for='field in fields' :value="formatExportFieldValue(field)">{{ formatExportFieldName(field) }}</option>
    </select>
</template>
<script>
	export default {
		props: ['fields'],
		data: function (){
			return {
				fields: this.fields
			}
		},
		methods: {
			formatExportFieldName: function (field) {
		        switch(field) {
		            case 'name':
		                field = 'title';
		                break;
		            case 'descrip':
		                field = 'description';
		                break;
		            case 'author_id':
		                field = 'author';
		                break;
		        }
		        return field.split('_').map(this.ucwords).join(' ');
			},
			formatExportFieldValue: function (field) {
		        switch(field) {
		            case 'author_id':
		                field = 'author';
		                break;
		        }
		        return field;
			},
			ucwords: function (str) {
  				return str.charAt(0).toUpperCase() + str.slice(1);
			},
			toggleField: function (e) {
				let field = e.target.value;
				if (field === '') return;
				let index = e.target.selectedIndex;
				if (field === 'all') $(e.target).find('option').removeClass('included');
				$(e.target).find('option:eq('+index+')').toggleClass('included');
				$(e.target).val('');
			}
		},
	}
</script>