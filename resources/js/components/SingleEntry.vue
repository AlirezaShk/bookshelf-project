<script>
	const FormHandler = require('./FormHandler.vue').default
	export default {
        /**
        * Single Entry
        * 
        * This Vue component is used for the manipulation of an entry 
        * details/edit form in {resource}.entry/edit-view
        **/
		props: {
			id: {type: String},
			authorIsAlive: {type: Boolean, default: undefined},
			originalData: {type: Array, default: undefined},
		},
		components: {
			FormHandler
		},
		methods: {
	        /**
	        * Expand or minimize the details of an entry.
	        **/

			toggleDetails: function(e) {
				$(".extra").toggleClass('tw-hidden');
				$(".author-Name-wrapper").toggleClass('tw-hidden');
				if(this.authorIsAlive == false) {
					$(".author-alive-wrapper").addClass('tw-hidden');
				}
				$("#"+this.id).find('textarea').toggleClass('detailed');
				$(e.target).toggleText('Expand', 'Minimize');
			},

	        /**
	        * Begin editting the details.
	        **/

			beginEdit: function(e) {
				$(".cancel-edit-btn").removeClass('tw-hidden');
				$(".confirm-edit-btn").removeClass('tw-hidden');
				$(".begin-edit-btn").addClass('tw-hidden');
				$(".expand-btn").addClass('tw-hidden');
				$(".extra").removeClass('tw-hidden');
				$(".author-Name-wrapper").addClass('tw-hidden');
				$("#"+this.id).removeClass('preview');
				$("#"+this.id).find("input").removeAttr('disabled');
				$("#"+this.id).find("textarea").removeAttr('disabled').addClass('detailed');
				$(".author-alive-wrapper").removeClass('tw-hidden');
				if($("#author-alive").length > 0) {
					if($("#author-alive")[0].checked) {
						$("#author-death").attr("disabled", true);
					}
				}
				$("#"+this.id).find("select").removeAttr('disabled');
				if((this.authorIsAlive !== undefined) && (this.authorIsAlive == true)) {
					$(".author-alive-wrapper").removeClass('extra-extra');
					$(".author-death-wrapper").removeClass('extra-extra');
				}
				if(this.originalData == undefined){
					this.originalData = $("#"+this.id).serializeArray();
				}
			},

	        /**
	        * Cancel editting the details.
	        * 
	        * @function SingleEntry.resetData()
	        **/

			cancelEdit: function(e) {
				$(".cancel-edit-btn").addClass('tw-hidden');
				$(".confirm-edit-btn").addClass('tw-hidden');
				$(".begin-edit-btn").removeClass('tw-hidden');
				$(".author-Name-wrapper").removeClass('tw-hidden');
				$(".expand-btn").removeClass('tw-hidden');
				$(".extra").addClass('tw-hidden');
				$("#"+this.id).addClass('preview');
				$("#"+this.id).find("input").attr("disabled", true);
				$("#"+this.id).find("textarea").attr("disabled", true).removeClass('detailed');
				$("#"+this.id).find("select").attr("disabled", true);
				if((this.authorIsAlive !== undefined) && (this.authorIsAlive == true)) {
					$(".author-alive-wrapper").addClass('extra-extra');
					$(".author-death-wrapper").addClass('extra-extra');
				}
				this.resetData();
				$(".expand-btn").text('Expand');
			},

	        /**
	        * Submit the edits.
	        **/

			confirmEdit: function(e) {
				$("#"+this.id).submit();
			},

	        /**
	        * Resets the entry details back to the original values. 
	        **/

			resetData: function() {
				let data = this.originalData;
				let temp = [];
				for(let i = 0; i < data.length; i++) {
					if(data[i].name.includes('[]')){
						temp.push(data[i].value);
					} else {
						if(temp.length > 0) {
							$("#"+this.id).find("[name='"+data[i-1].name+"']").val(temp);
							temp = [];
						}
						$("#"+this.id).find("[name='"+data[i].name+"']").val(data[i].value);
					}
				}
			}
		},
		mounted: function() {
			$(".cancel-edit-btn").addClass('tw-hidden');
			$(".confirm-edit-btn").addClass('tw-hidden');
			$("#"+this.id).find("input").attr("disabled", true);
			$("#"+this.id).find("textarea").attr("disabled", true);
			$("#"+this.id).find("select").attr("disabled", true);
			$("#"+this.id).find("select[multiple]").attr("size", $("#"+this.id).find("select option:checked").length);
			if((this.authorIsAlive !== undefined) && (this.authorIsAlive == true)) {
				$(".author-alive-wrapper").addClass('extra-extra');
				$(".author-death-wrapper").addClass('extra-extra');
			}
		}
	}
</script>