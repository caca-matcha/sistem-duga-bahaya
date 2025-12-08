<?php

if (!function_exists('getRiskColorsArray')) {
    /**
     * Mengembalikan array warna untuk visualisasi skor risiko.
     * Indeks 0-24 mewakili skor risiko 1-25.
     *
     * @return array
     */
    function getRiskColorsArray(): array
    {
        return [
            // Skor 1-5 (Low - Greenish)
            "#d1fae5", "#a7f3d0", "#6ee7b7", "#34d399", "#10b981",
            // Skor 6-10 (Medium - Yellowish)
            "#fef08a", "#fde047", "#facc15", "#fbbf24", "#f59e0b",
            // Skor 11-15 (Medium-High - Orange/Red)
            "#fdba74", "#fb923c", "#f97316", "#f87171", "#ef4444",
            // Skor 16-20 (High - Red)
            "#ef4444", "#f87171", "#f97316", "#fb7185", "#f43f5e",
            // Skor 21-25 (Extreme - Deep Red)
            "#ffe4e1", "#ffb3b3", "#ff8080", "#ff4d4d", "#ff1a1a"
        ];
    }
}

if (!function_exists('getRiskColor')) {
    /**
     * Mengembalikan warna latar belakang untuk skor risiko tertentu.
     *
     * @param int|null $score Skor risiko (1-25).
     * @return string Kode warna heksadesimal atau warna default jika skor null/invalid.
     */
    function getRiskColor(?int $score): string
    {
        if ($score === null || $score < 1 || $score > 25) {
            return "#9ca3af"; // Default gray for N/A or invalid
        }
        $colors = getRiskColorsArray();
        $index = max(0, min($score - 1, 24)); // Clamp index to 0-24
        return $colors[$index];
    }
}

if (!function_exists('getTextColor')) {
    /**
     * Mengembalikan warna teks yang kontras untuk skor risiko tertentu.
     *
     * @param int|null $score Skor risiko (1-25).
     * @return string Kode warna heksadesimal (hitam atau putih).
     */
    function getTextColor(?int $score): string
    {
        if ($score === null || $score < 1 || $score > 25) {
            return "#FFFFFF"; // White text for gray default background
        }
        // Heuristik: Warna gelap untuk skor rendah/menengah (hijau/kuning), putih untuk skor tinggi (merah)
        if ($score <= 10) { // Low to Medium
            return "#1f2937"; // Dark gray/black text
        } else { // Medium-High to Extreme
            return "#FFFFFF"; // White text
        }
    }
}
