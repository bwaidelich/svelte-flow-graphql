<article>
	<h1 id="header"><slot name="header"></slot></h1>
	<div id="content">
		<slot></slot>
	</div>
	<footer id="footer">{date}</footer>
</article>

<script>
	export let date = '';
</script>

<style>
article {
	border-radius: 3px;
	background: #eee;
	padding: 5px 10px;
	margin: 0 0 15px 0;
	box-shadow: 0 1px 5px rgba(0, 0, 0, .22), 0 1px 4px rgba(0, 0, 0, .34);
}
#header {
	margin: 0;
	padding: 10px 0;
	border-bottom: 1px solid #cfcfcf;
}
#content {
	padding: 10px 0;
}
#footer {
	border-top: 1px solid #cfcfcf;
	padding: 5px 0 0 0;
	text-align: right;
	font-size: small;
	height: 15px;
}
</style>