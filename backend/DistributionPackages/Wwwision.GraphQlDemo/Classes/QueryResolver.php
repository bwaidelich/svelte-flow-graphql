<?php
declare(strict_types=1);
namespace Wwwision\GraphQlDemo;

use t3n\GraphQL\ResolverInterface;

final class QueryResolver implements ResolverInterface
{

    public function articles(): array
    {
        return [
            ['id' => 'article-1', 'created_at' => (new \DateTimeImmutable('1980-12-13'))->format(DATE_ATOM), 'title' => 'First article', 'text' => 'Lorem <b>Ipsum</b>'],
            ['id' => 'article-2', 'created_at' => (new \DateTimeImmutable('2020-05-13'))->format(DATE_ATOM), 'title' => 'Second article', 'text' => 'With a <a href="#">Link</a>.'],
            ['id' => 'article-3', 'created_at' => (new \DateTimeImmutable('2020-05-15'))->format(DATE_ATOM), 'title' => 'Third article (no text)'],
        ];
    }

}
