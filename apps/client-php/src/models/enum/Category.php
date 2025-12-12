<?php

declare(strict_types=1);

enum Category : string 
{
    case Textbooks = 'Textbooks';
    case Stationery = 'Stationery';
    case Electronics = 'Electronics';
    case Clothing = 'Clothing';
    case SportsEquipment ='Sports Equipment';
    case Accessories = 'Accessories';
    case Furniture = 'Furniture';
    case Collectibles = 'Collectibles';
    case Other = 'Other';
}