import fetch from 'node-fetch'
import { createHttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'
import ApolloClient from 'apollo-client'

export const client = new ApolloClient({
  link: createHttpLink({
    uri: 'http://localhost:8081/api/demo',
    fetch: fetch,
  }),
  cache: new InMemoryCache(),
  defaultOptions: {
      query: {
          fetchPolicy: 'no-cache',
      }
  }
});