import gql from 'graphql-tag';

export const ARTICLES = gql`
    query articles ($first: Int, $after: String, $last: Int, $before: String) {
        articles(first: $first, after: $after, last: $last, before: $before) {
            edges {
                node {
                    id
                    created_at
                    title
                    text
                }
            }
            pageInfo {
                startCursor
                endCursor
                hasPreviousPage
                hasNextPage
            }
        }
    }`

export const ARTICLE = gql`
    query articles ($id: ArticleId!) {
        article(id: $id) {
            id
            created_at
            title
            text
        }
    }`