<?php

declare(strict_types=1);

class SearchItemsRequestDTO
{
    public function __construct(
        public readonly ?string $searchWord,
        public readonly array $category,
        public readonly array $statuses,
        public readonly string $sortBy,
        public readonly ?float $minPrice,
        public readonly ?float $maxPrice
    ) {}

    public static function fromArray(array $data): self
    {
        $searchWord = $data['q'] ?? null;
        
        $categoryInput = $data['categories'] ?? [];
        if (!is_array($categoryInput))
            $categoryInput = []; 

        $sortBy = $data['sort'] ?? 'newest';

        $rawStatus = $data['statuses'] ?? 'All';
        if ($rawStatus === 'All')
            $statuses = ['Active', 'Pending'];
        else
            $statuses = is_array($rawStatus) ? $rawStatus : [$rawStatus];
        
        $minPrice = isset($data['min']) && is_numeric($data['min']) ? (float)$data['min'] : null;
        $maxPrice = isset($data['max']) && is_numeric($data['max']) ? (float)$data['max'] : null;

        return new self(
            $searchWord,
            $categoryInput,
            $statuses,
            $sortBy,
            $minPrice,
            $maxPrice
        );
    }
}