<template>
	<div>
		<h1 v-if="poll.name">{{ poll.name }}</h1>
		<h1 v-else="poll.name">Name of Your Poll</h1>
		<template v-for="(question, index) in poll.questions">
			<h2 v-if="question.question">{{ question.question }}</h2>
			<h2 v-else="question.question">Question {{ index+1 }}</h2>
			
			<div class="radio" v-for="(answer, answer_index) in question.answers">
			  <label v-if="answer"><input type="radio" name="answer">{{ answer }}</label>
			  <label v-else><input type="radio" name="answer">Answer {{ answer_index+1 }}</label>
			</div>
		</template>
	</div>
</template>

<style type="text/css">

</style>

<script>
	module.exports = {

		props: ['poll'],

	}
</script>