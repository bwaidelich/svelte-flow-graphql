<script>
  import { client } from './api/client';
  import { ARTICLES } from './api/queries';
  import ArticleTeaser from './ArticleTeaser.svelte';

  const articlesPerPage = 5;

  let articles = fetchArticles({ first: articlesPerPage });

  function fetchArticles(variables) {
    return client.query({ query: ARTICLES, variables })
  }
  
  function nextPage(pageInfo) {
    articles = fetchArticles({first: articlesPerPage, after: pageInfo.endCursor})
  }

  function previousPage(pageInfo) {
    articles = fetchArticles({last: articlesPerPage, before: pageInfo.startCursor})
  }

</script>


{#await articles}
  Loading...
{:then result}
  {#each result.data.articles.edges as edge}
    <ArticleTeaser date={edge.node.created_at}>
      <span slot="header">{edge.node.title}</span>
      {@html edge.node.text}
    </ArticleTeaser>
  {/each}
  <button on:click={previousPage(result.data.articles.pageInfo)} disabled={!result.data.articles.pageInfo.hasPreviousPage}>PREV</button>
  <button on:click={nextPage(result.data.articles.pageInfo)} disabled={!result.data.articles.pageInfo.hasNextPage}>NEXT</button>
{:catch error}
  Error: {error}
{/await}