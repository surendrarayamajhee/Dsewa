<?php



function getOrderStatusClass($status)
{
    switch ($status) {
        case '1':
            $statusClass = "light";
            break;
        case '2':
            $statusClass = "warning";
            break;
        case '6':
            $statusClass = "success";
            break;
        case '4':
            $statusClass = "info";
            break;
        case '3':
            $statusClass = "primary";
            break;
        case '5':
            $statusClass = "info";
            break;
        case '6':
            $statusClass = "info";
            break;
        case '7':
            $statusClass = "success";
            break;
        case '8':
            $statusClass = "danger";
            break;
        default:
            $statusClass = "info";
    }

    return $statusClass;
}

function getOrderCreatedAsClass($status)
{
    switch ($status) {
        case 'EXCHANGE':
            $statusClass = "primary";
            break;
        case 'RETURN':
            $statusClass = "danger";
            break;
        case 'NEW':
            $statusClass = "info";
            break;
        case 'REFUND':
            $statusClass = "success";
            break;
        default:
            $statusClass = "light";
    }

    return $statusClass;
}
