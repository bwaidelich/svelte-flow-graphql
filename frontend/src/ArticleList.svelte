<script>
  import { getClient, query } from 'svelte-apollo';
  import { ARTICLES } from './queries';
  import ArticleTeaser from './ArticleTeaser.svelte';

  const client = getClient();
  const articles = query(client, { query: ARTICLES });
</script>

{#await $articles}
  Loading...
{:then result}
  {#each result.data.articles as article}
    <ArticleTeaser date={article.created_at}>
      <span slot="header">{article.title}</span>
      {@html article.text}
    </ArticleTeaser>
  {/each}
{:catch error}
  Error: {error}
{/await}