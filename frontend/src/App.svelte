<ArticleList />

<script>
  import ApolloClient from 'apollo-boost';
  import { setClient } from 'svelte-apollo';
  import ArticleList from './ArticleList.svelte';

  const client = new ApolloClient({ uri: 'http://localhost:8081/api/demo' });
  setClient(client);
</script>