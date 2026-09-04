<?php

namespace App\Services\Exports;

use Illuminate\Database\Eloquent\Builder;
use ZipArchive;

class ConsultationsExcelExport
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
     * Generate an OpenXML Spreadsheet (.xlsx) binary string for the given query.
     */
    public static function generate(Builder $query): string
    {
        // Fetch all matching records without pagination
        $consultations = $query->with('service')->get();
        $totalCount = $consultations->count();
        $exportDate = now()->format('Y-m-d H:i');

        // Create temp zip file
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive;
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Shared strings array and map
        $sharedStrings = [];
        $stringMap = [];
        $addString = function (string $text) use (&$sharedStrings, &$stringMap): int {
            $text = (string) $text;
            if (isset($stringMap[$text])) {
                return $stringMap[$text];
            }
            $index = count($sharedStrings);
            $sharedStrings[] = $text;
            $stringMap[$text] = $index;

            return $index;
        };

        // Header Metadata Rows (Rows 1-4)
        $titleIdx = $addString('MasterLink');
        $subtitleIdx = $addString('تقرير استشارات العملاء');
        $metaDateIdx = $addString('تاريخ التصدير: '.$exportDate);
        $metaTotalIdx = $addString('إجمالي النتائج: '.$totalCount.' استشارة');

        // Column Header Titles (Row 5)
        $headers = [
            'رقم الاستشارة',
            'اسم العميل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'الشركة / المؤسسة',
            'الخدمة المطلوبة',
            'تفاصيل الرسالة',
            'الحالة',
            'تاريخ الإرسال',
        ];
        $headerIndexes = array_map($addString, $headers);

        // Build data rows (Rows 6+)
        $dataRows = [];
        foreach ($consultations as $item) {
            $serviceName = $item->service?->title_ar
                ?? $item->service?->title_en
                ?? $item->service?->name
                ?? 'غير محدد';

            $statusText = static::$statusMap[$item->status] ?? $item->status;
            $createdAtStr = $item->created_at ? $item->created_at->format('Y-m-d H:i') : '';

            $rowValues = [
                (string) $item->id,
                $item->name ?? '',
                $item->email ?? '',
                $item->phone ?? '',
                $item->company_name ?? '',
                $serviceName,
                $item->message ?? '',
                $statusText,
                $createdAtStr,
            ];

            $rowIndexes = [];
            foreach ($rowValues as $val) {
                $rowIndexes[] = $addString($val);
            }
            $dataRows[] = $rowIndexes;
        }

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'.
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
            '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'.
            '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'.
            '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'.
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'.
            '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'.
            '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<sheets>'.
            '<sheet name="الاستشارات" sheetId="1" r:id="rId1"/>'.
            '</sheets>'.
            '</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml
        // Font 0: Normal 11pt, Font 1: Bold Title 16pt #F20530, Font 2: Bold Subtitle 12pt, Font 3: Bold Header 11pt White
        // Fill 0: None, Fill 1: Gray125, Fill 2: Brand Red #F20530, Fill 3: Soft Gray #F8FAFC
        // CellXfs:
        // 0: default
        // 1: Brand Title (Font 1)
        // 2: Subtitle (Font 2)
        // 3: Metadata (Font 0)
        // 4: Table Header (Font 3, Fill 2, Centered)
        // 5: Normal Data Cell with Text Wrap (Font 0, WrapText)
        // 6: Data Cell Right Aligned (Font 0)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<fonts count="4">'.
            '<font><sz val="11"/><name val="Segoe UI"/></font>'.
            '<font><b/><sz val="16"/><color rgb="FFF20530"/><name val="Segoe UI"/></font>'.
            '<font><b/><sz val="12"/><color rgb="FF0F172A"/><name val="Segoe UI"/></font>'.
            '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Segoe UI"/></font>'.
            '</fonts>'.
            '<fills count="4">'.
            '<fill><patternFill fillType="none"/></fill>'.
            '<fill><patternFill fillType="gray125"/></fill>'.
            '<fill><patternFill fillType="solid"><fgColor rgb="FFF20530"/><bgColor indexed="64"/></patternFill></fill>'.
            '<fill><patternFill fillType="solid"><fgColor rgb="FFF8FAFC"/><bgColor indexed="64"/></patternFill></fill>'.
            '</fills>'.
            '<borders count="2">'.
            '<border><left/><right/><top/><bottom/></border>'.
            '<border>'.
            '<left style="thin"><color rgb="FFE2E8F0"/></left>'.
            '<right style="thin"><color rgb="FFE2E8F0"/></right>'.
            '<top style="thin"><color rgb="FFE2E8F0"/></top>'.
            '<bottom style="thin"><color rgb="FFE2E8F0"/></bottom>'.
            '</border>'.
            '</borders>'.
            '<cellXfs count="7">'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'.
            '<xf numFmtId="0" fontId="1" fillId="0" borderId="0" applyFont="1"/>'.
            '<xf numFmtId="0" fontId="2" fillId="0" borderId="0" applyFont="1"/>'.
            '<xf numFmtId="0" fontId="0" fillId="3" borderId="1" applyFont="1" applyFill="1"/>'.
            '<xf numFmtId="0" fontId="3" fillId="2" borderId="1" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyAlignment="1"><alignment wrapText="1" vertical="top"/></xf>'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyAlignment="1"><alignment horizontal="right" vertical="top"/></xf>'.
            '</cellXfs>'.
            '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/sharedStrings.xml
        $ssXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="'.count($sharedStrings).'" uniqueCount="'.count($sharedStrings).'">';
        foreach ($sharedStrings as $str) {
            $safeStr = htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $ssXml .= '<si><t>'.$safeStr.'</t></si>';
        }
        $ssXml .= '</sst>';
        $zip->addFromString('xl/sharedStrings.xml', $ssXml);

        // 7. xl/worksheets/sheet1.xml
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $widths = [14, 25, 28, 18, 25, 25, 45, 16, 20];
        $totalDataRows = count($dataRows);
        $lastRowNum = $totalDataRows + 5;

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<sheetViews><sheetView rightToLeft="1" tabSelected="1" workbookViewId="0">'.
            '<pane ySplit="5" topLeftCell="A6" activePane="bottomLeft" state="frozen"/>'.
            '</sheetView></sheetViews>'.
            '<sheetFormatPr defaultRowHeight="20"/>'.
            '<cols>';
        foreach ($cols as $i => $col) {
            $w = $widths[$i] ?? 20;
            $sheetXml .= '<col min="'.($i + 1).'" max="'.($i + 1).'" width="'.$w.'" customWidth="1"/>';
        }
        $sheetXml .= '</cols><sheetData>';

        // Row 1: Brand Title
        $sheetXml .= '<row r="1" ht="28" customHeight="1">';
        $sheetXml .= '<c r="A1" t="s" s="1"><v>'.$titleIdx.'</v></c>';
        $sheetXml .= '</row>';

        // Row 2: Subtitle
        $sheetXml .= '<row r="2" ht="22" customHeight="1">';
        $sheetXml .= '<c r="A2" t="s" s="2"><v>'.$subtitleIdx.'</v></c>';
        $sheetXml .= '</row>';

        // Row 3: Export Date
        $sheetXml .= '<row r="3" ht="20" customHeight="1">';
        $sheetXml .= '<c r="A3" t="s" s="3"><v>'.$metaDateIdx.'</v></c>';
        $sheetXml .= '</row>';

        // Row 4: Total Records
        $sheetXml .= '<row r="4" ht="20" customHeight="1">';
        $sheetXml .= '<c r="A4" t="s" s="3"><v>'.$metaTotalIdx.'</v></c>';
        $sheetXml .= '</row>';

        // Row 5: Column Headers (Brand Red Fill, Bold White Text)
        $sheetXml .= '<row r="5" ht="26" customHeight="1">';
        foreach ($headerIndexes as $i => $sIdx) {
            $cellRef = $cols[$i].'5';
            $sheetXml .= '<c r="'.$cellRef.'" t="s" s="4"><v>'.$sIdx.'</v></c>';
        }
        $sheetXml .= '</row>';

        // Data Rows (Rows 6+)
        foreach ($dataRows as $rIdx => $rowStringIndexes) {
            $rowNum = $rIdx + 6;
            $sheetXml .= '<row r="'.$rowNum.'" ht="22">';
            foreach ($rowStringIndexes as $cIdx => $sIdx) {
                $cellRef = $cols[$cIdx].$rowNum;
                // Column 6 (index 6, 'G') is Message, use wrap text style (5)
                $styleId = ($cIdx === 6) ? '5' : '6';
                $sheetXml .= '<c r="'.$cellRef.'" t="s" s="'.$styleId.'"><v>'.$sIdx.'</v></c>';
            }
            $sheetXml .= '</row>';
        }

        $sheetXml .= '</sheetData>';
        $sheetXml .= '<autoFilter ref="A5:I'.max(6, $lastRowNum).'"/>';
        $sheetXml .= '</worksheet>';

        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $binary = file_get_contents($tempFile);
        @unlink($tempFile);

        return $binary;
    }
}
