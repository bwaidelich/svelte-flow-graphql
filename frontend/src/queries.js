import { gql } from 'apollo-boost';

export const ARTICLES = gql`{
    articles {
        id
        created_at
        title
        text
    }
}`