<?php

if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($categoryName)
    {
        switch ($categoryName) {
            case 'Food/Beverage':
                return '<i class="fas fa-pizza-slice"></i>';
            case 'Travel/Commute':
                return '<i class="fas fa-car"></i>';
            case 'Shopping':
                return '<i class="fas fa-shopping-bag"></i>';
            case 'Housing/Utilities':
                return '<i class="fas fa-home"></i>';
            case 'Entertainment':
                return '<i class="fas fa-film"></i>';
            case 'Health/Medical':
                return '<i class="fas fa-heartbeat"></i>';
            case 'Education':
                return '<i class="fas fa-graduation-cap"></i>';
            case 'Savings/Investments':
                return '<i class="fas fa-piggy-bank"></i>';
            case 'Insurance':
                return '<i class="fas fa-shield-alt"></i>';
            case 'Gifts/Donations':
                return '<i class="fas fa-gift"></i>';
            case 'Miscellaneous':
                return '<i class="fas fa-ellipsis-h"></i>';
            default:
                return '<i class="fas fa-question-circle"></i>';
        }
    }
}
