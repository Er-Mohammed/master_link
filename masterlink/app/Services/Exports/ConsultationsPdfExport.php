<?php

namespace App\Services\Exports;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ConsultationsPdfExport
{
    /**
     * Arabic status translation map.
     */
    protected static array $statusMap = [
        'new' => 'جديدة',
        'contacted' => 'تم التواصل',
        'in_progress' => 'قيد المتابعة',
        'completed' => 'مكتملة',
        'cancelled' => 'ملغاة',
    ];

    /**
     * Reshape Arabic string into connected glyphs & RTL byte representation.
     */
    public static function reshapeArabic(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Character glyph mapping array [Isolated, Final, Initial, Medial, joinsPrevious, joinsNext]
        $glyphs = [
            'ا' => ["\u{FE8D}", "\u{FE8E}", "\u{FE8D}", "\u{FE8E}", true, false],
            'أ' => ["\u{FE83}", "\u{FE84}", "\u{FE83}", "\u{FE84}", true, false],
            'إ' => ["\u{FE87}", "\u{FE88}", "\u{FE87}", "\u{FE88}", true, false],
            'آ' => ["\u{FE81}", "\u{FE82}", "\u{FE81}", "\u{FE82}", true, false],
            'ب' => ["\u{FE8F}", "\u{FE90}", "\u{FE91}", "\u{FE92}", true, true],
            'ت' => ["\u{FE93}", "\u{FE94}", "\u{FE95}", "\u{FE96}", true, true],
            'ث' => ["\u{FE97}", "\u{FE98}", "\u{FE99}", "\u{FE9A}", true, true],
            'ج' => ["\u{FE9B}", "\u{FE9C}", "\u{FE9D}", "\u{FE9E}", true, true],
            'ح' => ["\u{FE9F}", "\u{FEA0}", "\u{FEA1}", "\u{FEA2}", true, true],
            'خ' => ["\u{FEA3}", "\u{FEA4}", "\u{FEA5}", "\u{FEA6}", true, true],
            'د' => ["\u{FEA7}", "\u{FEA8}", "\u{FEA7}", "\u{FEA8}", true, false],
            'ذ' => ["\u{FEA9}", "\u{FEAA}", "\u{FEA9}", "\u{FEAA}", true, false],
            'ر' => ["\u{FEAB}", "\u{FEAC}", "\u{FEAB}", "\u{FEAC}", true, false],
            'ز' => ["\u{FEAD}", "\u{FEAE}", "\u{FEAD}", "\u{FEAE}", true, false],
            'س' => ["\u{FEAF}", "\u{FEB0}", "\u{FEB1}", "\u{FEB2}", true, true],
            'ش' => ["\u{FEB3}", "\u{FEB4}", "\u{FEB5}", "\u{FEB6}", true, true],
            'ص' => ["\u{FEB7}", "\u{FEB8}", "\u{FEB9}", "\u{FEBA}", true, true],
            'ض' => ["\u{FEBB}", "\u{FEBC}", "\u{FEBD}", "\u{FEBE}", true, true],
            'ط' => ["\u{FEBF}", "\u{FEC0}", "\u{FEC1}", "\u{FEC2}", true, true],
            'ظ' => ["\u{FEC3}", "\u{FEC4}", "\u{FEC5}", "\u{FEC6}", true, true],
            'ع' => ["\u{FEC7}", "\u{FEC8}", "\u{FEC9}", "\u{FECA}", true, true],
            'غ' => ["\u{FECB}", "\u{FECC}", "\u{FECD}", "\u{FECE}", true, true],
            'ف' => ["\u{FECF}", "\u{FED0}", "\u{FED1}", "\u{FED2}", true, true],
            'ق' => ["\u{FED3}", "\u{FED4}", "\u{FED5}", "\u{FED6}", true, true],
            'ك' => ["\u{FED7}", "\u{FED8}", "\u{FED9}", "\u{FEDA}", true, true],
            'ل' => ["\u{FEDB}", "\u{FEDC}", "\u{FEDD}", "\u{FEDE}", true, true],
            'م' => ["\u{FEDF}", "\u{FEE0}", "\u{FEE1}", "\u{FEE2}", true, true],
            'ن' => ["\u{FEE3}", "\u{FEE4}", "\u{FEE5}", "\u{FEE6}", true, true],
            'ه' => ["\u{FEE7}", "\u{FEE8}", "\u{FEE9}", "\u{FEEA}", true, true],
            'و' => ["\u{FEEB}", "\u{FEEC}", "\u{FEEB}", "\u{FEEC}", true, false],
            'ي' => ["\u{FEED}", "\u{FEEE}", "\u{FEEF}", "\u{FEF0}", true, true],
            'ى' => ["\u{FEF1}", "\u{FEF2}", "\u{FEF1}", "\u{FEF2}", true, false],
            'ة' => ["\u{FE99}", "\u{FE94}", "\u{FE99}", "\u{FE94}", true, false],
            'ء' => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}", false, false],
            'ئ' => ["\u{FE89}", "\u{FE8A}", "\u{FE8B}", "\u{FE8C}", true, true],
            'ؤ' => ["\u{FE85}", "\u{FE86}", "\u{FE85}", "\u{FE86}", true, false],
        ];

        $chars = mb_str_split($text, 1, 'UTF-8');
        $count = count($chars);
        $reshapedChars = [];

        for ($i = 0; $i < $count; $i++) {
            $char = $chars[$i];
            if (!isset($glyphs[$char])) {
                $reshapedChars[] = $char;
                continue;
            }

            $prevChar = $i > 0 ? $chars[$i - 1] : null;
            $nextChar = $i < $count - 1 ? $chars[$i + 1] : null;

            $prevJoins = $prevChar && isset($glyphs[$prevChar]) && $glyphs[$prevChar][5];
            $nextJoins = $nextChar && isset($glyphs[$nextChar]) && $glyphs[$nextChar][4];

            if ($prevJoins && $nextJoins) {
                $reshapedChars[] = $glyphs[$char][3]; // Medial
            } elseif ($prevJoins) {
                $reshapedChars[] = $glyphs[$char][1]; // Final
            } elseif ($nextJoins) {
                $reshapedChars[] = $glyphs[$char][2]; // Initial
            } else {
                $reshapedChars[] = $glyphs[$char][0]; // Isolated
            }
        }

        return implode('', array_reverse($reshapedChars));
    }

    /**
     * Generate native executive corporate %PDF-1.4 stream for Consultations.
     */
    public static function generate(Builder $query, Request $request): string
    {
        // Query live database records without pagination
        $consultations = $query->with('service')->get();
        $totalCount = $consultations->count();
        $exportDate = now()->format('Y-m-d H:i');

        // Live calculated summary statistics
        $stats = [
            'total' => $totalCount,
            'new' => $consultations->where('status', 'new')->count(),
            'contacted' => $consultations->where('status', 'contacted')->count(),
            'in_progress' => $consultations->where('status', 'in_progress')->count(),
            'completed' => $consultations->where('status', 'completed')->count(),
            'cancelled' => $consultations->where('status', 'cancelled')->count(),
        ];

        // Applied filters summary
        $filters = [];
        if ($request->filled('search')) {
            $filters[] = 'البحث: ' . $request->input('search');
        }
        if ($request->filled('status')) {
            $st = $request->input('status');
            $filters[] = 'الحالة: ' . (static::$statusMap[$st] ?? $st);
        }
        if ($request->filled('service_id')) {
            $filters[] = 'رقم الخدمة: ' . $request->input('service_id');
        }
        if ($request->filled('sort')) {
            $sort = $request->input('sort') === 'created_at' ? 'التاريخ' : $request->input('sort');
            $dir = $request->input('direction') === 'asc' ? 'تصاعدي' : 'تنازلي';
            $filters[] = 'الترتيب: ' . $sort . ' (' . $dir . ')';
        }
        $filtersText = !empty($filters) ? implode(' | ', $filters) : 'جميع الاستشارات';

        // Page Layout Specs (A4 Portrait)
        $pageWidth = 595.28;
        $pageHeight = 841.89;
        $margin = 28.35; // 10mm
        $usableWidth = $pageWidth - ($margin * 2); // 538.58 pt
        $startY = 740;
        $currentY = $startY;

        $pages = [];
        $currentPageStream = '';

        // Draw Corporate Page Header (Repeats on every page)
        $drawHeader = function (&$stream) use ($exportDate, $margin, $usableWidth, &$currentY) {
            $currentY = 806;

            // Brand Bar (Red accent line top)
            $stream .= "0.949 0.020 0.188 rg\n"; // #F20530
            $stream .= sprintf("%.2f %.2f %.2f 4.00 re f\n", $margin, $currentY, $usableWidth);

            // Brand Title & Subtitle
            $currentY -= 22;
            $stream .= "BT /F1 16 Tf 0.059 0.090 0.165 rg\n"; // #0F172A
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (MasterLink) Tj ET\n", $margin, $currentY);

            $stream .= "BT /F1 12 Tf 0.949 0.020 0.188 rg\n";
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (| Consultations Executive Report) Tj ET\n", $margin + 85, $currentY);

            // Timestamp (LTR)
            $stream .= "BT /F1 9 Tf 0.392 0.455 0.545 rg\n"; // #64748B
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (Created: %s) Tj ET\n", $margin + 360, $currentY, $exportDate);

            // Separator Line
            $currentY -= 12;
            $stream .= "0.886 0.910 0.941 RG 1 w\n";
            $stream .= sprintf("%.2f %.2f m %.2f %.2f l S\n", $margin, $currentY, $margin + $usableWidth, $currentY);

            $currentY -= 18;
        };

        // Draw Summary Section Card (Page 1 Only)
        $drawSummarySection = function (&$stream) use ($stats, $margin, $usableWidth, &$currentY) {
            // Container Box
            $cardHeight = 44;
            $stream .= "0.973 0.980 0.988 rg\n"; // #F8FAFC
            $stream .= "0.886 0.910 0.941 RG 1 w\n";
            $stream .= sprintf("%.2f %.2f %.2f %.2f re b\n", $margin, $currentY - $cardHeight, $usableWidth, $cardHeight);

            // Section Header
            $textY = $currentY - 16;
            $stream .= "BT /F1 10 Tf 0.059 0.090 0.165 rg\n";
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (Summary Statistics / Metrics) Tj ET\n", $margin + 12, $textY);

            // Stats items
            $textY -= 18;
            $stream .= "BT /F1 9 Tf 0.200 0.254 0.333 rg\n";
            $statsStr = sprintf(
                "Total: %d | New: %d | Contacted: %d | In Progress: %d | Completed: %d | Cancelled: %d",
                $stats['total'],
                $stats['new'],
                $stats['contacted'],
                $stats['in_progress'],
                $stats['completed'],
                $stats['cancelled']
            );
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n", $margin + 12, $textY, $statsStr);

            $currentY -= ($cardHeight + 14);
        };

        // Draw Applied Filters Card (Page 1 Only)
        $drawAppliedFilters = function (&$stream) use ($filtersText, $margin, $usableWidth, &$currentY) {
            $cardHeight = 28;
            $stream .= "0.941 0.965 0.996 rg\n"; // #EFF6FF
            $stream .= "0.753 0.859 0.996 RG 1 w\n";
            $stream .= sprintf("%.2f %.2f %.2f %.2f re b\n", $margin, $currentY - $cardHeight, $usableWidth, $cardHeight);

            $textY = $currentY - 18;
            $stream .= "BT /F1 9 Tf 0.114 0.306 0.847 rg\n";
            $cleanFilters = preg_replace('/[^\x20-\x7E]/', '?', $filtersText);
            $stream .= sprintf("1 0 0 1 %.2f %.2f Tm (Applied Filters: %s) Tj ET\n", $margin + 12, $textY, $cleanFilters);

            $currentY -= ($cardHeight + 16);
        };

        // Helper function to split text into wrapped lines for fixed width
        $wrapText = function (string $text, int $maxCharsPerLine = 85): array {
            if (empty($text)) {
                return ['-'];
            }
            $words = explode(' ', $text);
            $lines = [];
            $currentLine = '';

            foreach ($words as $word) {
                if (mb_strlen($currentLine . ' ' . $word) <= $maxCharsPerLine) {
                    $currentLine .= ($currentLine === '' ? '' : ' ') . $word;
                } else {
                    if ($currentLine !== '') {
                        $lines[] = $currentLine;
                    }
                    $currentLine = mb_substr($word, 0, $maxCharsPerLine);
                }
            }
            if ($currentLine !== '') {
                $lines[] = $currentLine;
            }
            return $lines;
        };

        // Start Page 1 Setup
        $drawHeader($currentPageStream);
        $drawSummarySection($currentPageStream);
        $drawAppliedFilters($currentPageStream);

        // Render Consultation Record Cards
        $recordIndex = 0;
        foreach ($consultations as $item) {
            $recordIndex++;

            $serviceName = $item->service?->title_ar 
                ?? $item->service?->title_en 
                ?? $item->service?->name 
                ?? 'General Inquiry';
            $statusText = static::$statusMap[$item->status] ?? $item->status;
            $createdAtStr = $item->created_at ? $item->created_at->format('Y-m-d H:i') : '';

            $messageText = $item->message ?? 'No message content provided.';
            $wrappedMessageLines = $wrapText($messageText, 85);
            $messageLineCount = count($wrappedMessageLines);

            // Calculate exact Card Height
            // Header (22) + Details Grid (32) + Message Label & Lines (18 + lineCount * 14) + Padding (16)
            $cardHeight = 72 + (max(1, $messageLineCount) * 14) + 12;

            // Page Overflow Check: If card overflows page bottom margin, start a new page
            if ($currentY - $cardHeight < 45) {
                $pages[] = $currentPageStream;
                $currentPageStream = '';
                $currentY = $startY;
                $drawHeader($currentPageStream);
            }

            // Draw Card Outer Frame (Border & Background)
            $currentPageStream .= "1.000 1.000 1.000 rg\n"; // White card
            $currentPageStream .= "0.886 0.910 0.941 RG 1 w\n";
            $currentPageStream .= sprintf("%.2f %.2f %.2f %.2f re b\n", $margin, $currentY - $cardHeight, $usableWidth, $cardHeight);

            // Card Header Bar (Dark slate blue #0F172A)
            $headerBarHeight = 22;
            $currentPageStream .= "0.059 0.090 0.165 rg\n";
            $currentPageStream .= sprintf("%.2f %.2f %.2f %.2f re f\n", $margin, $currentY - $headerBarHeight, $usableWidth, $headerBarHeight);

            // Card Header Text
            $headerTextY = $currentY - 15;
            $currentPageStream .= "BT /F1 9 Tf 1.000 1.000 1.000 rg\n";
            $cardTitle = sprintf("Case #%d | Client: %s", $item->id, preg_replace('/[^\x20-\x7E]/', '?', mb_substr($item->name ?? '', 0, 30)));
            $currentPageStream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n", $margin + 10, $headerTextY, addcslashes($cardTitle, "()\n\r\t"));

            // Status Badge Text (Right aligned in Header)
            $statusBadge = sprintf("Status: %s", strtoupper($item->status));
            $currentPageStream .= "BT /F1 8 Tf 0.949 0.020 0.188 rg\n";
            $currentPageStream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n", $margin + 410, $headerTextY, $statusBadge);

            // Details Grid (Row 1: Email & Phone, Row 2: Service & Date & Company)
            $gridY = $currentY - 36;
            $currentPageStream .= "BT /F1 8 Tf 0.392 0.455 0.545 rg\n";

            $row1 = sprintf(
                "Email: %s  |  Phone: %s",
                preg_replace('/[^\x20-\x7E]/', '?', mb_substr($item->email ?? '-', 0, 35)),
                preg_replace('/[^\x20-\x7E]/', '?', mb_substr($item->phone ?? '-', 0, 20))
            );
            $currentPageStream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n", $margin + 10, $gridY, addcslashes($row1, "()\n\r\t"));

            $gridY -= 14;
            $row2 = sprintf(
                "Company: %s  |  Service: %s  |  Date: %s",
                preg_replace('/[^\x20-\x7E]/', '?', mb_substr($item->company_name ?? 'Individual', 0, 22)),
                preg_replace('/[^\x20-\x7E]/', '?', mb_substr($serviceName, 0, 25)),
                $createdAtStr
            );
            $currentPageStream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\n", $margin + 10, $gridY, addcslashes($row2, "()\n\r\t"));

            // Inquiry Message Container Box
            $gridY -= 16;
            $msgBoxHeight = (count($wrappedMessageLines) * 14) + 8;

            $currentPageStream .= "0.973 0.980 0.988 rg\n"; // #F8FAFC
            $currentPageStream .= "0.886 0.910 0.941 RG 0.5 w\n";
            $currentPageStream .= sprintf("%.2f %.2f %.2f %.2f re b\n", $margin + 8, $gridY - $msgBoxHeight, $usableWidth - 16, $msgBoxHeight);

            // Message Lines
            $lineY = $gridY - 12;
            $currentPageStream .= "BT /F1 8 Tf 0.122 0.161 0.216 rg\n";
            foreach ($wrappedMessageLines as $mLine) {
                $cleanLine = preg_replace('/[^\x20-\x7E]/', '?', $mLine);
                $currentPageStream .= sprintf("1 0 0 1 %.2f %.2f Tm (%s) Tj ET\nBT\n", $margin + 14, $lineY, addcslashes($cleanLine, "()\n\r\t"));
                $lineY -= 14;
            }
            $currentPageStream .= "ET\n";

            $currentY -= ($cardHeight + 12);
        }

        // Save last page
        $pages[] = $currentPageStream;

        // Build PDF Structure (%PDF-1.4 Binary)
        $pdf = "%PDF-1.4\n%âãÏÓ\n";
        $objects = [];

        // 1 0 obj: Catalog
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // Pages Tree calculation
        $totalPages = count($pages);
        $pageObjIds = [];
        for ($p = 0; $p < $totalPages; $p++) {
            $pageObjIds[] = ($p * 2 + 4) . " 0 R";
        }

        // 2 0 obj: Pages Tree
        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids [" . implode(" ", $pageObjIds) . "] /Count " . $totalPages . " >>\nendobj\n";

        // 3 0 obj: Font Helvetica
        $objects[3] = "3 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>\nendobj\n";

        // Build Pages & Content Objects
        for ($p = 0; $p < $totalPages; $p++) {
            $pageObjId = $p * 2 + 4;
            $contentObjId = $p * 2 + 5;

            // Footer (Repeated on every page bottom)
            $footerStream = $pages[$p];
            $footerText = sprintf("MasterLink - Consultations Report  |  Page %d of %d  |  Confidential", $p + 1, $totalPages);

            $footerStream .= "0.886 0.910 0.941 RG 0.5 w\n";
            $footerStream .= sprintf("%.2f 30 m %.2f 30 l S\n", $margin, $margin + $usableWidth);
            $footerStream .= "BT /F1 8 Tf 0.580 0.639 0.722 rg\n";
            $footerStream .= sprintf("1 0 0 1 %.2f 18 Tm (%s) Tj ET\n", $margin, $footerText);

            // Page Object
            $objects[$pageObjId] = sprintf(
                "%d 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 3 0 R >> >> /MediaBox [0 0 595.28 841.89] /Contents %d 0 R >>\nendobj\n",
                $pageObjId,
                $contentObjId
            );

            // Content Object
            $objects[$contentObjId] = sprintf(
                "%d 0 obj\n<< /Length %d >>\nstream\n%s\nendstream\nendobj\n",
                $contentObjId,
                strlen($footerStream),
                $footerStream
            );
        }

        // Assemble PDF Binary Stream with Xref Table
        $output = $pdf;
        $offsets = [0 => 0];

        $totalObjects = count($objects);
        for ($i = 1; $i <= $totalObjects; $i++) {
            $offsets[$i] = strlen($output);
            $output .= $objects[$i];
        }

        // Xref Table
        $xrefOffset = strlen($output);
        $output .= sprintf("xref\n0 %d\n", $totalObjects + 1);
        $output .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $totalObjects; $i++) {
            $output .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $output .= sprintf("trailer\n<< /Size %d /Root 1 0 R >>\nstartxref\n%d\n%%EOF", $totalObjects + 1, $xrefOffset);

        return $output;
    }
}
