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
                <h2 class='tw-col-span-2 tw-font-bold tw-text-lg'>{{ target.name }}</h2>
                <div class='tw-col-span-1'>By 
                    <a :href='"/author/"+target.author.id'>{{ target.author.fname + " " + target.author.lname }}</a>
                </div>
                <div class='tw-col-span-1'>Released {{ target.release_date }}</div>
                <div class='tw-col-span-2'>In: <a :href='"/books?genre="+target.genre'>{{ target.genre }}</a></div>
                <div class='tw-col-span-2 tw-line-clamp-2'>{{ target.descrip }}</div>
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
    	props: ['modalUrl', 'header', 'body', 'target', 'csrfToken', 'detailMode'],
        data: function () {
            this.body = "Are you sure you want to delete this book from the archive?";
            return {}
        },
        methods: {
            confirm: function () {
                let that = this;
        		$.post(this.modalUrl + this.target.id, {_method: 'DELETE', _token: this.csrfToken, responseType: 'json'}, function(data) {
                    if(data.success)
                        $('.results-table tr[data-id='+ that.target.id +']').remove();
                }, 'json');
                that.$emit('close');
        	},
            expandDetails: function () {
                window.location = '/book/' + this.target.id;
            }
        },
        watch : {
            detailMode: {
                immediate: true,
                handler(val) {
                    if(val) {
                        this.header = "Record Details";
                    } else {
                        this.header = "Delete Record";
                    }
                }
            }
        }
    }
</script>