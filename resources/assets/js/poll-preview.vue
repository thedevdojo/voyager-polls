<template>
	<div>
		<template v-for="poll in polls">
			<h2>{{ poll.question }}</h2>
			<div class="radio" v-for="answer in poll.answers">
			  <label><input type="radio" name="answer">{{ answer }}</label>
			</div>
		</template>
	</div>
</template>

<style type="text/css">

</style>

<script>

	

	module.exports = {

		props: ['polls'],

		data: function(){
			return {
				greeting: 'Hello'
			}
		},
		watch : {
			poll: function(newVal){
				this.poll = newVal;
			}
		}
	}
</script>