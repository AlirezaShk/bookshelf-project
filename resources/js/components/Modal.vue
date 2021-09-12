<template>
    <div class="modal-mask tw-bg-gray-700 tw-bg-opacity-80 tw-fixed tw-w-screen tw-h-screen">
      <div class="modal-wrapper tw-bg-gray-100 tw-rounded tw-inset-x-0 tw-absolute tw-m-auto tw-w-96 tw-h-max tw-bottom-1/2">
        <div class="modal-container tw-h-full tw-w-full">
          <div class="modal-header tw-bg-black tw-text-white">
          	<h2>
          		{{ header }}
      		</h2>
          </div>

          <div class="modal-body tw-border-b" v-if="detailMode">
          	<div class='tw-grid tw-rows-auto tw-grid-cols-2'>
                <slot></slot>
            </div>
          </div>
          <div class="modal-body tw-border-b" v-else>
            {{ body }}
          </div>
          <div class="modal-footer tw-relative tw-h-12 tw-border-0">
              <button class="tw-rounded tw-bg-red-200 hover:tw-bg-red-400 tw-rounded tw-absolute tw-right-3/4 tw-m-auto tw-h-3/4 tw-w-1/5 tw-duration-200" @click="$emit('close')">
                Close
              </button>
              <button class="tw-rounded tw-bg-blue-200 hover:tw-bg-blue-400 tw-rounded tw-absolute tw-left-3/4 tw-m-auto tw-h-3/4 tw-w-1/5 tw-duration-200" @click="expandDetails()"  v-if="detailMode">
                Expand
              </button>
              <button class="tw-rounded tw-bg-green-200 hover:tw-bg-green-400 tw-rounded tw-absolute tw-left-3/4 tw-m-auto tw-h-3/4 tw-w-1/5 tw-duration-200" @click="confirm()" v-else>
                Confirm
              </button>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
    export default {
        /**
        * Modal
        * 
        * This Vue component is used for modals (pop up message window)
        * for a record detail (fields are provided by a <slot> tag) or 
        * a delete message, using Modal.body to form the body of the 
        * message.
        **/
    	props: ['modalUrl', 'header', 'body', 'target', 'csrfToken', 'detailMode', 'applyFilter', 'type'],
        data: function () {
            this.body = "Are you sure you want to delete this book from the archive?";
            return {
                target: this.target,
                type: this.type
            }
        },
        methods: {
            /**
            * After confirming deletion, sends a DELETE request to api.{type}.delete
            **/

            confirm: function () {
                let that = this;
                $.ajax({
                    url: this.modalUrl.replace(this.type, 'api/'+this.type) + this.target.id,
                    type: 'DELETE',
                    data: {
                        _method: 'DELETE', 
                        _token: this.csrfToken,
                    },
                    dataType: 'json',
                    success: function (data) {
                        if(data.success){
                            $('.results-table tr[data-id='+ that.target.id +']').remove();
                            let e = document.createEvent('HTMLEvents');
                            e.initEvent('input', false, true);
                            $("#search-form input[name=filter]")[0].dispatchEvent(e);
                        }
                    }
                });
                that.$emit('close');
        	},

            /**
            * Open {type}.entry/edit-view page for the record.
            **/
            
            expandDetails: function () {
                window.location = this.modalUrl + this.target.id;
            },

            /**
            * Apply a hard filter (stored in session) for the genre of 
            * this Book.
            **/

            genreSearch: function (genre) {
                event.preventDefault();
                this.applyFilter(
                    'g', 
                    genre,
                    'books'
                );
            }
        },
        watch : {

            /**
            * Change header of the Modal box based on Modal.detailMode
            **/
            
            detailMode: {
                immediate: true,
                handler(val) {
                    if(val) {
                        this.header = "Record Details";
                    } else {
                        this.header = "Delete Record";
                        this.body = "Are you sure you want to delete this "+ this.type +" from the archive?";
                    }
                }
            }
        }
    }
</script>