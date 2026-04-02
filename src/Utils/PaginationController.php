<?php

namespace App\Utils;

class PaginationController
{
    protected int $perPage = 5;

    public function getPage(array $items, int $page): array
    {
        $offset = ($page - 1) * $this->perPage;
        return array_slice($items, $offset, $this->perPage);
    }

    public function totalPages(array $items): int
    {
        return (int) ceil(count($items) / $this->perPage);
    }

    public function getPageNumbers(int $current, int $total): array
    {
        if ($total <= 5) {
            return range(1, $total);
        }

        $pages = [1];

        if ($current > 3) {
            $pages[] = '...';
        }

        for ($i = max(2, $current - 1); $i <= min($total - 1, $current + 1); $i++) {
            $pages[] = $i;
        }

        if ($current < $total - 2) {
            $pages[] = '...';
        }

        $pages[] = $total;

        return $pages;
    }

    public function getPaginationData(array $items, int $page): array
    {
        $total = $this->totalPages($items);

        return [
            'pageCourante' => $page,
            'totalPages'   => $total,
            'pages'        => $this->getPageNumbers($page, $total),
        ];
    }
}