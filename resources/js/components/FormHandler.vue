<script>
	export default {
        /**
        * Form Handler
        * 
        * This Vue component is in charge of manipulation of forms and 
        * their input fields.
        **/
		props: ['id', 'toggleDetails', 'beginEdit', 'cancelEdit', 'confirmEdit'],
		data: function () {
			return {
				id: this.id,
			}
		},
		methods: {
	        /**
	        * Randomly returns an Author ID, from the Database. 
	        * Helper function.
	        * Reachable, only if config(app.debug) is TRUE.
	        **/

			AIDFillter: function(e) {
				$.get('/api/author/aid-sample', {}, function(data) {
					$(e.target).parent().parent().find('input').val(parseInt(data.aid));
				}, 'json');
			},

	        /**
	        * Returns a randomly generated valid ISBN, from the 
	        * Database.
	        * Helper function.
	        * Reachable, only if config(app.debug) is TRUE.
	        **/

			ISBNFiller: function(e) {
				$.get('/api/book/isbn-sample', {
					length: 10 + 3*(Math.floor(Math.random()))
				}, function(data) {
					$(e.target).parent().parent().find('input').val(data.isbn);
				}, 'json');
			},

	        /**
	        * Hide the error if the input is being changed.
	        **/

			resetError: function(e) {
				$(e.target).parent().find('.error').remove();
			},

	        /**
	        * If Author is checked is being alive, Obit field is
	        * disabled; enabled, otherwise.
	        **/
	        
			checkNotDead: function(e, override = false, el = undefined) {
				let isAlive;
				if(override) {
					isAlive = el;
				} else {
					isAlive = e.target
				}
				if(isAlive.checked) {
					$("#author-death").prop('disabled', true);
				} else {
					$("#author-death").prop('disabled', false);
				}
			}
		},
		mounted: function() {
			this.checkNotDead(undefined, true, $("#author-alive")[0]);
		}
	}
	

</script>