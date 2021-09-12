<template>
    <select class='tw-col-span-1' name='export-fields-selector' @change='toggleField'>
        <option value='' selected>Included Fields</option>
        <option v-for='field in fields' :value="field.raw">{{ field.label }}</option>
    </select>
</template>
<script>
	export default {
        /**
        * Archive Exporter Field Includer
        * 
        * This Vue component is in charge of selection of the 
        * attributes.
        **/
		props: ['fields'],
		data: function (){
			return {
				fields: this.fields
			}
		},
		methods: {
	        /**
	        * Toggles the selection state of an attribute 
	        **/
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