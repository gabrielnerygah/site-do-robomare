<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

function formatDecimal($number, $decimals = 1) {
    return number_format($number, $decimals, ',', '.');
}

function getStatusBadge($status) {
    $badges = [
        'operacao' => '<span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800">EM OPERAÇÃO</span>',
        'carregando' => '<span class="px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-800">CARREGANDO</span>',
        'manutencao' => '<span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800">MANUTENÇÃO</span>',
        'pendente' => '<span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-800">PENDENTE</span>',
        'em_analise' => '<span class="px-3 py-1 text-xs font-bold bg-blue-100 text-blue-800">EM ANÁLISE</span>',
        'aprovado' => '<span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800">APROVADO</span>',
        'rejeitado' => '<span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800">REJEITADO</span>',
    ];
    return $badges[$status] ?? $status;
}
?>
