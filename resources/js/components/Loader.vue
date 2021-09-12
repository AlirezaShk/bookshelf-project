<template>
	<div class='tw-absolute tw-inset-0 tw-bg-gray-300' :style='{width: "100%", height: "100%", top: top, zIndex: 10}'>
		<object type="image/svg+xml" data='/icon/loading.svg' class='tw-absolute tw-animate-spin tw-w-12 tw-inset-0 tw-m-auto'>
			<img src='/icon/loading.svg'>
		</object>
	</div>
</template>
<script>
	export default {
        /**
        * Loader
        * 
        * Renders a loading animation.
        **/
		props: ['top']
	}
</script>