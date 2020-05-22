<?php
declare(strict_types=1);
namespace Wwwision\GraphQlDemo;

use t3n\GraphQL\ResolverInterface;

final class QueryResolver implements ResolverInterface
{
    /**
     * @var array
     */
    private static $articles;

    private static function fixture(): array
    {
        if (self::$articles === null) {
            $now = new \DateTimeImmutable();
            self::$articles = array_map(static function($i) use ($now) {
                return ['id' => 'article-' . $i, 'created_at' => $now->format(DATE_ATOM), 'title' => 'Article #' . $i, 'text' => 'Lorem <b>Ipsum</b>'];
            }, range(1, 150));
        }
        return self::$articles;
    }

    public function articles($_, array $args): array
    {
        $articles = self::fixture();
        $before = $args['before'] ?? null;
        $after = $args['after'] ?? null;
        $first = $args['first'] ?? null;
        $last = $args['last'] ?? null;

        $articleEdges = $this->edgesToReturn($articles, $before, $after, $first, $last);
        $firstEdge = $articleEdges[array_key_first($articleEdges)] ?? null;
        $lastEdge = $articleEdges[array_key_last($articleEdges)] ?? null;
        return [
            'pageInfo' => [
                'hasPreviousPage' => $this->hasPreviousPage($articles, $before, $after, $first, $last),
                'hasNextPage' => $this->hasNextPage($articles, $before, $after, $first, $last),
                'startCursor' => $firstEdge ? $firstEdge['id'] : '',
                'endCursor' => $lastEdge ? $lastEdge['id'] : '',
            ],
            'count' => \count($articles),
            'edges' => array_map(static function(array $article) {
                return [
                    'cursor' => $article['id'],
                    'node' => $article,
                ];
            }, $articleEdges)
        ];
    }

    public function article($_, array $args): ?array
    {
        $articles = self::fixture();
        $id = $args['id'];
        $match = array_filter($articles, static function(array $article) use ($id) {
            return $article['id'] === $id;
        });
        return $match !== [] ? reset($match) : null;
    }

    private function hasPreviousPage(array $allEdges, ?string $before, ?string $after, ?int $first, ?int $last): bool
    {
        if ($last !== null) {
            $edges = $this->applyCursorsToEdges($allEdges, $before, $after);
            return (\count($edges) > $last);
        }
        if ($after !== null && $this->edgeIndexForCursor($allEdges, $after) > 0) {
            return true;
        }
        return false;
    }

    private function hasNextPage(array $allEdges, ?string $before, ?string $after, ?int $first, ?int $last): bool
    {
        if ($first !== null) {
            $edges = $this->applyCursorsToEdges($allEdges, $before, $after);
            return (\count($edges) > $first);
        }
        if ($before !== null) {
            $index = $this->edgeIndexForCursor($allEdges, $before);
            if ($index > -1 && $index < \count($allEdges)) {
                return true;
            }
        }
        return false;
    }

    private function edgesToReturn(array $allEdges, ?string $before, ?string $after, ?int $first, ?int $last): array
    {
        $edges = $this->applyCursorsToEdges($allEdges, $before, $after);
        if ($first !== null) {
            if ($first < 0) {
                throw new \InvalidArgumentException('argument "first" must not be less than 0');
            }
            if (\count($edges) > $first) {
                $edges = \array_slice($edges, 0, $first);
            }
        }
        if ($last !== null) {
            if ($last < 0) {
                throw new \InvalidArgumentException('argument "last" must not be less than 0');
            }
            if (\count($edges) > $last) {
                $edges = \array_slice($edges, -$last);
            }
        }
        return $edges;
    }

    private function applyCursorsToEdges(array $allEdges, ?string $before, ?string $after): array
    {
        $edges = $allEdges;
        if ($after !== null) {
            $afterEdgeIndex = $this->edgeIndexForCursor($edges, $after);
            if ($afterEdgeIndex > -1) {
                $edges = \array_slice($edges, $afterEdgeIndex + 1);
            }
        }
        if ($before !== null) {
            $beforeEdgeIndex = $this->edgeIndexForCursor($edges, $before);
            if ($beforeEdgeIndex > -1) {
                $edges = \array_slice($edges, 0, $beforeEdgeIndex);
            }
        }
        return $edges;
    }

    private function edgeIndexForCursor(array $edges, string $cursor): int
    {
        foreach ($edges as $index => $edge) {
            if ($edge['id'] === $cursor) {
                return $index;
            }
        }
        return -1;
    }

}
